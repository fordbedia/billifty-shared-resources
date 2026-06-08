@php
	$ink        = '#202230';
	$muted      = '#6b7280';
	$bg         = '#ffffff';
	$panelBg    = '#fbfbfd';
	$tableHead  = '#f4f5f8';
	$border     = '#eceff4';
	$stripe     = '#ffffff';
	$railColor  = $scheme->main->code;
	$accentSoft = $scheme->extra_light->code ?? '#f0edff';
@endphp

<div class="aurora--theme aurora-root scheme cat">
	<div class="sheet">
		<div class="top-rail"></div>

		<header class="header-grid">
			<section class="brand-block">
				<div class="brand-lockup">
					@if($logoSrc)
						<div class="brand-icon">
							<img src="{{ $logoSrc }}" alt="logo" class="logo"/>
						</div>
					@endif
					<div class="brand-name">{{ $businessName }}</div>
				</div>

				<div class="business-details">
					@foreach($businessInfoRows as $row)
						<div>@if($row['label'])<span>{{ $row['label'] }}:</span> @endif{{ $row['value'] }}</div>
					@endforeach
				</div>
			</section>

			<section class="invoice-block">
				<div class="invoice-title">INVOICE</div>
				<div class="invoice-meta">
					<div class="meta-row">
						<span>Invoice No.</span>
						<strong>{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</strong>
					</div>
					<div class="meta-row">
						<span>Issue Date</span>
						<strong>{{ $fmtDate($invoice->issued_on ?? null) }}</strong>
					</div>
					<div class="meta-row">
						<span>Due Date</span>
						<strong>{{ $fmtDate($invoice->due_on ?? null) }}</strong>
					</div>
				</div>
			</section>
		</header>

		<section class="info-grid">
			<div class="info-cell bill-to">
				<div class="section-label"><span class="label-mark"></span>Bill To</div>
				<div class="party-name">{{ $clientName }}</div>
				@foreach($clientInfoRows as $row)
					<div>@if($row['label'])<span>{{ $row['label'] }}:</span> @endif{{ $row['value'] }}</div>
				@endforeach
			</div>

			<div class="info-cell payment-info">
				<div class="section-label"><span class="label-mark"></span>Payment Information</div>
				@if($hasBankTransferDetails)
					@foreach($bankTransferDetails as $label => $value)
						<div><span>{{ $label }}:</span> {{ $value }}</div>
					@endforeach
				@else
					<div class="muted">Payment details unavailable.</div>
				@endif
			</div>
		</section>

		<section class="items-wrap{{ $hasLineDiscount ? ' has-line-discount' : '' }}">
			<div class="items-grid items-head">
				<div class="desc">Description</div>
				<div class="center">Qty</div>
				<div class="right">Unit Price</div>
				<div class="center">Tax</div>
				@if($hasLineDiscount)
					<div class="discount-col">Discount</div>
				@endif
				<div class="right">Amount</div>
			</div>

			@if($itemCount === 0)
				<div class="items-empty muted center">No items.</div>
			@else
				@foreach($items as $it)
					<div class="items-grid item-row">
						<div class="desc">
							<div class="item-title">{{ $it->name ?? 'Item' }}</div>
							@if(!empty($it->description))
								<div class="item-description">{{ $it->description }}</div>
							@endif
						</div>
						<div class="center">{{ $fmtQuantity($it) }}</div>
						<div class="right">{{ $fmtItemUnitPrice($it) }}</div>
						<div class="center">{{ $fmtItemTaxRate($it) }}</div>
						@if($hasLineDiscount)
							<div class="discount-col">{{ $fmtItemLineDiscount($it) }}</div>
						@endif
						<div class="right amount">{{ $fmtItemLineTotal($it) }}</div>
					</div>
				@endforeach
			@endif
		</section>

		<section class="totals">
			<div class="totals-panel">
				@if(!empty($invoiceShippingAddress))
					<div class="totals-row">
						<span>Ship To:</span>
						<strong>{{ $invoiceShippingAddress }}</strong>
					</div>
				@endif
				@foreach($invoiceTotalsRows as $totalRow)
					@if(in_array($totalRow['type'], ['total', 'balance_due'], true))
						<div class="totals-due">
							<span>{{ $totalRow['label'] }}</span>
							<strong>{{ $totalRow['value'] }}</strong>
						</div>
					@else
						<div class="totals-row{{ in_array($totalRow['type'], ['discount', 'amount_paid'], true) ? ' discount' : '' }}">
							<span>{{ $totalRow['label'] }}</span>
							<strong>{{ $totalRow['value'] }}</strong>
						</div>
					@endif
				@endforeach
				@include('invoicing::templates.paid-stamp')
			</div>
		</section>

		<div class="footer-rule"></div>

		<footer class="footer-grid">
			<section>
				<h4>Notes</h4>
				<p>@if($invoice->notes){{ $invoice->notes }}@else &mdash; @endif</p>
			</section>
			<section>
				<h4>Terms &amp; Conditions</h4>
				<p>@if($invoice->terms){{ $invoice->terms }}@else &mdash; @endif</p>
			</section>
			@include('invoicing::templates.payment-method', ['invoice' => $invoice, 'pi' => $pi])
		</footer>

		{!! $watermark() !!}
	</div>

	<style>
		.aurora-root .sheet {
			width: 100%;
			max-width: 100%;
			box-sizing: border-box;
			position: relative;
			background: {{ $bg }};
			border-radius: 8px;
			padding: 58px 54px 42px;
			font-family: {{ $fontFamily }};
			color: {{ $ink }};
			box-shadow: 0 10px 28px rgba(17, 24, 39, 0.08);
			overflow: hidden;
		}

		.aurora-root .top-rail {
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
			height: 7px;
			background: {{ $railColor }};
		}

		.aurora-root .right {
			text-align: right;
		}

		.aurora-root .center {
			text-align: center;
		}

		.aurora-root .muted {
			color: {{ $muted }};
		}

		.aurora-root .header-grid {
			display: grid;
			grid-template-columns:minmax(0, 1.18fr) minmax(240px, 0.82fr);
			column-gap: 24px;
			align-items: start;
			margin-bottom: 44px;
		}

		.aurora-root .brand-block {
			min-width: 0;
		}

		.aurora-root .brand-lockup {
			display: flex;
			align-items: center;
			gap: 10px;
			margin-bottom: 20px;
			flex-wrap: wrap;
		}

		.aurora-root .brand-icon {
			flex: 0 0 auto;
		}

		.aurora-root .logo {
			width: 130px;
			max-width: 100%;
			height: auto;
			object-fit: contain;
			/*border-radius:6px;*/
			display: block;
		}

		.aurora-root .logo-mark {
			display: flex;
			width: 23px;
			height: 23px;
			border-radius: 6px;
			background: {{ $accentSoft }};
			align-items: center;
			justify-content: center;
			flex-direction: column;
			gap: 2px;
			box-sizing: border-box;
		}

		.aurora-root .logo-mark span {
			display: block;
			width: 10px;
			height: 2px;
			border-radius: 2px;
			background: {{ $railColor }};
		}

		.aurora-root .brand-name {
			min-width: 0;
			overflow-wrap: anywhere;
			font-size: 21px;
			line-height: 25px;
			font-weight: 800;
			color: #171923;
		}

		.aurora-root .business-details {
			max-width: 330px;
			font-size: 12px;
			line-height: 19px;
			color: {{ $muted }};
		}

		.aurora-root .invoice-block {
			display: flex;
			flex-direction: column;
			align-items: flex-end;
			min-width: 0;
			text-align: right;
		}

		.aurora-root .invoice-title {
			font-size: 27px;
			line-height: 32px;
			font-weight: 700;
			letter-spacing: 0.22em;
			color: #eceef4;
			margin: 0 0 18px;
		}

		.aurora-root .invoice-meta {
			width: 240px;
			max-width: 100%;
			background: {{ $panelBg }};
			border-radius: 8px;
			padding: 16px 18px;
			box-sizing: border-box;
			text-align: left;
		}

		.aurora-root .meta-row {
			display: flex;
			align-items: flex-start;
			justify-content: space-between;
			gap: 14px;
			padding: 4px 0;
			font-size: 12px;
			line-height: 16px;
		}

		.aurora-root .meta-row span {
			flex: 0 0 auto;
			text-transform: uppercase;
			letter-spacing: 0.09em;
			color: #7b8190;
			font-weight: 700;
		}

		.aurora-root .meta-row strong {
			min-width: 0;
			color: #1f2430;
			font-weight: 800;
			text-align: right;
			overflow-wrap: anywhere;
		}

		.aurora-root .info-grid {
			display: grid;
			grid-template-columns:1fr 1fr;
			column-gap: 54px;
			margin-bottom: 36px;
		}

		.aurora-root .info-cell {
			min-width: 0;
			font-size: 12px;
			line-height: 19px;
			color: #4b5563;
			overflow-wrap: anywhere;
		}

		.aurora-root .section-label {
			display: flex;
			align-items: center;
			gap: 7px;
			margin-bottom: 11px;
			text-transform: uppercase;
			letter-spacing: 0.1em;
			color: {{ $railColor }};
			font-size: 11px;
			line-height: 15px;
			font-weight: 800;
		}

		.aurora-root .label-mark {
			display: inline-block;
			width: 8px;
			height: 8px;
			background: {{ $railColor }};
			border-radius: 2px;
			flex: 0 0 8px;
		}

		.aurora-root .party-name {
			margin-bottom: 3px;
			font-size: 15px;
			line-height: 20px;
			color: #1f2430;
			font-weight: 800;
		}

		.aurora-root .payment-info span,
		.aurora-root .paymentinfo .label {
			color: #4b5563;
			font-weight: 700;
		}

		.aurora-root .paymentinfo {
			list-style: none;
			margin: 0;
			padding: 0;
		}

		.aurora-root .paymentinfo li {
			margin: 0 0 3px;
			padding: 0;
		}

		.aurora-root .items-wrap {
			border: 1px solid{{ $border }};
			border-radius: 8px;
			overflow: hidden;
			margin-bottom: 46px;
		}

		.aurora-root .items-grid {
			display: grid;
			grid-template-columns:minmax(180px, 2.4fr) minmax(52px, 0.55fr) minmax(92px, 0.9fr) minmax(56px, 0.55fr) minmax(94px, 0.9fr);
			align-items: start;
			column-gap: 0;
		}

		.aurora-root .items-wrap.has-line-discount .items-grid {
			grid-template-columns:minmax(150px, 2fr) minmax(46px, 0.45fr) minmax(82px, 0.75fr) minmax(50px, 0.45fr) minmax(74px, 0.65fr) minmax(86px, 0.8fr);
		}

		.aurora-root .items-grid > div {
			min-width: 0;
			box-sizing: border-box;
			padding: 17px 10px;
			overflow-wrap: normal;
			word-break: normal;
		}

		.aurora-root .items-grid > .desc {
			padding-left: 17px;
			padding-right: 17px;
			overflow-wrap: anywhere;
		}

		.aurora-root .items-head {
			background: {{ $tableHead }};
			color: #6d7280;
			border-bottom: 1px solid{{ $border }};
		}

		.aurora-root .items-head > div {
			padding-top: 13px;
			padding-bottom: 13px;
			font-size: 11px;
			line-height: 15px;
			text-transform: uppercase;
			letter-spacing: 0.1em;
			font-weight: 800;
			white-space: nowrap;
		}

		.aurora-root .item-row {
			background: {{ $stripe }};
			border-bottom: 1px solid{{ $border }};
			font-size: 12px;
			line-height: 17px;
			color: #303442;
		}

		.aurora-root .item-row:last-child {
			border-bottom: 0;
		}

		.aurora-root .items-empty {
			padding: 17px;
			font-size: 12px;
			line-height: 17px;
		}

		.aurora-root .item-title,
		.aurora-root .amount {
			color: #202230;
			font-weight: 800;
		}

		.aurora-root .right,
		.aurora-root .center,
		.aurora-root .discount-col,
		.aurora-root .amount {
			white-space: nowrap;
		}

		.aurora-root .discount-col {
			text-align: center;
		}

		.aurora-root .item-description {
			margin-top: 2px;
			color: {{ $muted }};
			font-size: 11px;
			line-height: 15px;
		}

		.aurora-root .totals {
			display: flex;
			justify-content: flex-end;
			margin: 0 0 44px;
		}

		.aurora-root .totals-panel {
			width: 44%;
			min-width: 260px;
			background: {{ $panelBg }};
			border-radius: 8px;
			padding: 18px 20px 20px;
			box-sizing: border-box;
		}

		.aurora-root .totals-row,
		.aurora-root .totals-due {
			display: flex;
			align-items: flex-start;
			justify-content: space-between;
			gap: 14px;
		}

		.aurora-root .totals-row {
			padding: 7px 0;
			font-size: 12px;
			line-height: 17px;
			color: #5c6270;
		}

		.aurora-root .totals-row strong {
			color: #202230;
			font-weight: 800;
			text-align: right;
		}

		.aurora-root .totals-row.discount strong {
			color: #2fb36d;
		}

		.aurora-root .totals-due {
			margin-top: 11px;
			padding-top: 17px;
			border-top: 1px solid{{ $border }};
			font-size: 18px;
			line-height: 23px;
			font-weight: 900;
			color: #171923;
		}

		.aurora-root .totals-due strong {
			color: {{ $railColor }};
			font-size: 21px;
			text-align: right;
		}

		.aurora-root .footer-rule {
			height: 1px;
			background: {{ $border }};
			margin: 0 0 20px;
		}

		.aurora-root .footer-grid {
			display: grid;
			grid-template-columns:1fr 1fr;
			column-gap: 50px;
		}

		.aurora-root .footer-grid section {
			min-width: 0;
		}

		.aurora-root .footer-grid h4 {
			margin: 0 0 8px;
			font-size: 11px;
			line-height: 15px;
			color: #202230;
			text-transform: uppercase;
			letter-spacing: 0.08em;
			font-weight: 900;
		}

		.aurora-root .footer-grid p {
			margin: 0;
			white-space: pre-wrap;
			font-size: 11px;
			line-height: 16px;
			color: #666d7a;
			overflow-wrap: anywhere;
		}

		.aurora-root .watermark {
			margin-top: 28px;
			text-align: center;
			font-size: 12px;
			font-weight: bold;
			color: #6b7280;
		}

		@media print {
			.aurora-root .sheet {
				box-shadow: none;
				border-radius: 0;
				padding: 46px 42px 34px;
			}
		}
	</style>
</div>
