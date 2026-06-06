@php
	$ink        = '#2c2f39';
	$muted      = '#7b8190';
	$bg         = '#ffffff';
	$panelSoft  = '#f5f6fb';
	$border     = '#edf0f6';
	$accent     = $scheme->main->code;
@endphp

<div class="ledger--theme ledger-root scheme cat">
	<div class="ledger-sheet">
		<div class="ledger-header">
			<div class="brand-cell">
				<div class="brand-lockup">
					@if($logoSrc)
						<div class="brand-mark-cell">
							<img src="{{ $logoSrc }}" class="logo" alt="logo"/>
						</div>
					@endif
					<div class="brand-copy">
						<div class="brand-name">{{ $businessName }}</div>
						<div class="brand-tagline">
							@if($businessLegalName !== '' && strcasecmp($businessLegalName, $businessName) !== 0)
								{{ $businessLegalName }}
							@elseif($displayText(data_get($bp, 'website')) !== '')
								{{ $displayText(data_get($bp, 'website')) }}
							@elseif($displayText(data_get($bp, 'email')) !== '')
								{{ $displayText(data_get($bp, 'email')) }}
							@else
								Creative Solution Studio
							@endif
						</div>
					</div>
				</div>
			</div>

			<div class="invoice-cell">
				<div class="invoice-title">INVOICE</div>
				<div class="invoice-meta">Invoice #: <span>{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</span></div>
				<div class="invoice-meta">Date: <span>{{ $fmtDate($invoice->issued_on ?? null) }}</span></div>
			</div>
		</div>

		<div class="header-rule"></div>

		<div class="party-row">
			<div class="party-cell bill-cell">
				<div class="section-label">Billed To</div>
				<div class="bill-card">
					<div class="party-name">{{ $clientName }}</div>
					@foreach($clientInfoRows as $row)
						<div>@if($row['label'])<span>{{ $row['label'] }}:</span> @endif{{ $row['value'] }}</div>
					@endforeach
				</div>
			</div>

			<div class="party-cell from-cell">
				<div class="section-label">From</div>
				<div class="party-name">{{ $businessName }}</div>
				@foreach($businessInfoRows as $row)
					<div>@if($row['label'])<span>{{ $row['label'] }}:</span> @endif{{ $row['value'] }}</div>
				@endforeach
			</div>
		</div>

		<div class="items-list {{ $hasLineDiscount ? 'has-discount' : '' }}">
			<div class="item-row item-head">
				<div class="item-col desc">Description</div>
				<div class="item-col qty center">Qty</div>
				<div class="item-col unit amount-col">Unit Price</div>
				<div class="item-col tax center">Tax</div>
				@if($hasLineDiscount)
					<div class="item-col discount center">Discount</div>
				@endif
				<div class="item-col amount amount-col">Amount</div>
			</div>

			@if($itemCount === 0)
				<div class="item-empty muted center">No items.</div>
			@else
				@foreach($items as $it)
					<div class="item-row item-line">
						<div class="item-col desc">
							<div class="item-title">{{ $it->name ?? 'Item' }}</div>
							@if(!empty($it->description))
								<div class="item-description">{{ $it->description }}</div>
							@endif
						</div>
						<div class="item-col qty center">{{ $fmtQuantity($it) }}</div>
						<div class="item-col unit amount-col">{{ $fmtItemUnitPrice($it) }}</div>
						<div class="item-col tax center">{{ $fmtItemTaxRate($it) }}</div>
						@if($hasLineDiscount)
							<div class="item-col discount center">{{ $fmtItemLineDiscount($it) }}</div>
						@endif
						<div class="item-col amount amount-col item-amount">{{ $fmtItemLineTotal($it) }}</div>
					</div>
				@endforeach
			@endif
		</div>

		<div class="summary-band">
			<div class="summary-layout">
				<div class="payment-cell">
					<div class="section-label">Payment Information</div>
					@if($hasBankTransferDetails)
						<div class="payment-list">
							@foreach($bankTransferDetails as $label => $value)
								<div class="payment-row"><span>{{ $label }}</span><strong>{{ $value }}</strong></div>
							@endforeach
						</div>
					@else
						<div class="muted">Payment details unavailable.</div>
					@endif
				</div>

				<div class="totals-cell">
					<div class="totals-card">
						@foreach($invoiceTotalsRows as $totalRow)
							@if(in_array($totalRow['type'], ['total', 'balance_due'], true))
								<div class="total-due">
									<span class="total-label">{{ $totalRow['label'] }}</span>
									<span class="total-value">{{ $totalRow['value'] }}</span>
								</div>
							@else
								<div class="total-row{{ in_array($totalRow['type'], ['discount', 'amount_paid'], true) ? ' discount' : '' }}">
									<span class="total-label">{{ $totalRow['label'] }}</span>
									<span class="total-value">{{ $totalRow['value'] }}</span>
								</div>
							@endif
						@endforeach
						@include('invoicing::templates.paid-stamp')
					</div>
				</div>
			</div>
		</div>

		<div class="footer-row">
			<div class="footer-cell">
				<h4>Notes</h4>
				<p>@if($invoice->notes){{ $invoice->notes }}@else &mdash; @endif</p>
			</div>
			<div class="footer-cell">
				<h4>Terms &amp; Conditions</h4>
				<p>@if($invoice->terms){{ $invoice->terms }}@else &mdash; @endif</p>
			</div>
			@include('invoicing::templates.payment-method', ['invoice' => $invoice, 'pi' => $pi])
		</div>

		{!! $watermark() !!}
	</div>

	<style>
		.ledger-root .ledger-sheet {
			width: 100%;
			max-width: 100%;
			box-sizing: border-box;
			background: {{ $bg }};
			border-radius: 8px;
			padding: 46px 42px 32px;
			font-family: {{ $fontFamily }};
			color: {{ $ink }};
			box-shadow: 0 8px 24px rgba(28, 31, 42, 0.08);
			overflow: hidden;
		}

		.ledger-root .muted {
			color: {{ $muted }};
		}

		.ledger-root .center {
			text-align: center;
		}

		.ledger-root .amount-col {
			text-align: right;
		}

		.ledger-root .ledger-header {
			display: flex;
			align-items: flex-start;
			justify-content: space-between;
			margin-bottom: 38px;
		}

		.ledger-root .brand-cell {
			flex: 0 0 55%;
			max-width: 55%;
			padding-right: 20px;
			box-sizing: border-box;
		}

		.ledger-root .invoice-cell {
			flex: 0 0 45%;
			max-width: 45%;
			text-align: right;
		}

		.ledger-root .brand-lockup {
			display: flex;
			align-items: flex-start;
			flex-wrap: wrap;
		}

		.ledger-root .brand-mark-cell {
			flex: 0 0 auto;
			padding-top: 1px;
			padding-bottom: 5px;
		}

		.ledger-root .brand-copy {
			min-width: 0;
		}

		.ledger-root .logo {
			display: block;
			width: 130px;
			max-width: 100%;
			height: auto;
			object-fit: contain;
			/*border-radius:4px;*/
		}

		.ledger-root .logo-placeholder {
			display: block;
			width: 27px;
			height: 27px;
			border-radius: 4px;
			background: {{ $accent }};
			color: #ffffff;
			text-align: center;
			font-size: 13px;
			line-height: 27px;
			font-weight: 800;
		}

		.ledger-root .brand-name {
			margin: 0 0 2px;
			font-size: 20px;
			line-height: 23px;
			font-weight: 900;
			color: #30333f;
		}

		.ledger-root .brand-tagline {
			font-size: 10px;
			line-height: 14px;
			color: #8a90a0;
		}

		.ledger-root .invoice-title {
			margin: 0 0 8px;
			color: {{ $accent }};
			font-size: 25px;
			line-height: 30px;
			letter-spacing: 0.18em;
			font-weight: 900;
		}

		.ledger-root .invoice-meta {
			margin-top: 3px;
			color: #626978;
			font-size: 11px;
			line-height: 16px;
			font-weight: 700;
		}

		.ledger-root .invoice-meta span {
			color: #2f3440;
			font-weight: 900;
		}

		.ledger-root .header-rule {
			height: 1px;
			margin: 0 -42px 30px;
			background: {{ $border }};
		}

		.ledger-root .party-row {
			display: flex;
			align-items: flex-start;
			margin-bottom: 32px;
		}

		.ledger-root .party-cell {
			flex: 0 0 50%;
			max-width: 50%;
			color: #6f7684;
			font-size: 11px;
			line-height: 17px;
			box-sizing: border-box;
		}

		.ledger-root .bill-cell {
			padding-right: 30px;
		}

		.ledger-root .from-cell {
			padding-left: 18px;
		}

		.ledger-root .section-label {
			margin: 0 0 14px;
			color: #9aa0ae;
			font-size: 10px;
			line-height: 14px;
			text-transform: uppercase;
			letter-spacing: 0.14em;
			font-weight: 900;
		}

		.ledger-root .bill-card {
			min-height: 96px;
			border-radius: 6px;
			background: {{ $panelSoft }};
			padding: 20px 22px 18px;
			box-sizing: border-box;
		}

		.ledger-root .party-name {
			margin-bottom: 5px;
			color: #2f3340;
			font-size: 14px;
			line-height: 18px;
			font-weight: 900;
		}

		.ledger-root .contact-line {
			margin-top: 9px;
		}

		.ledger-root .status-pill {
			display: inline-block;
			margin-top: 28px;
			border-radius: 4px;
			padding: 7px 14px;
			font-size: 11px;
			line-height: 15px;
			font-weight: 700;
		}

		.ledger-root .status-pill.is-pending,
		.ledger-root .status-pill.is-paid {
			background: #eef9f1;
			color: #219656;
		}

		.ledger-root .status-pill.is-draft {
			background: #f1f3f7;
			color: #6d7482;
		}

		.ledger-root .status-pill.is-void {
			background: #fff0f0;
			color: #be4040;
		}

		.ledger-root .items-list {
			width: 100%;
			margin-bottom: 38px;
		}

		.ledger-root .item-row {
			display: flex;
			align-items: flex-start;
			width: 100%;
			box-sizing: border-box;
		}

		.ledger-root .item-head {
			color: #8d94a4;
			font-size: 10px;
			line-height: 14px;
			text-transform: uppercase;
			letter-spacing: 0.1em;
			font-weight: 900;
		}

		.ledger-root .item-col {
			padding: 0 12px 20px;
			box-sizing: border-box;
			min-width: 0;
		}

		.ledger-root .item-line .item-col {
			padding-bottom: 24px;
			color: #4c5360;
			font-size: 12px;
			line-height: 17px;
			text-transform: none;
			letter-spacing: 0;
			font-weight: 400;
		}

		.ledger-root .items-list .desc {
			flex: 0 0 45%;
			max-width: 45%;
			padding-left: 8px;
		}

		.ledger-root .items-list .qty {
			flex: 0 0 10%;
			max-width: 10%;
		}

		.ledger-root .items-list .unit {
			flex: 0 0 15%;
			max-width: 15%;
		}

		.ledger-root .items-list .tax {
			flex: 0 0 10%;
			max-width: 10%;
		}

		.ledger-root .items-list .amount {
			flex: 0 0 20%;
			max-width: 20%;
		}

		.ledger-root .items-list.has-discount .desc {
			flex-basis: 40%;
			max-width: 40%;
		}

		.ledger-root .items-list.has-discount .qty {
			flex-basis: 9%;
			max-width: 9%;
		}

		.ledger-root .items-list.has-discount .unit {
			flex-basis: 14%;
			max-width: 14%;
		}

		.ledger-root .items-list.has-discount .tax {
			flex-basis: 9%;
			max-width: 9%;
		}

		.ledger-root .items-list .discount {
			flex: 0 0 11%;
			max-width: 11%;
		}

		.ledger-root .items-list.has-discount .amount {
			flex-basis: 17%;
			max-width: 17%;
		}

		.ledger-root .item-title,
		.ledger-root .item-amount {
			color: #2f3340;
			font-weight: 900;
		}

		.ledger-root .item-description {
			margin-top: 3px;
			color: #8b91a0;
			font-size: 10px;
			line-height: 14px;
		}

		.ledger-root .item-empty {
			padding: 12px 8px 24px;
			font-size: 12px;
			line-height: 17px;
		}

		.ledger-root .summary-band {
			margin: 0 -42px 34px;
			background: {{ $panelSoft }};
		}

		.ledger-root .summary-layout {
			display: flex;
			align-items: flex-start;
			width: 100%;
			box-sizing: border-box;
		}

		.ledger-root .payment-cell,
		.ledger-root .totals-cell {
			flex: 0 0 50%;
			max-width: 50%;
			padding: 28px 42px;
			box-sizing: border-box;
		}

		.ledger-root .payment-cell {
			padding-right: 24px;
		}

		.ledger-root .totals-cell {
			padding-left: 18px;
		}

		.ledger-root .payment-list {
			width: 100%;
		}

		.ledger-root .payment-row {
			display: flex;
			align-items: flex-start;
			justify-content: space-between;
			padding: 10px 14px;
			border-bottom: 1px solid #ffffff;
			background: #fdfdff;
			color: #7a818f;
			font-size: 11px;
			line-height: 16px;
			box-sizing: border-box;
		}

		.ledger-root .payment-row span {
			flex: 0 0 42%;
			max-width: 42%;
			color: #8a91a0;
		}

		.ledger-root .payment-row strong {
			flex: 1 1 auto;
			color: #343946;
			font-weight: 800;
			text-align: right;
			word-break: break-word;
		}

		.ledger-root .payment-fallback {
			padding: 10px 14px;
			background: #fdfdff;
			color: #343946;
			font-size: 11px;
			line-height: 16px;
			box-sizing: border-box;
		}

		.ledger-root .paymentinfo {
			margin: 0;
			padding: 0;
			list-style: none;
		}

		.ledger-root .totals-card {
			border-radius: 6px;
			background: #ffffff;
			padding: 20px 22px 19px;
			box-sizing: border-box;
		}

		.ledger-root .total-row,
		.ledger-root .total-due {
			display: flex;
			align-items: flex-start;
			justify-content: space-between;
		}

		.ledger-root .total-row {
			padding: 5px 0;
			color: #717887;
			font-size: 11px;
			line-height: 16px;
		}

		.ledger-root .total-value {
			color: #2f3440;
			font-weight: 800;
			text-align: right;
		}

		.ledger-root .total-row.discount .total-value {
			color: #db6572;
		}

		.ledger-root .total-due {
			margin-top: 15px;
			padding-top: 13px;
			border-top: 1px solid{{ $border }};
			font-size: 15px;
			line-height: 22px;
			font-weight: 900;
			color: #2f3440;
		}

		.ledger-root .total-due .total-value {
			color: {{ $accent }};
			font-size: 20px;
			letter-spacing: 0.02em;
		}

		.ledger-root .footer-row {
			display: flex;
			align-items: flex-start;
		}

		.ledger-root .footer-cell {
			flex: 0 0 50%;
			max-width: 50%;
			padding-right: 32px;
			box-sizing: border-box;
		}

		.ledger-root .footer-cell:last-child {
			padding-right: 0;
			padding-left: 18px;
		}

		.ledger-root .footer-cell h4 {
			margin: 0 0 9px;
			color: #8f96a5;
			font-size: 10px;
			line-height: 14px;
			text-transform: uppercase;
			letter-spacing: 0.12em;
			font-weight: 900;
		}

		.ledger-root .footer-cell p {
			margin: 0;
			white-space: pre-wrap;
			color: #6e7584;
			font-size: 11px;
			line-height: 17px;
		}

		.ledger-root .watermark {
			margin-top: 30px;
			text-align: center;
			color: #a0a6b2;
			font-size: 11px;
			line-height: 15px;
			font-weight: bold;
		}

		@media print {
			.ledger-root .ledger-sheet {
				box-shadow: none;
				border-radius: 0;
				padding: 42px 38px 30px;
			}

			.ledger-root .header-rule,
			.ledger-root .summary-band {
				margin-left: -38px;
				margin-right: -38px;
			}

			.ledger-root .payment-cell,
			.ledger-root .totals-cell {
				padding-left: 38px;
				padding-right: 38px;
			}
		}
	</style>
</div>
