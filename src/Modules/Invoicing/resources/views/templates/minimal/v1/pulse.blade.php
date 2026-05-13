@php
  $accent = data_get($scheme ?? null, 'main.code', '#0b5a47') ?: '#0b5a47';
  $accentDark = data_get($scheme ?? null, 'dark.code', $accent) ?: $accent;
@endphp

<div class="pulse--theme invoice-root pulse-root scheme cat">
  <div class="pulse-sheet">
    <header class="pulse-header">
      <section class="brand-cell">
        <div class="brand-lockup">
          @if($logoSrc)
            <span class="logo-frame"><img src="{{ $logoSrc }}" alt="Business Logo" class="logo" /></span>
          @endif

          <div>
            <div class="brand-name">{{ $businessName }}</div>
            @if($bp?->name !== $businessName)
              <div class="brand-subtitle">{{ $bp->company }}</div>
            @elseif($bp?->website)
              <div class="brand-subtitle">{{ $bp->website }}</div>
            @endif
          </div>
        </div>
      </section>

      <section class="invoice-cell">
        <div class="invoice-title">INVOICE</div>
        <div class="invoice-meta">
          <div><span>Invoice No:</span> <strong>{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</strong></div>
          <div><span>Date:</span> <strong>{{ $fmtDate($invoice->issued_on ?? null) }}</strong></div>
          <div><span>Due Date:</span> <strong>{{ $fmtDate($invoice->due_on ?? null) }}</strong></div>
        </div>
      </section>
    </header>

    <section class="party-grid">
      <div class="party-cell">
        <div class="section-label">Billed To</div>
        <div class="party-name">{{ $clientName }}</div>
        @if($cl?->company && $cl?->name && $cl->company !== $cl->name)<div>Attn: {{ $cl->name }}</div>@endif
        @if($clAddress)<div>{{ $clAddress }}</div>@endif
        @if($cl?->email)<div class="party-spaced">{{ $cl->email }}</div>@endif
        @if($cl?->phone)<div>{{ $cl->phone }}</div>@endif
        @if($cl?->tax_id)<div>Tax ID: {{ $cl->tax_id }}</div>@endif
        @if($cl?->license_no)<div>License No: {{ $cl->license_no }}</div>@endif
      </div>

      <div class="party-cell">
        <div class="section-label">From</div>
        <div class="party-name">{{ $businessName }}</div>
        @if($bpAddress)<div>{{ $bpAddress }}</div>@endif
        @if($bp?->email)<div class="party-spaced">{{ $bp->email }}</div>@endif
        @if($bp?->phone)<div>{{ $bp->phone }}</div>@endif
        @if($bp?->tax_id)<div>Tax ID: {{ $bp->tax_id }}</div>@endif
        @if($bp?->license_no)<div>License No: {{ $bp->license_no }}</div>@endif
      </div>
    </section>

    <section class="items-table{{ $hasLineDiscount ? ' has-line-discount' : '' }}">
      <div class="item-row item-head">
        <div class="desc-col">Description</div>
        <div class="qty-col">Qty</div>
        <div class="unit-price-col">Unit Price</div>
        <div class="tax-col">Tax</div>
        @if($hasLineDiscount)<div class="discount-col">Discount</div>@endif
        <div class="amount-col">Amount</div>
      </div>

      @forelse($items as $it)
        <div class="item-row">
          <div class="desc-col">
            <div class="item-title">{{ $it->name ?? 'Item' }}</div>
            @if(!empty($it->description))<div class="item-description">{{ $it->description }}</div>@endif
          </div>
          <div class="qty-col">{{ $fmtQuantity($it) }}</div>
          <div class="unit-price-col">{{ $fmtItemUnitPrice($it) }}</div>
          <div class="tax-col">{{ $fmtItemTaxRate($it) }}</div>
          @if($hasLineDiscount)<div class="discount-col">{{ $fmtItemLineDiscount($it) }}</div>@endif
          <div class="amount-col item-amount">{{ $fmtItemLineTotal($it) }}</div>
        </div>
      @empty
        <div class="empty-cell">No items.</div>
      @endforelse
    </section>

    <section class="payment-summary">
      <div class="payment-cell">
        <div class="section-label">Payment Information</div>
        <div class="payment-details">
          @if($hasBankTransferDetails)
              <div class="payment-group">
                <div class="payment-heading"><span class="payment-mark"></span>Bank Transfer</div>
                @foreach($bankTransferDetails as $label => $value)
                  <div>{{ $label }}: {{ $value }}</div>
                @endforeach
              </div>
          @else
            <div class="muted-line">&mdash;</div>
          @endif
        </div>
      </div>

      <div class="summary-cell">
        <div class="summary-box">
          <div class="summary-row">
            <span>Subtotal</span>
            <strong>{{ $fmtMoney($subtotalCents, $currency) }}</strong>
          </div>
          <div class="summary-row">
            <span>{{ $discountLabel }}</span>
            <strong class="discount-value">-{{ $fmtMoney($discountCents, $currency) }}</strong>
          </div>
          <div class="summary-row">
            <span>{{ $taxLabel }}</span>
            <strong>{{ $fmtMoney($taxCents, $currency) }}</strong>
          </div>
          @if($hasShipping)
            <div class="summary-row">
              <span>Shipping</span>
              <strong>{{ $fmtMoney($shippingCents, $currency) }}</strong>
            </div>
          @endif
          <div class="summary-row total-row">
            <span>Total Due</span>
            <strong>{{ $fmtMoney($totalDue, $currency) }}</strong>
          </div>
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
  .pulse-root,
  .pulse-root * {
    box-sizing: border-box;
  }

  .pulse-root {
    width: 100%;
    color: #1f2426;
    font-family: {{ $fontFamily }};
    font-size: 10px;
    line-height: 1.42;
    background: #ffffff;
  }

  .pulse-root .pulse-sheet {
    position: relative;
    width: 100%;
    max-width: 100%;
    min-height: 100%;
    padding: 64px 52px 0;
    background: #ffffff;
    border: 1px solid #e1e5e3;
    overflow: hidden;
  }

  .pulse-root .pulse-sheet:after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    height: 7px;
    background: {{ $accentDark }};
  }

  .pulse-root .pulse-header {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 245px;
    gap: 30px;
    align-items: start;
    min-height: 132px;
    padding-left: 0;
  }

  .pulse-root .brand-lockup {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 1px;
  }

  .pulse-root .logo-frame,
  .pulse-root .logo-placeholder {
    display: block;
    flex: 0 0 36px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
  }

  .pulse-root .logo {
    display: block;
    width: 36px;
    height: 36px;
    object-fit: contain;
    /*border-radius: 50%;*/
  }

  .pulse-root .logo-placeholder {
    color: #ffffff;
    font-size: 16px;
    line-height: 36px;
    text-align: center;
    font-weight: 800;
  }

  .pulse-root .brand-name {
    color: {{ $accentDark }};
    font-family: Georgia, "Times New Roman", serif;
    font-size: 24px;
    line-height: 25px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
  }

  .pulse-root .brand-subtitle {
    margin-top: 2px;
    color: #7d8583;
    font-size: 8px;
    line-height: 10px;
    font-weight: 700;
    letter-spacing: 0.24em;
    text-transform: uppercase;
  }

  .pulse-root .invoice-cell {
    text-align: right;
  }

  .pulse-root .invoice-title {
    margin: 0 0 7px;
    color: {{ $accentDark }};
    font-family: Georgia, "Times New Roman", serif;
    font-size: 30px;
    line-height: 33px;
    font-weight: 500;
    letter-spacing: 0.07em;
  }

  .pulse-root .invoice-meta {
    color: #1f2426;
    font-size: 10px;
    line-height: 17px;
  }

  .pulse-root .invoice-meta span {
    font-weight: 700;
  }

  .pulse-root .invoice-meta strong {
    font-weight: 400;
  }

  .pulse-root .party-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    gap: 72px;
    margin: 0 -52px;
    padding: 35px 52px 39px;
    border-top: 1px solid #dfe4e2;
  }

  .pulse-root .party-cell {
    color: #353b3d;
    font-size: 10px;
    line-height: 17px;
  }

  .pulse-root .section-label,
  .pulse-root .footer-label {
    margin: 0 0 24px;
    color: {{ $accentDark }};
    font-family: Georgia, "Times New Roman", serif;
    font-size: 11px;
    line-height: 13px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
  }

  .pulse-root .party-name {
    margin-bottom: 2px;
    color: #1f2426;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 15px;
    line-height: 18px;
    font-weight: 500;
  }

  .pulse-root .party-spaced {
    margin-top: 9px;
  }

  .pulse-root .items-table {
    display: grid;
    width: 100%;
    margin: 5px 0 34px;
  }

  .pulse-root .item-row {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 72px 94px 54px 102px;
    align-items: start;
    border-bottom: 1px solid #e4e8e6;
  }

  .pulse-root .items-table.has-line-discount .item-row {
    grid-template-columns: minmax(0, 1fr) 64px 84px 52px 76px 96px;
  }

  .pulse-root .item-head {
    color: {{ $accentDark }};
    border-bottom: 1px solid #253330;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 10px;
    line-height: 12px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
  }

  .pulse-root .item-row > div {
    min-width: 0;
    padding: 20px 6px 22px;
    color: #4b5355;
    font-size: 10px;
    line-height: 13px;
    overflow-wrap: normal;
    word-break: normal;
  }

  .pulse-root .item-head > div {
    padding: 0 6px 15px;
    white-space: nowrap;
  }

  .pulse-root .desc-col {
    padding-left: 7px !important;
    padding-right: 20px !important;
    text-align: left;
    overflow-wrap: anywhere !important;
  }

  .pulse-root .qty-col,
  .pulse-root .tax-col,
  .pulse-root .discount-col {
    text-align: center;
    white-space: nowrap;
  }

  .pulse-root .unit-price-col {
    text-align: right;
    white-space: nowrap;
  }

  .pulse-root .amount-col {
    padding-right: 5px !important;
    text-align: right;
    white-space: nowrap;
  }

  .pulse-root .item-title {
    margin-bottom: 4px;
    color: #161c1e;
    font-size: 11px;
    line-height: 13px;
    font-weight: 800;
  }

  .pulse-root .item-description {
    color: #70787a;
    font-size: 9px;
    line-height: 12px;
  }

  .pulse-root .item-amount {
    color: #161c1e !important;
    font-weight: 800;
  }

  .pulse-root .empty-cell {
    padding: 20px 0 22px 7px;
    border-bottom: 1px solid #e4e8e6;
    color: #70787a;
    font-size: 10px;
  }

  .pulse-root .payment-summary {
    display: grid;
    grid-template-columns: minmax(0, 53%) minmax(0, 47%);
    gap: 28px;
    margin: 0 -52px;
    padding: 41px 52px 39px;
    background: #f6f9f8;
  }

  .pulse-root .payment-cell .section-label {
    margin-bottom: 24px;
  }

  .pulse-root .payment-details {
    color: #293133;
    font-size: 10px;
    line-height: 15px;
  }

  .pulse-root .payment-group {
    margin: 0 0 22px;
  }

  .pulse-root .payment-heading {
    margin-bottom: 4px;
    color: #1d2527;
    font-weight: 800;
  }

  .pulse-root .payment-mark {
    display: inline-block;
    width: 8px;
    height: 8px;
    margin-right: 7px;
    background: {{ $accentDark }};
    vertical-align: 0;
  }

  .pulse-root .pulse-payment-list {
    margin: 0 0 18px;
    padding: 0;
    list-style: none;
  }

  .pulse-root .pulse-payment-list li {
    margin: 0 0 6px;
  }

  .pulse-root .pulse-payment-list .label {
    color: #1d2527;
    font-weight: 800;
  }

  .pulse-root .pulse-payment-list .value {
    color: #293133;
    font-weight: 400;
  }

  .pulse-root .payment-note {
    margin: 0 0 16px;
    color: #596163;
  }

  .pulse-root .summary-cell {
    display: flex;
    justify-content: flex-end;
  }

  .pulse-root .summary-box {
    width: 282px;
    min-height: 178px;
    padding: 23px 20px 19px;
    background: #ffffff;
    border: 1px solid #e0e4e2;
  }

  .pulse-root .summary-row {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 22px;
    color: #3f484a;
    font-size: 10px;
    line-height: 20px;
  }

  .pulse-root .summary-row strong {
    color: #161c1e;
    font-weight: 800;
    text-align: right;
  }

  .pulse-root .summary-row .discount-value {
    color: #b94b44;
  }

  .pulse-root .summary-row.total-row {
    margin-top: 9px;
    padding-top: 16px;
    border-top: 1px solid #253330;
    color: #1d2527;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 15px;
    line-height: 23px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
  }

  .pulse-root .summary-row.total-row strong {
    color: {{ $accentDark }};
    font-size: 22px;
    line-height: 23px;
    letter-spacing: 0.03em;
    text-transform: none;
  }

  .pulse-root .footer-band {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    gap: 54px;
    margin: 0 -52px;
    padding: 30px 52px 41px;
    border-top: 1px solid #dfe4e2;
    background: #ffffff;
  }

  .pulse-root .footer-label {
    margin-bottom: 12px;
    font-family: {{ $fontFamily }};
    font-size: 9px;
    line-height: 11px;
    letter-spacing: 0.08em;
  }

  .pulse-root .footer-copy {
    color: #586062;
    font-size: 9px;
    line-height: 14px;
  }

  .pulse-root .muted-line {
    color: #8d9492;
  }

  .pulse-root .watermark {
    margin: 18px 0 0;
    color: #8d9492;
    font-size: 9px;
    text-align: center;
  }
</style>
