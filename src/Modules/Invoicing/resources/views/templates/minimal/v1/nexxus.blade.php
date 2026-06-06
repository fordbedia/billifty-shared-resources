@php
	$accent = data_get($scheme ?? null, 'main.code', '#ff3108') ?: '#ff3108';
	$accentDark = data_get($scheme ?? null, 'dark.code', $accent) ?: $accent;
	$projectTitle = data_get($firstItem, 'name') ?: 'Project Details';
	$projectReference = $invoice->reference ?: null;
@endphp

<div class="nexxus--theme invoice-root nexxus-root scheme cat">
	<div class="nexxus-sheet">
		<header class="nexxus-header">
			<section class="brand-cell">
				<div class="brand-lockup">
					@if($logoSrc)
						<img src="{{ $logoSrc }}" alt="Business Logo" class="logo"/>
					@endif
					<div class="brand-name">{{ $businessName }}</div>
				</div>

				<div class="business-lines">
					@foreach($businessInfoRows as $row)
						<div>@if($row['label'])<span>{{ $row['label'] }}:</span> @endif{{ $row['value'] }}</div>
					@endforeach
				</div>
			</section>

			<section class="invoice-cell">
				<div class="invoice-title">INVOICE</div>
				<div class="invoice-meta">
					<div class="meta-row">
						<span>Invoice No:</span>
						<strong>{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</strong>
					</div>
					<div class="meta-row">
						<span>Date:</span>
						<strong>{{ $fmtDate($invoice->issued_on ?? null) }}</strong>
					</div>
					<div class="meta-row">
						<span>Due Date:</span>
						<strong>{{ $fmtDate($invoice->due_on ?? null) }}</strong>
					</div>
				</div>
			</section>
		</header>

		<div class="accent-rule"></div>

		<section class="party-grid">
			<div class="party-cell party-left">
				<div class="section-label">Billed To</div>
				<div class="client-name">{{ $clientName }}</div>
				@foreach($clientInfoRows as $row)
					<div>@if($row['label'])<span>{{ $row['label'] }}:</span> @endif{{ $row['value'] }}</div>
				@endforeach
			</div>
		</section>

		<section class="items-grid{{ $hasLineDiscount ? ' has-line-discount' : '' }}">
			<div class="item-row item-head">
				<div class="desc-col">Description</div>
				<div class="qty-col">Qty</div>
				<div class="money-col">Unit Price</div>
				<div class="tax-col">Tax</div>
				@if($hasLineDiscount)
					<div class="tax-col">Discount</div>
				@endif
				<div class="money-col">Amount</div>
			</div>

			@forelse($items as $it)
				<div class="item-row">
					<div class="desc-col">
						<div class="item-title">{{ $it->name ?? 'Item' }}</div>
						@if(!empty($it->description))
							<div class="item-description">{{ $it->description }}</div>
						@endif
					</div>
					<div class="qty-col">{{ $fmtQuantity($it) }}</div>
					<div class="money-col">{{ $fmtItemUnitPrice($it) }}</div>
					<div class="tax-col">{{ $fmtItemTaxRate($it) }}</div>
					@if($hasLineDiscount)
						<div class="tax-col">{{ $fmtItemLineDiscount($it) }}</div>
					@endif
					<div class="money-col item-amount">{{ $fmtItemLineTotal($it) }}</div>
				</div>
			@empty
				<div class="empty-cell">No items.</div>
			@endforelse
		</section>

		<section class="payment-summary">
			<div class="payment-cell">
				<div class="section-label">Payment Information</div>
				<div class="payment-panel">
					@if($hasBankTransferDetails)
						<div class="payment-details">
							@foreach($bankTransferDetails as $label => $value)
								<div class="payment-row"><span>{{ $label }}:</span><strong>{{ $value }}</strong></div>
							@endforeach
						</div>
					@else
						<div class="muted-line">&mdash;</div>
					@endif
{{--					<div class="reference-line">Please include invoice number--}}
{{--						<strong>{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</strong> in your payment reference.--}}
{{--					</div>--}}
				</div>
			</div>

			<div class="summary-cell">
				<div class="summary-list">
					@foreach($invoiceTotalsRows as $totalRow)
						@if(in_array($totalRow['type'], ['total', 'balance_due'], true))
							<div class="summary-row total-row">
								<span>{{ $totalRow['label'] }}</span>
								<strong>{{ $totalRow['value'] }}</strong>
							</div>
						@else
							<div class="summary-row{{ in_array($totalRow['type'], ['discount', 'amount_paid'], true) ? ' discount-row' : '' }}">
								<span>{{ $totalRow['label'] }}</span>
								<strong>{{ $totalRow['value'] }}</strong>
							</div>
						@endif
					@endforeach
					@include('invoicing::templates.paid-stamp')
				</div>
			</div>
		</section>

		<footer class="footer-band">
			<section class="footer-section">
				<div class="footer-label">Notes</div>
				<div class="footer-copy">@if($invoice->notes){!! nl2br(e($invoice->notes)) !!}@else &mdash; @endif</div>
			</section>

			<section class="footer-section">
				<div class="footer-label">Terms &amp; Conditions</div>
				<div class="footer-copy">@if($invoice->terms){!! nl2br(e($invoice->terms)) !!}@else &mdash; @endif</div>
			</section>

			@include('invoicing::templates.payment-method', ['invoice' => $invoice, 'pi' => $pi])
		</footer>

		{!! $watermark() !!}
	</div>
</div>

<style>
	.nexxus-root,
	.nexxus-root * {
		box-sizing: border-box;
	}

	.nexxus-root {
		width: 100%;
		color: #242932;
		font-family: {{ $fontFamily }};
		font-size: 10px;
		line-height: 1.38;
		background: #ffffff;
	}

	.nexxus-root .nexxus-sheet {
		width: 100%;
		max-width: 100%;
		min-height: 100%;
		padding: 38px 40px 0;
		background: #ffffff;
		overflow: hidden;
		box-shadow: 0 10px 30px rgba(22, 27, 34, 0.08);
	}

	.nexxus-root .nexxus-header {
		display: grid;
		grid-template-columns: 58% 42%;
		align-items: start;
	}

	.nexxus-root .brand-cell {
		padding-right: 28px;
	}

	.nexxus-root .invoice-cell {
		text-align: right;
	}

	.nexxus-root .brand-lockup {
		display: flex;
		align-items: center;
		gap: 10px;
		margin-bottom: 22px;
		flex-wrap: wrap;
	}

	.nexxus-root .logo,
	.nexxus-root .logo-placeholder {
		display: block;
		flex: 0 0 auto;
		width: 25px;
		height: 25px;
		border-radius: 3px;
	}

	.nexxus-root .logo {
		width: 130px;
		max-width: 100%;
		height: auto;
		object-fit: contain;
		background: #ffffff;
	}

	.nexxus-root .logo-placeholder {
		background: {{ $accent }};
		color: #ffffff;
		font-size: 13px;
		line-height: 25px;
		text-align: center;
		font-weight: 800;
	}

	.nexxus-root .brand-name {
		color: #1e232b;
		font-size: 16px;
		line-height: 20px;
		font-weight: 800;
		letter-spacing: 0.04em;
		text-transform: uppercase;
	}

	.nexxus-root .business-lines {
		color: #6f7782;
		font-size: 9px;
		line-height: 14px;
	}

	.nexxus-root .invoice-title {
		margin: 0 0 17px;
		color: #20242c;
		font-size: 21px;
		line-height: 25px;
		font-weight: 800;
		letter-spacing: 0.02em;
	}

	.nexxus-root .invoice-meta {
		display: grid;
		gap: 7px;
		width: 205px;
		margin-left: auto;
		color: #656d78;
		font-size: 9px;
		line-height: 13px;
	}

	.nexxus-root .meta-row {
		display: grid;
		grid-template-columns: 92px 1fr;
		gap: 12px;
		align-items: start;
	}

	.nexxus-root .meta-row span,
	.nexxus-root .meta-row strong {
		text-align: right;
	}

	.nexxus-root .meta-row strong {
		color: #232832;
		font-weight: 700;
	}

	.nexxus-root .accent-rule {
		height: 2px;
		margin: 31px -40px 33px;
		background: {{ $accent }};
	}

	.nexxus-root .party-grid {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		margin-bottom: 44px;
	}

	.nexxus-root .party-cell {
		color: #626b76;
		font-size: 9px;
		line-height: 15px;
	}

	.nexxus-root .party-left {
		padding-left: 39px;
		padding-right: 35px;
	}

	.nexxus-root .party-right {
		padding-left: 43px;
		padding-right: 28px;
	}

	.nexxus-root .section-label,
	.nexxus-root .footer-label {
		margin: 0 0 12px;
		color: {{ $accentDark }};
		font-size: 9px;
		line-height: 11px;
		font-weight: 800;
		letter-spacing: 0.08em;
		text-transform: uppercase;
	}

	.nexxus-root .client-name {
		margin-bottom: 7px;
		color: #242932;
		font-size: 12px;
		line-height: 16px;
		font-weight: 800;
	}

	.nexxus-root .items-grid {
		display: grid;
		width: 100%;
		margin-bottom: 29px;
	}

	.nexxus-root .item-row {
		display: grid;
		grid-template-columns: minmax(0, 47%) 10% 16% 11% 16%;
		align-items: start;
		border-bottom: 1px solid #e8ebef;
	}

	.nexxus-root .items-grid.has-line-discount .item-row {
		grid-template-columns: minmax(0, 40%) 8% 14% 10% 10% 14%;
	}

	.nexxus-root .item-head {
		color: #68717d;
		border-bottom: 1px solid #242932;
		font-size: 8px;
		line-height: 11px;
		font-weight: 700;
		letter-spacing: 0.08em;
		text-transform: uppercase;
	}

	.nexxus-root .item-row > div {
		min-width: 0;
		padding: 15px 0 16px;
		color: #5f6874;
		font-size: 9px;
		line-height: 13px;
	}

	.nexxus-root .item-head > div {
		padding: 0 0 13px;
	}

	.nexxus-root .desc-col {
		padding-left: 39px !important;
		padding-right: 18px !important;
		text-align: left;
	}

	.nexxus-root .qty-col,
	.nexxus-root .tax-col {
		text-align: center;
	}

	.nexxus-root .money-col {
		padding-right: 30px !important;
		text-align: right;
	}

	.nexxus-root .item-title {
		margin-bottom: 5px;
		color: #252a33;
		font-size: 10px;
		line-height: 13px;
		font-weight: 800;
	}

	.nexxus-root .item-description {
		color: #6f7782;
		font-size: 8px;
		line-height: 11px;
	}

	.nexxus-root .item-amount {
		color: #252a33 !important;
		font-weight: 800;
	}

	.nexxus-root .empty-cell {
		padding: 15px 0 16px 39px;
		border-bottom: 1px solid #e8ebef;
		color: #7a828c;
		font-size: 9px;
	}

	.nexxus-root .payment-summary {
		display: grid;
		grid-template-columns: 55% 45%;
		margin-bottom: 50px;
	}

	.nexxus-root .payment-cell {
		padding-left: 39px;
		padding-right: 50px;
	}

	.nexxus-root .summary-cell {
		padding-left: 8px;
		padding-right: 30px;
	}

	.nexxus-root .payment-panel {
		width: 245px;
		min-height: 112px;
		padding: 17px 20px 14px;
		background: #f6f7f9;
		color: #5f6874;
		font-size: 9px;
		line-height: 14px;
	}

	.nexxus-root .payment-details {
		display: grid;
		gap: 6px;
		margin-bottom: 12px;
	}

	.nexxus-root .payment-row {
		display: grid;
		grid-template-columns: 76px minmax(0, 1fr);
		gap: 8px;
	}

	.nexxus-root .payment-row span {
		color: #545c67;
		font-weight: 700;
	}

	.nexxus-root .payment-row strong {
		min-width: 0;
		color: #252a33;
		font-weight: 700;
		overflow-wrap: anywhere;
	}

	.nexxus-root .nexxus-payment-list {
		margin: 0 0 12px;
		padding: 0;
		list-style: none;
	}

	.nexxus-root .nexxus-payment-list li {
		margin: 0 0 6px;
	}

	.nexxus-root .nexxus-payment-list .label {
		color: #545c67;
		font-weight: 700;
	}

	.nexxus-root .nexxus-payment-list .value {
		color: #252a33;
		font-weight: 700;
	}

	.nexxus-root .payment-note {
		margin: 0 0 9px;
		color: #5f6874;
	}

	.nexxus-root .reference-line {
		padding-top: 4px;
		color: #6f7782;
		font-size: 8px;
		line-height: 12px;
	}

	.nexxus-root .reference-line strong {
		color: #252a33;
	}

	.nexxus-root .summary-list {
		display: grid;
		gap: 16px;
		color: #5d6672;
		font-size: 10px;
		line-height: 14px;
	}

	.nexxus-root .summary-row {
		display: grid;
		grid-template-columns: minmax(0, 1fr) auto;
		align-items: start;
		gap: 20px;
	}

	.nexxus-root .summary-row strong {
		color: #252a33;
		font-weight: 800;
		text-align: right;
	}

	.nexxus-root .summary-row.total-row {
		padding-top: 12px;
		color: #252a33;
		font-size: 12px;
		line-height: 18px;
		font-weight: 800;
		text-transform: uppercase;
	}

	.nexxus-root .summary-row.total-row strong {
		color: {{ $accent }};
		font-size: 21px;
		line-height: 24px;
		text-transform: none;
	}

	.nexxus-root .footer-band {
		margin: 0 -40px;
		padding: 30px 40px 31px;
		background: #f7f8fa;
	}

	.nexxus-root .footer-section {
		margin: 0 0 18px;
		padding: 0 39px;
	}

	.nexxus-root .footer-copy {
		color: #6c7480;
		font-size: 9px;
		line-height: 14px;
	}

	.nexxus-root .muted-line {
		color: #8a919b;
	}

	.nexxus-root .watermark {
		margin: 18px 0 0;
		color: #8a919b;
		font-size: 9px;
		text-align: center;
	}
</style>
