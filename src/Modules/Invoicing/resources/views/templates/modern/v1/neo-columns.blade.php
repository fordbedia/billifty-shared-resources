@php
  $fontFamily = data_get($theme ?? null, 'fontFamily', 'DejaVu Sans');
  $accent = data_get($scheme ?? null, 'main.code', '#ff3108');
  $accentSoft = data_get($scheme ?? null, 'light.code', '#fff5ef');
  $currency = $invoice->currency ?? 'USD';
  $bpAddress = $bp ? $addr($bp) : null;
  $clAddress = $cl ? $addr($cl) : null;
  $itemsCount = $items instanceof \Illuminate\Support\Collection ? $items->count() : count($items);
  $firstItem = $items instanceof \Illuminate\Support\Collection ? $items->first() : (is_array($items) ? reset($items) : null);
  $projectTitle = $invoice->reference ?: (data_get($firstItem, 'name') ?? 'Project Detail');
  $projectDetail = $invoice->notes ?: ($invoice->terms ?: null);
  $totalDue = $invoice->amount_due_cents ?? $invoice->total_cents ?? 0;
  $hasLineDiscount = ($invoice->discount_mode ?? null) === 'per-line';
  $logoInitial = strtoupper(substr(trim((string)($bp?->name ?? 'G')), 0, 1));
  $statusRaw = $invoice->status instanceof \BackedEnum ? $invoice->status->value : ($invoice->status ?? 'issued');
  $statusLabel = match ($statusRaw) {
    'issued', 'sent' => 'Pending Payment',
    'partially' => 'Partially Paid',
    default => ucwords(str_replace('_', ' ', (string) $statusRaw)),
  };
@endphp

<div class="neo--theme invoice-root neo-root scheme cat">
  <div class="neo-sheet">
    <div class="accent-corner"></div>

    <header class="neo-header">
      <section class="brand-cell" aria-label="Business profile">
        <div class="brand-lockup">
			@if($logoSrc)
			  <div class="brand-mark-cell">
				  <img src="{{ $logoSrc }}" alt="Business Logo" class="logo" />
			  </div>
			@endif
          <div class="brand-copy">
            <div class="brand-name">{{ $bp?->name ?? 'Your Business' }}</div>
          </div>
        </div>

        <div class="business-lines">
          @if($bpAddress)<div>{{ $bpAddress }}</div>@endif
          @if($bp?->email)<div>{{ $bp->email }}</div>@endif
          @if($bp?->phone)<div>{{ $bp->phone }}</div>@endif
          @if($bp?->website)<div>{{ $bp->website }}</div>@endif
        </div>
      </section>

      <section class="invoice-cell" aria-label="Invoice details">
        <div class="invoice-title">INVOICE</div>
        <div class="invoice-meta">
          <div class="invoice-meta-row">
            <span>Invoice No</span>
            <strong>{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</strong>
          </div>
          <div class="invoice-meta-row">
            <span>Date</span>
            <strong>{{ $fmtDate($invoice->issued_on ?? null) }}</strong>
          </div>
          <div class="invoice-meta-row">
            <span>Due Date</span>
            <strong class="due-date">{{ $fmtDate($invoice->due_on ?? null) }}</strong>
          </div>
        </div>
      </section>
    </header>

    <div class="header-rule"></div>

    <section class="party-band" aria-label="Billing and project details">
      <div class="party-grid">
        <div class="bill-card">
          <div class="section-kicker">+ Bill To</div>
          <div class="client-name">{{ $cl?->company ?? $cl?->name ?? 'Client' }}</div>
          @if($cl?->company && $cl?->name && $cl->company !== $cl->name)<div>Attn: {{ $cl->name }}</div>@endif
          @if($clAddress)<div>{{ $clAddress }}</div>@endif
          @if($cl?->email)<div>{{ $cl->email }}</div>@endif
          @if($cl?->phone)<div>{{ $cl->phone }}</div>@endif
          @if($cl?->tax_id)<div>Tax ID: {{ $cl->tax_id }}</div>@endif
          @if($cl?->license_no)<div>License No: {{ $cl->license_no }}</div>@endif
        </div>

        <div class="project-card">
          <div class="section-kicker">Project Detail</div>
          <div class="project-title">{{ $projectTitle }}</div>
          <div class="project-copy">@if($projectDetail){!! nl2br(e($projectDetail)) !!}@else &mdash; @endif</div>
          <div class="status-pill">Status: {{ $statusLabel }}</div>
        </div>
      </div>
    </section>

    <section class="items-section" aria-label="Invoice items">
      <div class="items-grid{{ $hasLineDiscount ? ' has-line-discount' : '' }}">
        <div class="items-header">
          <div class="item-no">Item</div>
          <div class="desc">Description</div>
          <div class="center">Qty</div>
          <div class="amount-col">Rate</div>
          @if($hasLineDiscount)<div class="discount-col">Discount</div>@endif
          <div class="amount-col">Amount</div>
        </div>

        <div class="items-body">
          @if($itemsCount === 0)
            <div class="items-empty">No items.</div>
          @else
            @foreach($items as $it)
              <div class="items-row">
                <div class="item-no">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="desc">
                  <div class="item-title">{{ $it->name ?? 'Item' }}</div>
                  @if(!empty($it->description))<div class="item-description">{{ $it->description }}</div>@endif
                  @if($hasLineDiscount)<div class="item-description">Discount: {{ $fmtPercent($it->line_discount_rate) }}</div>@endif
                </div>
                <div class="center">{{ rtrim(rtrim((string)($it->quantity ?? 0), '0'), '.') }}{{ $it->unit ? ' '.$it->unit : '' }}</div>
                <div class="amount-col">{{ $fmtMoney($it->unit_price_cents ?? 0, $currency) }}</div>
                @if($hasLineDiscount)<div class="discount-col">{{ $fmtPercent($it->line_discount_rate) }}</div>@endif
                <div class="amount-col strong">{{ $fmtMoney($it->line_total_cents ?? 0, $currency) }}</div>
              </div>
            @endforeach
          @endif
        </div>
      </div>
    </section>

    <section class="lower-layout" aria-label="Payment and total">
      <div class="lower-info">
        <div class="payment-block">
          <div class="lower-kicker">Payment Method</div>
          @if($pi->payment_method)
            <div class="payment-box">{!! $paymentInfo($pi, 'neo-payment-list') !!}</div>
          @else
            <div class="muted-line">&mdash;</div>
          @endif
        </div>

        <div class="terms-cell">
          <div class="lower-kicker">Terms &amp; Conditions</div>
          <div class="terms-copy">@if($invoice->terms){{ $invoice->terms }}@else &mdash; @endif</div>
        </div>
      </div>

      <aside class="total-panel-cell" aria-label="Invoice total">
        <div class="total-panel">
          <div class="summary-row">
            <span>Subtotal</span>
            <strong>{{ $fmtMoney($invoice->subtotal_cents ?? 0, $currency) }}</strong>
          </div>
          <div class="summary-row">
            <span>Tax</span>
            <strong>{{ $fmtMoney($invoice->tax_cents ?? 0, $currency) }}</strong>
          </div>
          <div class="summary-row">
            <span>Discount</span>
            <strong>-{{ $fmtMoney($invoice->discount_cents ?? 0, $currency) }}</strong>
          </div>
          @if((int)($invoice->shipping_cents ?? 0) > 0)
            <div class="summary-row">
              <span>Shipping</span>
              <strong>{{ $fmtMoney($invoice->shipping_cents ?? 0, $currency) }}</strong>
            </div>
          @endif
          <div class="total-divider"></div>
          <div class="total-label">Total Amount Due</div>
          <div class="total-amount">{{ $fmtMoney($totalDue, $currency) }}</div>
          <div class="currency-pill">{{ is_object($currency) ? $currency->code : $currency }}</div>
        </div>
      </aside>
    </section>

	@if ($watermark())  
		<footer class="neo-footer">
		  <div class="footer-brand"{{$watermark()}}</div>
		</footer>
	@endif


  </div>

  <style>
    body {
      margin: 0;
      background: #ffffff;
      font-family: "{{ $fontFamily }}", "DejaVu Sans", Arial, sans-serif;
      color: #111111;
      font-size: 12px;
    }

    .neo-root,
    .neo-root * {
      box-sizing: border-box;
    }

    .neo-root {
      --accent: {{ $accent }};
      --accent-soft: {{ $accentSoft }};
      --ink: #111111;
      --muted: #636363;
      --line: #161616;
      --paper: #ffffff;
      font-family: "{{ $fontFamily }}", "DejaVu Sans", Arial, sans-serif;
      color: #111111;
      background: #ffffff;
    }

    .neo-sheet {
      position: relative;
      width: 100%;
      min-height: 100%;
      background: #ffffff;
      overflow: hidden;
    }

    .accent-corner {
      position: absolute;
      top: 0;
      right: 0;
      width: 0;
      height: 0;
      border-top: 92px solid {{ $accent }};
      border-left: 108px solid transparent;
      z-index: 1;
    }

    .neo-header {
      position: relative;
      z-index: 2;
      display: grid;
      grid-template-columns: 48% 52%;
    }

    .brand-cell {
      padding: 48px 46px 36px 46px;
    }

    .invoice-cell {
      padding: 44px 46px 24px 24px;
      text-align: right;
    }

    .brand-lockup {
      display: flex;
      align-items: center;
      gap: 10px;
      width: max-content;
      max-width: 100%;
    }

    .brand-mark-cell {
      flex: 0 0 34px;
      width: 34px;
    }

    .logo,
    .logo-placeholder {
      display: block;
      width: 34px;
      height: 34px;
    }

    .logo {
      object-fit: contain;
      background: #ffffff;
    }

    .logo-placeholder {
      position: relative;
      background: #111111;
      color: #ffffff;
      line-height: 34px;
      text-align: center;
      font-size: 11px;
      font-weight: 800;
    }

    .logo-placeholder::after {
      content: "";
      position: absolute;
      top: 0;
      right: 0;
      width: 7px;
      height: 34px;
      background: {{ $accent }};
    }

    .brand-copy {
      min-width: 0;
    }

    .brand-name {
      font-size: 25px;
      line-height: 30px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: 0;
      overflow-wrap: anywhere;
    }

    .business-lines {
      margin-top: 16px;
      max-width: 270px;
      color: #2a2a2a;
      font-size: 10px;
      line-height: 14px;
      font-weight: 600;
    }

    .invoice-title {
      font-size: 50px;
      line-height: 54px;
      font-weight: 900;
      letter-spacing: 1px;
      margin-bottom: 16px;
    }

    .invoice-meta {
      display: grid;
      gap: 0;
      width: 210px;
      margin-left: auto;
      color: #111111;
    }

    .invoice-meta-row {
      display: grid;
      grid-template-columns: 86px minmax(0, 1fr);
      align-items: start;
      gap: 8px;
      padding: 5px 0;
      font-size: 10px;
      line-height: 14px;
      font-weight: 800;
      text-transform: uppercase;
    }

    .invoice-meta-row span {
      color: #222222;
      letter-spacing: .04em;
      text-align: left;
    }

    .invoice-meta-row strong {
      text-align: right;
      font-size: 13px;
      letter-spacing: 0;
      text-transform: none;
      overflow-wrap: anywhere;
    }

    .invoice-meta-row .due-date {
      color: {{ $accent }};
    }

    .header-rule {
      height: 5px;
      margin: 0;
      background: #111111;
    }

    .party-band {
      padding: 34px 48px 38px 48px;
      background-color: #fffaf6;
      background-image: radial-gradient(#f3e4dc 1px, transparent 1px);
      background-size: 8px 8px;
    }

    .party-grid {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
      gap: 56px;
      align-items: stretch;
    }

    .bill-card {
      min-height: 174px;
      padding: 31px 34px 28px 34px;
      background: #ffffff;
      color: #151515;
      box-shadow: 0 8px 24px rgba(17, 17, 17, .04);
      font-size: 11px;
      line-height: 17px;
      font-weight: 600;
    }

    .section-kicker {
      margin-bottom: 14px;
      color: {{ $accent }};
      font-size: 10px;
      line-height: 12px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .12em;
    }

    .client-name {
      margin-bottom: 8px;
      color: #111111;
      font-size: 17px;
      line-height: 22px;
      font-weight: 900;
      text-transform: uppercase;
      overflow-wrap: anywhere;
    }

    .project-card {
      min-height: 174px;
      padding: 27px 34px 24px 34px;
      background: #111111;
      color: #ffffff;
      font-size: 11px;
      line-height: 17px;
    }

    .project-card .section-kicker {
      color: #9b9b9b;
      margin-bottom: 14px;
    }

    .project-card .section-kicker::before {
      content: "";
      display: inline-block;
      width: 10px;
      height: 7px;
      margin-right: 7px;
      border-top: 2px solid #777777;
      border-bottom: 2px solid #777777;
    }

    .project-title {
      margin-bottom: 9px;
      color: #ffffff;
      font-size: 16px;
      line-height: 22px;
      font-weight: 900;
      overflow-wrap: anywhere;
    }

    .project-copy {
      max-width: 320px;
      color: #c5c5c5;
      font-size: 11px;
      line-height: 17px;
      font-weight: 500;
      overflow-wrap: anywhere;
    }

    .status-pill {
      display: inline-block;
      margin-top: 18px;
      padding: 7px 15px;
      background: {{ $accent }};
      color: #ffffff;
      font-size: 9px;
      line-height: 11px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .08em;
    }

    .items-section {
      padding: 46px 48px 18px 48px;
      background: #ffffff;
    }

    .items-grid {
      width: 100%;
      color: #111111;
    }

    .items-header,
    .items-row {
      display: grid;
      grid-template-columns: 54px minmax(0, 1fr) 72px 122px 122px;
      column-gap: 0;
    }

    .items-grid.has-line-discount .items-header,
    .items-grid.has-line-discount .items-row {
      grid-template-columns: 46px minmax(0, 1fr) 58px 96px 84px 102px;
    }

    .items-header {
      align-items: end;
      border-bottom: 3px solid #111111;
      color: #111111;
      font-size: 10px;
      line-height: 12px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .12em;
    }

    .items-header > div {
      padding: 0 0 18px 0;
    }

    .items-row {
      align-items: start;
      border-bottom: 1px solid #111111;
      font-size: 11px;
      line-height: 15px;
      font-weight: 700;
    }

    .items-row > div {
      padding: 20px 0 18px 0;
    }

    .items-grid .item-no {
      color: {{ $accent }};
      font-weight: 900;
      text-align: left;
    }

    .items-grid .desc {
      padding-right: 22px;
      min-width: 0;
    }

    .items-grid .center {
      text-align: center;
    }

    .items-grid .amount-col {
      text-align: right;
      white-space: nowrap;
    }

    .items-grid .discount-col {
      text-align: right;
      white-space: nowrap;
    }

    .item-title {
      color: #111111;
      font-size: 14px;
      line-height: 18px;
      font-weight: 900;
      overflow-wrap: anywhere;
    }

    .item-description {
      margin-top: 4px;
      color: #555555;
      font-size: 10px;
      line-height: 15px;
      font-weight: 600;
      overflow-wrap: anywhere;
    }

    .strong {
      font-weight: 900;
    }

    .items-empty {
      padding: 20px 0 18px 0;
      border-bottom: 1px solid #111111;
      color: #777777;
      font-size: 11px;
      line-height: 15px;
      font-weight: 700;
      text-align: center;
    }

    .lower-layout {
      display: grid;
      grid-template-columns: 61% 39%;
      align-items: start;
      margin-top: 8px;
    }

    .lower-info {
      padding: 72px 28px 0 48px;
    }

    .total-panel-cell {
      padding: 10px 48px 0 0;
    }

    .payment-block {
      max-width: 430px;
    }

    .lower-kicker {
      margin-bottom: 12px;
      color: #111111;
      font-size: 10px;
      line-height: 12px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .16em;
    }

    .payment-box {
      color: #111111;
      font-size: 10px;
      line-height: 15px;
      font-weight: 700;
    }

    .neo-payment-list {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 6px 36px;
      margin: 0;
      padding: 0;
      list-style: none;
    }

    .neo-payment-list li {
      display: block;
      margin: 0;
      padding: 0;
      min-width: 0;
    }

    .neo-payment-list .label {
      display: block;
      color: #111111;
      font-size: 9px;
      line-height: 12px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .05em;
    }

    .neo-payment-list .value {
      display: block;
      color: #111111;
      font-size: 10px;
      line-height: 14px;
      font-weight: 700;
      word-break: break-word;
    }

    .terms-cell {
      max-width: 430px;
      padding-top: 28px;
    }

    .terms-copy,
    .muted-line {
      color: #111111;
      font-size: 10px;
      line-height: 16px;
      font-weight: 600;
      white-space: pre-wrap;
      overflow-wrap: anywhere;
    }

    .total-panel {
      position: relative;
      display: flex;
      min-height: 314px;
      padding: 42px 35px 28px 35px;
      background: {{ $accent }};
      color: #ffffff;
      overflow: hidden;
      flex-direction: column;
    }

    .total-panel::after {
      content: "";
      position: absolute;
      right: -34px;
      bottom: -54px;
      width: 176px;
      height: 176px;
      background: rgba(255, 255, 255, .08);
      transform: rotate(45deg);
    }

    .summary-row {
      position: relative;
      z-index: 2;
      display: flex;
      justify-content: space-between;
      gap: 20px;
      padding: 0 0 22px 0;
      color: #ffffff;
      font-size: 11px;
      line-height: 14px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .06em;
    }

    .summary-row span {
      opacity: .84;
    }

    .summary-row strong {
      min-width: 0;
      font-size: 12px;
      font-weight: 900;
      letter-spacing: 0;
      text-transform: none;
      text-align: right;
      overflow-wrap: anywhere;
    }

    .total-divider {
      position: relative;
      z-index: 2;
      height: 3px;
      margin: 0 0 20px 0;
      background: #ffffff;
      flex: 0 0 auto;
    }

    .total-label {
      position: relative;
      z-index: 2;
      margin-bottom: 7px;
      color: #ffffff;
      font-size: 10px;
      line-height: 12px;
      font-weight: 900;
      text-transform: uppercase;
      text-align: right;
      letter-spacing: .11em;
    }

    .total-amount {
      position: relative;
      z-index: 2;
      color: #ffffff;
      font-size: 25px;
      line-height: 46px;
      font-weight: 900;
      text-align: right;
      letter-spacing: 0;
      overflow-wrap: anywhere;
    }

    .currency-pill {
      position: relative;
      z-index: 2;
      align-self: flex-end;
      margin-top: 7px;
      padding: 4px 9px;
      background: #ffffff;
      color: {{ $accent }};
      font-size: 9px;
      line-height: 11px;
      font-weight: 900;
      text-transform: uppercase;
    }

    .neo-footer {
      display: flex;
      justify-content: space-between;
      gap: 24px;
      margin-top: 0;
      padding: 14px 48px;
      background: #111111;
      color: #ffffff;
      font-size: 9px;
      line-height: 11px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .16em;
    }

    .footer-brand {
      color: #777777;
      text-align: right;
      overflow-wrap: anywhere;
    }

    .watermark {
      margin: 14px 48px 0 48px;
      color: #777777;
      font-size: 10px;
      text-align: center;
    }

    @media print {
      .neo-sheet {
        overflow: hidden;
      }
    }
  </style>
</div>
