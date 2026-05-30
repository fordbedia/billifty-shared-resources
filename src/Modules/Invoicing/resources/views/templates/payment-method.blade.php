@php
	use SimpleSoftwareIO\QrCode\Facades\QrCode;

	$paymentInfos = collect($paymentInformations ?? []);

	if (isset($pi) && $pi) {
		$paymentInfos = $paymentInfos
			->prepend($pi)
			->unique(fn ($paymentInfo) => data_get($paymentInfo, 'id') ?? spl_object_id((object) $paymentInfo))
			->values();
	}

	$paymentToken = $paymentToken ?? null;
	$payUrl = $payUrl ?? null;
	$paymentAccent = data_get($scheme ?? null, 'main.code', '#111827') ?: '#111827';
	$onlinePaymentMethodLabels = [
		'stripe' => 'Stripe',
		'paypal' => 'PayPal',
		'cash_app' => 'Cash App',
	];

	$configuredOnlinePaymentMethods = $paymentInfos
		->map(fn ($paymentInfo) => $paymentMethodKey($paymentInfo))
		->filter(fn ($paymentMethod) => array_key_exists($paymentMethod, $onlinePaymentMethodLabels))
		->unique()
		->values();

	$onlinePaymentMethods = collect(array_keys($onlinePaymentMethodLabels))
		->filter(fn ($paymentMethod) => $configuredOnlinePaymentMethods->contains($paymentMethod))
		->values();

	$onlinePaymentLabels = $onlinePaymentMethods
		->map(fn ($paymentMethod) => $onlinePaymentMethodLabels[$paymentMethod])
		->values();

	$onlinePaymentLabelCount = $onlinePaymentLabels->count();
	$onlinePaymentSummary = match (true) {
		$onlinePaymentLabelCount === 1 => $onlinePaymentLabels->first(),
		$onlinePaymentLabelCount === 2 => $onlinePaymentLabels->implode(' or '),
		$onlinePaymentLabelCount > 2 => $onlinePaymentLabels->slice(0, -1)->implode(', ') . ', or ' . $onlinePaymentLabels->last(),
		default => null,
	};

	$paymentMethodBlocks = collect();

	if ($payUrl && $onlinePaymentSummary) {
		$qrCodeSrc = null;

		try {
			$qrCodeSvg = QrCode::size(86)->format('svg')->generate($payUrl);
			$qrCodeSrc = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
		} catch (\Throwable $e) {
			$qrCodeSrc = null;
		}

		$paymentMethodBlocks = collect([[
			'method' => 'online',
			'label' => 'Payment Link',
			'onlinePaymentSummary' => $onlinePaymentSummary,
			'hasOnlinePaymentLink' => true,
			'qrCodeSrc' => $qrCodeSrc,
			'detailRows' => [],
			'shouldRender' => true,
		]]);
	}

	$showComponentOnNonHtml = true;
	if (isset($renderContext) && $renderContext === 'html') {
		$showComponentOnNonHtml = false;
	}
@endphp

@if($paymentMethodBlocks->isNotEmpty() && $showComponentOnNonHtml)
	@foreach($paymentMethodBlocks as $paymentMethodBlock)
		<section
			class="payment-method-block payment-method-{{ str_replace('_', '-', $paymentMethodBlock['method'] ?: 'generic') }}">
			<h4 class="payment-method-heading">
				@if($paymentMethodBlock['hasOnlinePaymentLink'])
					Payment Link
				@else
					{{ $paymentMethodBlock['label'] }}
				@endif
			</h4>

			@if($paymentMethodBlock['hasOnlinePaymentLink'])
				<div class="payment-method-link-card">
					@if($paymentMethodBlock['qrCodeSrc'])
						<img
							class="payment-method-qr"
							src="{{ $paymentMethodBlock['qrCodeSrc'] }}"
							alt="Invoice payment QR code"
						/>
					@endif
					<div class="payment-method-link-copy">
						<div class="payment-method-title">Pay
							with {{ $paymentMethodBlock['onlinePaymentSummary'] }}</div>
						<div class="payment-method-text">Scan the QR code or open the secure payment link.</div>
						<a class="payment-method-link" target="_blank" rel="noopener noreferrer" href="{{ $payUrl }}">
							Open Payment Link
						</a>
					</div>
				</div>
			@endif

			@if(count($paymentMethodBlock['detailRows']) > 0)
				<dl class="payment-method-list">
					@foreach($paymentMethodBlock['detailRows'] as $name => $value)
						<div class="payment-method-row">
							<dt>{{ $name }}</dt>
							<dd>{{ $value }}</dd>
						</div>
					@endforeach
				</dl>
			@endif
		</section>
	@endforeach

	<style>
		.payment-method-block,
		.payment-method-block * {
			box-sizing: border-box;
		}

		.payment-method-block {
			--payment-method-accent: {{ $paymentAccent }};
			min-width: 0;
			overflow-wrap: anywhere;
		}

		.payment-method-heading {
			margin: 0 0 9px;
			color: #1f2937;
			font-size: 10px;
			line-height: 13px;
			font-weight: 900;
			letter-spacing: .1em;
			text-transform: uppercase;
		}

		.payment-method-link-card {
			display: flex;
			align-items: flex-start;
			gap: 11px;
			margin-bottom: 10px;
		}

		.payment-method-qr {
			display: block;
			flex: 0 0 58px;
			width: 58px;
			height: 58px;
			padding: 4px;
			background: #ffffff;
			border: 1px solid #e5e7eb;
		}

		.payment-method-link-copy {
			min-width: 0;
		}

		.payment-method-title {
			margin-bottom: 2px;
			color: #111827;
			font-size: 11px;
			line-height: 14px;
			font-weight: 900;
		}

		.payment-method-text {
			margin-bottom: 4px;
			color: #6b7280;
			font-size: 9px;
			line-height: 13px;
		}

		.payment-method-link {
			color: var(--payment-method-accent);
			font-size: 9px;
			line-height: 12px;
			font-weight: 900;
			text-decoration: none;
		}

		.payment-method-list {
			display: grid;
			gap: 5px;
			margin: 0;
			padding: 0;
		}

		.payment-method-row {
			display: grid;
			grid-template-columns: minmax(82px, .75fr) minmax(0, 1fr);
			gap: 8px;
			align-items: start;
		}

		.payment-method-row dt,
		.payment-method-row dd {
			margin: 0;
			font-size: 9px;
			line-height: 13px;
		}

		.payment-method-row dt {
			color: #6b7280;
			font-weight: 800;
		}

		.payment-method-row dd {
			color: #111827;
			font-weight: 800;
		}

		.aurora-root .sheet .footer-grid {
			grid-template-columns: repeat(3, minmax(0, 1fr));
			column-gap: 34px;
		}

		.aurora-root .payment-method-heading {
			margin: 0 0 8px;
			color: #202230;
			font-size: 11px;
			line-height: 15px;
			letter-spacing: .08em;
		}

		.aurora-root .payment-method-link-card {
			padding: 10px;
			background: #fbfbfd;
			border: 1px solid #eceff4;
			border-radius: 6px;
		}

		.aurora-root .payment-method-title,
		.aurora-root .payment-method-row dd {
			color: #202230;
		}

		.aurora-root .payment-method-text,
		.aurora-root .payment-method-row dt {
			color: #666d7a;
		}

		.ledger-root .ledger-sheet .footer-row {
			display: grid;
			grid-template-columns: repeat(3, minmax(0, 1fr));
			column-gap: 30px;
			align-items: start;
		}

		.ledger-root .ledger-sheet .footer-row .footer-cell,
		.ledger-root .ledger-sheet .footer-row .footer-cell:last-child {
			max-width: none;
			padding: 0;
		}

		.ledger-root .payment-method-heading {
			margin: 0 0 9px;
			color: #8f96a5;
			font-size: 10px;
			line-height: 14px;
			letter-spacing: .12em;
		}

		.ledger-root .payment-method-link-card,
		.ledger-root .payment-method-row {
			padding: 9px 11px;
			background: #fdfdff;
			border-bottom: 1px solid #edf0f6;
		}

		.ledger-root .payment-method-link-card {
			display: flex;
			border-radius: 5px 5px 0 0;
		}

		.ledger-root .payment-method-list {
			gap: 0;
		}

		.ledger-root .payment-method-row dt {
			color: #8a91a0;
		}

		.ledger-root .payment-method-row dd {
			color: #343946;
			text-align: right;
		}

		.simplifi-root .simplifi-sheet .footer-grid {
			grid-template-columns: repeat(3, minmax(0, 1fr));
			column-gap: 34px;
		}

		.simplifi-root .payment-method-heading {
			margin: 0 0 11px;
			color: #182333;
			font-size: 9px;
			line-height: 12px;
			letter-spacing: .18em;
		}

		.simplifi-root .payment-method-link-card {
			display: block;
			padding: 0;
		}

		.simplifi-root .payment-method-qr {
			margin: 0 0 9px;
			border-color: #e4e7eb;
		}

		.simplifi-root .payment-method-title,
		.simplifi-root .payment-method-row dd {
			color: #182333;
		}

		.simplifi-root .payment-method-text,
		.simplifi-root .payment-method-row dt {
			color: #707985;
		}

		.nexxus-root .nexxus-sheet .footer-band {
			display: grid;
			grid-template-columns: repeat(3, minmax(0, 1fr));
			gap: 0;
		}

		.nexxus-root .nexxus-sheet .footer-band .footer-section {
			margin: 0;
			padding: 0 24px;
		}

		.nexxus-root .payment-method-block {
			padding: 0 24px;
		}

		.nexxus-root .payment-method-heading {
			margin: 0 0 12px;
			color: var(--payment-method-accent);
			font-size: 9px;
			line-height: 11px;
			letter-spacing: .08em;
		}

		.nexxus-root .payment-method-link-card {
			display: block;
			padding: 13px 14px;
			background: #ffffff;
			border-left: 2px solid var(--payment-method-accent);
		}

		.nexxus-root .payment-method-qr {
			margin: 0 0 9px;
		}

		.nexxus-root .payment-method-row {
			grid-template-columns: 72px minmax(0, 1fr);
			gap: 7px;
		}

		.nexxus-root .payment-method-row dt,
		.nexxus-root .payment-method-text {
			color: #6c7480;
		}

		.nexxus-root .payment-method-row dd,
		.nexxus-root .payment-method-title {
			color: #242932;
		}

		.pulse-root .pulse-sheet .footer-band {
			grid-template-columns: repeat(3, minmax(0, 1fr));
			gap: 36px;
		}

		.pulse-root .payment-method-heading {
			margin: 0 0 12px;
			color: var(--payment-method-accent);
			font-family: inherit;
			font-size: 9px;
			line-height: 11px;
			letter-spacing: .08em;
		}

		.pulse-root .payment-method-link-card {
			padding: 0 0 0 12px;
			border-left: 4px solid var(--payment-method-accent);
		}

		.pulse-root .payment-method-title,
		.pulse-root .payment-method-row dd {
			color: #1f2426;
		}

		.pulse-root .payment-method-text,
		.pulse-root .payment-method-row dt {
			color: #586062;
		}

		.moderno-root .payment-method-block {
			max-width: 390px;
			margin-bottom: 22px;
		}

		.moderno-root .payment-method-heading {
			margin-bottom: 10px;
			color: #8b94a0;
			font-size: 8px;
			line-height: 10px;
			letter-spacing: .16em;
		}

		.moderno-root .payment-method-link-card {
			padding: 11px 12px;
			background: #fbfcfd;
			border: 1px solid #edf0f2;
			border-radius: 7px;
		}

		.moderno-root .payment-method-title,
		.moderno-root .payment-method-row dd {
			color: #111827;
			font-weight: 800;
		}

		.moderno-root .payment-method-text,
		.moderno-root .payment-method-row dt {
			color: #4b5563;
			font-weight: 700;
		}

		.mono-root .mono-body .mono-footer-grid {
			grid-template-columns: repeat(3, minmax(0, 1fr));
			column-gap: 16px;
		}

		.mono-root .payment-method-block {
			min-height: 121px;
			padding: 18px 19px 17px;
			background: linear-gradient(180deg, #f7fbff 0%, #ffffff 100%);
			border: 1px solid rgba(223, 227, 234, .42);
			border-radius: 4px;
			box-shadow: 0 10px 22px rgba(15, 23, 42, .035);
		}

		.mono-root .payment-method-heading {
			position: relative;
			margin: 0 0 13px;
			padding-left: 18px;
			color: #303645;
			font-size: 12px;
			line-height: 15px;
			font-weight: 900;
			letter-spacing: 0;
			text-transform: none;
		}

		.mono-root .payment-method-heading::before {
			content: "";
			position: absolute;
			top: 5px;
			left: 0;
			width: 12px;
			height: 7px;
			border-top: 2px solid var(--payment-method-accent);
			border-bottom: 2px solid var(--payment-method-accent);
			border-radius: 1px;
		}

		.mono-root .payment-method-link-card {
			display: block;
		}

		.mono-root .payment-method-qr {
			margin-bottom: 9px;
		}

		.mono-root .payment-method-row {
			grid-template-columns: minmax(74px, .8fr) minmax(0, 1fr);
		}

		.mono-root .payment-method-title,
		.mono-root .payment-method-row dd {
			color: #242a37;
		}

		.mono-root .payment-method-text,
		.mono-root .payment-method-row dt {
			color: #333a49;
		}

		.neo-root .payment-method-block {
			max-width: 430px;
			margin-top: 28px;
		}

		.neo-root .payment-method-heading {
			margin-bottom: 12px;
			color: #111111;
			font-size: 10px;
			line-height: 12px;
			letter-spacing: .16em;
		}

		.neo-root .payment-method-link-card {
			padding: 14px 16px;
			background: #111111;
			color: #ffffff;
		}

		.neo-root .payment-method-title,
		.neo-root .payment-method-link,
		.neo-root .payment-method-link-card .payment-method-text {
			color: #ffffff;
		}

		.neo-root .payment-method-list {
			grid-template-columns: repeat(2, minmax(0, 1fr));
			gap: 7px 36px;
		}

		.neo-root .payment-method-row {
			display: block;
		}

		.neo-root .payment-method-row dt {
			color: #111111;
			font-size: 9px;
			line-height: 12px;
			font-weight: 900;
			letter-spacing: .05em;
			text-transform: uppercase;
		}

		.neo-root .payment-method-row dd {
			color: #111111;
			font-size: 10px;
			line-height: 14px;
			font-weight: 700;
		}

		@media (max-width: 680px) {
			.aurora-root .sheet .footer-grid,
			.ledger-root .ledger-sheet .footer-row,
			.simplifi-root .simplifi-sheet .footer-grid,
			.nexxus-root .nexxus-sheet .footer-band,
			.pulse-root .pulse-sheet .footer-band,
			.mono-root .mono-body .mono-footer-grid {
				grid-template-columns: 1fr;
				row-gap: 22px;
			}
		}
	</style>
@endif
