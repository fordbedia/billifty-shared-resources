@php
  $ink        = '#182333';
  $muted      = '#707985';
  $faint      = '#a0a7b1';
  $paper      = '#ffffff';
  $border     = '#e4e7eb';
  $rule       = '#eef0f3';
  $accent     = $scheme->main->code;
@endphp

<div class="simplifi--theme simplifi-root scheme cat">
  <div class="simplifi-sheet">
    <header class="invoice-header">
      <section class="brand-cell" aria-label="Business">
        @if($logoSrc)
          <img src="{{ $logoSrc }}" class="logo" alt="logo" />
        @endif

        <div class="business-name">{{ $businessName }}</div>
        <div class="business-lines">
          @if($bpAddress)<div>{{ $bpAddress }}</div>@endif
          @if($bp?->email)<div>{{ $bp->email }}</div>@endif
          @if($bp?->phone)<div>{{ $bp->phone }}</div>@endif
          @if($bp?->website)<div>{{ $bp->website }}</div>@endif
          @if($bp?->tax_id)<div>Tax ID: {{ $bp->tax_id }}</div>@endif
          @if($bp?->license_no)<div>License No: {{ $bp->license_no }}</div>@endif
        </div>
      </section>

      <section class="invoice-cell" aria-label="Invoice details">
        <div class="invoice-title">INVOICE</div>
        <dl class="invoice-meta">
          <div>
            <dt>Invoice No:</dt>
            <dd>{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</dd>
          </div>
          <div>
            <dt>Date:</dt>
            <dd>{{ $fmtDate($invoice->issued_on ?? null) }}</dd>
          </div>
          <div>
            <dt>Due Date:</dt>
            <dd>{{ $fmtDate($invoice->due_on ?? null) }}</dd>
          </div>
        </dl>
      </section>
    </header>

    <section class="bill-to" aria-label="Bill to">
      <div class="section-label">Bill To</div>
      <div class="client-name">{{ $clientName }}</div>
      @if($cl?->company && $cl?->name && $cl->company !== $cl->name)<div>Attn: {{ $cl->name }}</div>@endif
      @if($clAddress)<div>{{ $clAddress }}</div>@endif
      @if($cl?->email)<div>{{ $cl->email }}</div>@endif
      @if($cl?->phone)<div>{{ $cl->phone }}</div>@endif
      @if($cl?->tax_id)<div>Tax ID: {{ $cl->tax_id }}</div>@endif
      @if($cl?->license_no)<div>License No: {{ $cl->license_no }}</div>@endif
    </section>

    <section class="items-list{{ $hasLineDiscount ? ' has-line-discount' : '' }}" aria-label="Invoice items">
      <div class="items-head">
        <div class="desc">Description</div>
        <div class="qty">Qty</div>
        <div class="money">Unit Price</div>
        <div class="tax-col">Tax</div>
        @if($hasLineDiscount)<div class="discount-col">Discount</div>@endif
        <div class="money">Amount</div>
      </div>

      @if($itemCount === 0)
        <div class="empty-cell">No items.</div>
      @else
        @foreach($items as $it)
          <article class="item-row">
            <div class="desc">
              <div class="item-title">{{ $it->name ?? 'Item' }}</div>
              @if(!empty($it->description))<div class="item-description">{{ $it->description }}</div>@endif
            </div>
            <div class="qty">{{ $fmtQuantity($it) }}</div>
            <div class="money">{{ $fmtItemUnitPrice($it) }}</div>
            <div class="tax-col">{{ $fmtItemTaxRate($it) }}</div>
            @if($hasLineDiscount)<div class="discount-col">{{ $fmtItemLineDiscount($it) }}</div>@endif
            <div class="money item-amount">{{ $fmtItemLineTotal($it) }}</div>
          </article>
        @endforeach
      @endif
    </section>

    <div class="totals-wrap">
      <div class="totals-panel">
        <div class="total-row">
          <span>Subtotal</span>
          <strong>{{ $fmtMoney($subtotalCents, $currency) }}</strong>
        </div>
        @if($hasDiscount)
          <div class="total-row">
            <span>Discount</span>
            <strong>-{{ $fmtMoney($discountCents, $currency) }}</strong>
          </div>
        @endif
        <div class="total-row">
          <span>{{ $taxLabel }}</span>
          <strong>{{ $fmtMoney($taxCents, $currency) }}</strong>
        </div>
        @if($hasShipping)
          <div class="total-row">
            <span>Shipping</span>
            <strong>{{ $fmtMoney($shippingCents, $currency) }}</strong>
          </div>
        @endif
        <div class="grand-total">
          <span>Total</span>
          <strong>{{ $fmtMoney($totalDue, $currency) }}</strong>
        </div>
        @include('invoicing::templates.paid-stamp')
      </div>
    </div>

    <div class="footer-rule"></div>

    <footer class="footer-grid">
      <section class="payment-cell">
        <div class="section-label">Payment Information</div>

        @if($hasBankTransferDetails)
          <div class="payment-lines">
            @foreach($bankTransferDetails as $label => $value)
              <div><span>{{ $label }}:</span> {{ $value }}</div>
            @endforeach
          </div>
        @else
          <div class="muted">&mdash;</div>
        @endif
      </section>

      <section class="notes-cell">
        <div class="section-label">Notes &amp; Terms</div>
        @if($invoice->notes)<p>{!! nl2br(e($invoice->notes)) !!}</p>@endif
        @if($invoice->terms)<p>{!! nl2br(e($invoice->terms)) !!}</p>@endif
        @if(!$invoice->notes && !$invoice->terms)<p>&mdash;</p>@endif
      </section>

      @include('invoicing::templates.payment-method', ['invoice' => $invoice, 'pi' => $pi])
    </footer>

    {!! $watermark() !!}
  </div>

  <style>
    .simplifi-root,
    .simplifi-root *{
      box-sizing:border-box;
    }
    .simplifi-root{
      width:100%;
      color:{{ $ink }};
      font-family:{{ $fontFamily }};
      font-size:11px;
      line-height:1.45;
    }
    .simplifi-root .simplifi-sheet{
      width:100%;
      max-width:100%;
      min-height:100%;
      padding:48px 56px 38px;
      background:{{ $paper }};
      border-radius:2px;
      box-shadow:0 8px 24px rgba(24,35,51,0.09);
      overflow:hidden;
    }
    .simplifi-root .muted{
      color:{{ $muted }};
    }

    .simplifi-root .invoice-header{
      display:grid;
      grid-template-columns:minmax(0, 1.35fr) minmax(220px, .9fr);
      column-gap:28px;
      align-items:start;
      margin-bottom:58px;
    }
    .simplifi-root .brand-cell{
      min-width:0;
    }
    .simplifi-root .invoice-cell{
      text-align:right;
    }
    .simplifi-root .logo{
      display:block;
      width:28px;
      max-height:28px;
      object-fit:contain;
      border-radius:0;
      margin-bottom:18px;
    }
    .simplifi-root .logo-placeholder{
      display:block;
      width:28px;
      height:28px;
      margin-bottom:18px;
      background:{{ $accent }};
      color:#ffffff;
      font-size:12px;
      line-height:28px;
      text-align:center;
      font-weight:800;
    }
    .simplifi-root .business-name{
      margin:0 0 5px;
      color:{{ $ink }};
      font-size:17px;
      line-height:22px;
      font-weight:800;
    }
    .simplifi-root .business-lines{
      max-width:250px;
      color:{{ $muted }};
      font-size:10px;
      line-height:16px;
    }
    .simplifi-root .invoice-title{
      margin:0 0 16px;
      color:{{ $ink }};
      font-size:27px;
      line-height:32px;
      font-weight:900;
      letter-spacing:0;
      text-transform:uppercase;
    }
    .simplifi-root .invoice-meta{
      display:grid;
      gap:6px;
      margin:0 0 0 auto;
    }
    .simplifi-root .invoice-meta div{
      display:flex;
      justify-content:flex-end;
      gap:20px;
      color:{{ $muted }};
      font-size:10px;
      line-height:14px;
      font-weight:500;
      white-space:nowrap;
      text-align:right;
    }
    .simplifi-root .invoice-meta dt,
    .simplifi-root .invoice-meta dd{
      margin:0;
    }
    .simplifi-root .invoice-meta dd{
      color:{{ $ink }};
      font-weight:800;
    }

    .simplifi-root .bill-to{
      width:54%;
      min-height:136px;
      margin-bottom:44px;
      color:{{ $muted }};
      font-size:10px;
      line-height:16px;
    }
    .simplifi-root .section-label{
      margin:0 0 11px;
      color:{{ $ink }};
      font-size:9px;
      line-height:12px;
      font-weight:800;
      text-transform:uppercase;
      letter-spacing:0.18em;
    }
    .simplifi-root .client-name{
      margin:0 0 2px;
      color:{{ $ink }};
      font-size:13px;
      line-height:18px;
      font-weight:800;
    }

    .simplifi-root .items-list{
      width:100%;
      margin:0 0 30px;
    }
    .simplifi-root .items-head,
    .simplifi-root .item-row{
      display:grid;
      grid-template-columns:minmax(0, 1fr) 54px 94px 52px 100px;
      column-gap:16px;
      align-items:start;
    }
    .simplifi-root .items-list.has-line-discount .items-head,
    .simplifi-root .items-list.has-line-discount .item-row{
      grid-template-columns:minmax(0, 1fr) 46px 82px 50px 76px 92px;
      column-gap:14px;
    }
    .simplifi-root .items-head{
      padding:0 0 12px;
      border-bottom:1px solid {{ $ink }};
      color:{{ $ink }};
      font-size:9px;
      line-height:12px;
      font-weight:900;
      text-transform:uppercase;
      letter-spacing:0.18em;
    }
    .simplifi-root .item-row{
      padding:17px 0 15px;
      border-bottom:1px solid {{ $rule }};
      color:{{ $muted }};
      font-size:10px;
      line-height:15px;
    }
    .simplifi-root .qty{
      text-align:center;
    }
    .simplifi-root .money{
      text-align:right;
    }
    .simplifi-root .tax-col{
      text-align:right;
      white-space:nowrap;
    }
    .simplifi-root .discount-col{
      text-align:right;
      white-space:nowrap;
    }
    .simplifi-root .item-row .qty{
      color:{{ $ink }};
    }
    .simplifi-root .item-row .money{
      color:{{ $ink }};
      font-weight:600;
      white-space:nowrap;
    }
    .simplifi-root .item-row .tax-col{
      color:{{ $ink }};
      font-weight:600;
    }
    .simplifi-root .item-title{
      margin:0 0 2px;
      color:{{ $ink }};
      font-size:11px;
      line-height:16px;
      font-weight:800;
    }
    .simplifi-root .item-description{
      color:{{ $muted }};
      font-size:9px;
      line-height:13px;
    }
    .simplifi-root .empty-cell{
      padding:18px 0;
      border-bottom:1px solid {{ $rule }};
      text-align:center;
      color:{{ $muted }};
    }

    .simplifi-root .totals-wrap{
      display:flex;
      justify-content:flex-end;
      width:100%;
      margin:0 0 62px;
    }
    .simplifi-root .totals-panel{
      width:44%;
      max-width:250px;
    }
    .simplifi-root .total-row{
      display:flex;
      justify-content:space-between;
      gap:20px;
      padding:0 0 10px;
      color:{{ $muted }};
      font-size:10px;
      line-height:14px;
    }
    .simplifi-root .total-row strong,
    .simplifi-root .grand-total strong{
      color:{{ $ink }};
      font-weight:800;
    }
    .simplifi-root .grand-total{
      display:flex;
      justify-content:space-between;
      gap:20px;
      margin-top:10px;
      padding-top:16px;
      border-top:1px solid {{ $rule }};
      color:{{ $ink }};
      font-size:13px;
      line-height:18px;
      font-weight:900;
      text-transform:uppercase;
      letter-spacing:0.14em;
    }
    .simplifi-root .grand-total strong{
      font-size:21px;
      line-height:24px;
      letter-spacing:0.04em;
      text-transform:none;
    }

    .simplifi-root .footer-rule{
      height:1px;
      margin:0 0 38px;
      background:{{ $border }};
    }
    .simplifi-root .footer-grid{
      display:grid;
      grid-template-columns:minmax(0, 1fr) minmax(0, 1fr);
      column-gap:64px;
      color:{{ $muted }};
      font-size:10px;
      line-height:16px;
    }
    .simplifi-root .payment-cell,
    .simplifi-root .notes-cell{
      min-width:0;
    }
    .simplifi-root .payment-lines span,
    .simplifi-root .simplifi-payment-list .label{
      color:{{ $ink }};
      font-weight:800;
    }
    .simplifi-root .payment-note{
      margin-top:4px;
      color:{{ $muted }};
      font-size:9px;
      font-style:italic;
    }
    .simplifi-root .simplifi-payment-list{
      margin:0;
      padding:0;
      list-style:none;
    }
    .simplifi-root .simplifi-payment-list li{
      margin:0 0 1px;
    }
    .simplifi-root .notes-cell p{
      margin:0 0 9px;
      white-space:pre-wrap;
    }
    .simplifi-root .watermark{
      margin-top:28px;
      text-align:center;
      color:{{ $faint }};
      font-size:10px;
      line-height:14px;
      font-weight:700;
    }

    @media print{
      .simplifi-root .simplifi-sheet{
        box-shadow:none;
        border-radius:0;
        padding:46px 54px 36px;
      }
    }

    @media (max-width:680px){
      .simplifi-root .simplifi-sheet{
        padding:34px 24px 30px;
      }
      .simplifi-root .invoice-header,
      .simplifi-root .footer-grid{
        grid-template-columns:1fr;
        row-gap:28px;
      }
      .simplifi-root .invoice-cell{
        text-align:left;
      }
      .simplifi-root .invoice-meta{
        margin-left:0;
      }
      .simplifi-root .invoice-meta div{
        justify-content:space-between;
      }
      .simplifi-root .bill-to,
      .simplifi-root .totals-panel{
        width:100%;
        max-width:none;
      }
      .simplifi-root .items-list{
        overflow-x:auto;
      }
      .simplifi-root .items-head,
      .simplifi-root .item-row{
        min-width:560px;
      }
      .simplifi-root .items-list.has-line-discount .items-head,
      .simplifi-root .items-list.has-line-discount .item-row{
        min-width:640px;
      }
    }
  </style>
</div>
