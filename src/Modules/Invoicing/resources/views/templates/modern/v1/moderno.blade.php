@php
  $accent = data_get($scheme ?? null, 'main.code', '#0b996f') ?: '#0b996f';
  $accentSoft = data_get($scheme ?? null, 'extra_light.code', data_get($scheme ?? null, 'light.code', '#edf9f3')) ?: '#edf9f3';
@endphp

<div class="moderno--theme invoice-root moderno-root scheme cat">
  <div class="moderno-sheet">
    <div class="moderno-header">
      <div class="brand-cell">
        <div class="brand-lockup">
			@if($logoSrc)
			  <div class="brand-mark-cell">
				  <img src="{{ $logoSrc }}" alt="Business Logo" class="logo" />
			  </div>
			@endif
          <div class="brand-name-cell">
            <div class="brand-name">{{ $bp?->name ?? 'Your Business' }}</div>
          </div>
        </div>

        <div class="business-lines">
          @if($bpAddress)<div>{{ $bpAddress }}</div>@endif
          @if($bp?->email)<div>{{ $bp->email }}</div>@endif
          @if($bp?->phone)<div>{{ $bp->phone }}</div>@endif
          @if($bp?->website)<div>{{ $bp->website }}</div>@endif
        </div>
      </div>

      <div class="invoice-cell">
        <div class="invoice-title">INVOICE</div>
        <div class="invoice-number">#{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</div>
        <div class="invoice-meta">
          <div class="meta-row">
            <span>Date Issued:</span>
            <strong>{{ $fmtDate($invoice->issued_on ?? null) }}</strong>
          </div>
          <div class="meta-row">
            <span>Due Date:</span>
            <strong>{{ $fmtDate($invoice->due_on ?? null) }}</strong>
          </div>
        </div>
      </div>
    </div>

    <div class="bill-band">
      <div class="party-layout">
        <div class="bill-cell">
          <div class="section-label">Billed To</div>
          <div class="client-card">
            <div class="client-name">{{ $clientName }}</div>
            @if($cl?->company && $cl?->name && $cl->company !== $cl->name)<div>Attn: {{ $cl->name }}</div>@endif
            @if($clAddress)<div>{{ $clAddress }}</div>@endif
            @if($cl?->email)<div>{{ $cl->email }}</div>@endif
            @if($cl?->phone)<div>{{ $cl->phone }}</div>@endif
            @if($cl?->tax_id)<div>Tax ID: {{ $cl->tax_id }}</div>@endif
            @if($cl?->license_no)<div>License No: {{ $cl->license_no }}</div>@endif
          </div>
        </div>

        <div class="amount-due-cell">
          <div class="amount-card">
            <div class="amount-label">Amount Due</div>
            <div class="amount-value">{{ $fmtMoney($totalDue, $currency) }}</div>
            @if($dueLabel)<div class="amount-caption">{{ $dueLabel }}</div>@endif
          </div>
        </div>
      </div>
    </div>

    <div class="items-section">
      <div class="items-grid{{ $hasLineDiscount ? ' has-discount' : '' }}">
        <div class="item-row item-head">
          <div class="desc-col">Description</div>
          <div class="qty-col">Qty</div>
          <div class="money-col">Unit Price</div>
          <div class="tax-col">Tax</div>
          @if($hasLineDiscount)<div class="tax-col">Discount</div>@endif
          <div class="money-col">Amount</div>
        </div>

        @forelse($items as $it)
          <div class="item-row">
            <div class="desc-col">
              <div class="item-title">{{ $it->name ?? 'Item' }}</div>
              @if(!empty($it->description))<div class="item-description">{{ $it->description }}</div>@endif
            </div>
            <div class="qty-col">{{ $fmtQuantity($it) }}</div>
            <div class="money-col">{{ $fmtItemUnitPrice($it) }}</div>
            <div class="tax-col">{{ $fmtItemTaxRate($it) }}</div>
            @if($hasLineDiscount)<div class="tax-col">{{ $fmtItemLineDiscount($it) }}</div>@endif
            <div class="money-col strong">{{ $fmtItemLineTotal($it) }}</div>
          </div>
        @empty
          <div class="empty-cell">No items.</div>
        @endforelse
      </div>
    </div>

    <div class="lower-layout">
      <div class="payment-cell">
        <div class="lower-section">
          <div class="lower-label">Payment Information</div>
          @if($hasBankTransferDetails)
              <div class="payment-details">
                @foreach($bankTransferDetails as $label => $value)
                  <div class="payment-detail-row"><span>{{ $label }}:</span><strong>{{ $value }}</strong></div>
                @endforeach
              </div>
          @else
            <div class="muted-line">&mdash;</div>
          @endif
        </div>

        <div class="lower-section notes-section">
          <div class="lower-label">Notes &amp; Terms</div>
          @if($invoice->notes || $invoice->terms)
            @if($invoice->notes)<div class="note-copy">{!! nl2br(e($invoice->notes)) !!}</div>@endif
            @if($invoice->terms)<div class="note-copy">{!! nl2br(e($invoice->terms)) !!}</div>@endif
          @else
            <div class="muted-line">&mdash;</div>
          @endif
        </div>

        @include('invoicing::templates.payment-method', ['invoice' => $invoice, 'pi' => $pi])
      </div>

      <div class="summary-cell">
        <div class="summary-box">
          <div class="summary-row">
            <span>Subtotal</span>
            <strong>{{ $fmtMoney($subtotalCents, $currency) }}</strong>
          </div>
          <div class="summary-row">
            <span>{{ $taxLabel }}</span>
            <strong>{{ $fmtMoney($taxCents, $currency) }}</strong>
          </div>
          <div class="summary-row discount-row">
            <span>Discount</span>
            <strong>-{{ $fmtMoney($discountCents, $currency) }}</strong>
          </div>
          @if($hasShipping)
            <div class="summary-row">
              <span>Shipping</span>
              <strong>{{ $fmtMoney($shippingCents, $currency) }}</strong>
            </div>
          @endif
          <div class="summary-total">
            <span>Total</span>
            <strong>{{ $fmtMoney($totalDue, $currency) }}</strong>
          </div>
        </div>
      </div>
    </div>

    {!! $watermark() !!}
  </div>

  <style>
    body,
    .invoice-root,
    .moderno-root {
      margin: 0;
      font-family: {{ $fontFamily }};
      color: #1f2933;
      background: #f3f4f6;
    }

    .moderno-root,
    .moderno-root * {
      box-sizing: border-box;
    }

    .moderno-root {
      --accent: {{ $accent }};
      --accent-soft: {{ $accentSoft }};
      --ink: #17202a;
      --muted: #69717d;
      --line: #edf0f2;
      --paper: #ffffff;
      width: 100%;
      font-size: 11px;
      line-height: 1.45;
    }

    .moderno-sheet {
      width: 100%;
      min-height: 100%;
      background: #ffffff;
      border-radius: 7px;
      overflow: hidden;
      box-shadow: 0 14px 38px rgba(15, 23, 42, .08);
    }

    .moderno-header {
      display: flex;
      align-items: flex-start;
    }

    .brand-cell {
      flex: 0 0 54%;
      padding: 36px 36px 30px 36px;
    }

    .invoice-cell {
      flex: 0 0 46%;
      padding: 34px 36px 30px 18px;
      text-align: right;
    }

    .brand-lockup {
      display: inline-flex;
      align-items: center;
    }

    .brand-mark-cell {
      width: 34px;
      margin-right: 3px;
    }

    .logo,
    .logo-placeholder {
      display: block;
      width: 27px;
      height: 27px;
    }

    .logo {
      object-fit: contain;
      background: #ffffff;
    }

    .logo-placeholder {
      background: var(--accent);
      color: #ffffff;
      font-size: 12px;
      line-height: 27px;
      text-align: center;
      font-weight: 800;
      text-transform: lowercase;
    }

    .brand-name {
      color: #1b2027;
      font-size: 17px;
      line-height: 22px;
      font-weight: 800;
      letter-spacing: 0;
    }

    .business-lines {
      margin-top: 22px;
      max-width: 270px;
      color: #4b5563;
      font-size: 10px;
      line-height: 16px;
      font-weight: 600;
    }

    .business-lines div {
      margin-bottom: 1px;
    }

    .invoice-title {
      color: #111827;
      font-family: "DejaVu Serif", Georgia, serif;
      font-size: 27px;
      line-height: 31px;
      font-weight: 800;
      letter-spacing: .03em;
    }

    .invoice-number {
      margin-top: 2px;
      color: #111827;
      font-size: 10px;
      line-height: 13px;
      font-weight: 800;
      letter-spacing: .11em;
    }

    .invoice-meta {
      width: 172px;
      margin: 20px 0 0 auto;
    }

    .meta-row {
      display: flex;
      justify-content: space-between;
      gap: 14px;
      padding: 2px 0;
      color: #4b5563;
      font-size: 10px;
      line-height: 14px;
      font-weight: 600;
    }

    .meta-row span {
      flex: 0 0 76px;
      text-align: left;
    }

    .meta-row strong {
      color: #111827;
      text-align: right;
      font-weight: 800;
    }

    .bill-band {
      padding: 34px 36px 36px 36px;
      background: #fbfcfd;
      border-top: 1px solid #f5f6f7;
      border-bottom: 1px solid #f2f3f4;
    }

    .party-layout {
      display: flex;
      align-items: flex-start;
    }

    .bill-cell {
      flex: 0 0 50%;
      padding-right: 30px;
    }

    .amount-due-cell {
      flex: 0 0 50%;
      padding: 74px 0 0 30px;
    }

    .section-label {
      margin: 0 0 26px 0;
      color: #8a93a0;
      font-size: 8px;
      line-height: 10px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .14em;
    }

    .client-card {
      min-height: 122px;
      padding: 22px 18px 18px 18px;
      background: #ffffff;
      color: #4b5563;
      font-size: 10px;
      line-height: 17px;
      font-weight: 600;
      box-shadow: 0 12px 24px rgba(15, 23, 42, .025);
    }

    .client-name {
      margin-bottom: 8px;
      color: #111827;
      font-size: 15px;
      line-height: 20px;
      font-weight: 900;
    }

    .amount-card {
      width: 294px;
      margin-left: auto;
      padding: 24px 24px 22px 24px;
      background: var(--accent-soft);
      border-radius: 9px;
      color: var(--accent);
    }

    .amount-label {
      margin-bottom: 8px;
      font-size: 9px;
      line-height: 11px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .16em;
    }

    .amount-value {
      color: var(--accent);
      font-family: "DejaVu Serif", Georgia, serif;
      font-size: 26px;
      line-height: 31px;
      font-weight: 900;
      letter-spacing: .02em;
    }

    .amount-caption {
      margin-top: 2px;
      color: var(--accent);
      font-size: 10px;
      line-height: 13px;
      font-weight: 800;
    }

    .items-section {
      padding: 54px 36px 0 36px;
      background: #ffffff;
    }

    .items-grid {
      width: 100%;
      color: #111827;
    }

    .item-row {
      display: flex;
      align-items: flex-start;
      border-bottom: 1px solid var(--line);
    }

    .item-head {
      border-bottom: 1px solid var(--line);
    }

    .item-head > div {
      padding: 0 0 14px 0;
      color: #8b94a0;
      font-size: 8px;
      line-height: 10px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .16em;
    }

    .item-row:not(.item-head) > div {
      padding: 18px 0 17px 0;
      color: #111827;
      font-size: 11px;
      line-height: 15px;
      font-weight: 700;
    }

    .items-grid .desc-col {
      flex: 1 1 auto;
      padding-right: 18px;
      text-align: left;
    }

    .items-grid .qty-col {
      flex: 0 0 68px;
      text-align: center;
    }

    .items-grid .tax-col {
      flex: 0 0 70px;
      text-align: center;
    }

    .items-grid .money-col {
      flex: 0 0 116px;
      text-align: right;
    }

    .items-grid.has-discount .money-col {
      flex-basis: 108px;
    }

    .item-title {
      color: #111827;
      font-size: 12px;
      line-height: 16px;
      font-weight: 900;
    }

    .item-description {
      margin-top: 3px;
      color: #6b7280;
      font-size: 9px;
      line-height: 13px;
      font-weight: 600;
    }

    .strong {
      font-weight: 900;
    }

    .empty-cell {
      padding: 22px 0;
      color: #8b94a0;
      text-align: center;
    }

    .lower-layout {
      display: flex;
      align-items: flex-start;
      margin-top: 52px;
    }

    .payment-cell {
      flex: 0 0 60%;
      padding: 0 34px 36px 36px;
    }

    .summary-cell {
      flex: 0 0 40%;
      padding: 0 36px 36px 10px;
    }

    .lower-section {
      margin-bottom: 22px;
    }

    .lower-label {
      margin-bottom: 10px;
      color: #8b94a0;
      font-size: 8px;
      line-height: 10px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .16em;
    }

    .payment-details {
      width: auto;
    }

    .payment-detail-row {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      padding: 1px 0;
      color: #111827;
      font-size: 9px;
      line-height: 14px;
      font-weight: 700;
    }

    .payment-detail-row span {
      flex: 0 0 88px;
      color: #111827;
      font-weight: 900;
    }

    .payment-detail-row strong {
      flex: 1 1 auto;
      font-weight: 700;
    }

    .payment-line,
    .payment-fallback,
    .muted-line,
    .note-copy {
      color: #111827;
      font-size: 9px;
      line-height: 15px;
      font-weight: 700;
    }

    .payment-line span {
      font-weight: 900;
    }

    .moderno-payment-list {
      margin: 0;
      padding: 0;
      list-style: none;
    }

    .moderno-payment-list li {
      margin: 0 0 3px 0;
      padding: 0;
    }

    .moderno-payment-list .label {
      font-weight: 900;
    }

    .moderno-payment-list .value {
      font-weight: 700;
    }

    .notes-section {
      max-width: 390px;
    }

    .note-copy {
      margin-bottom: 7px;
      white-space: pre-wrap;
    }

    .summary-box {
      width: 224px;
      margin-left: auto;
      padding: 16px 18px 18px 18px;
      background: #ffffff;
      border: 1px solid #edf0f2;
      border-radius: 7px;
      box-shadow: 0 10px 24px rgba(15, 23, 42, .025);
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      gap: 14px;
      padding: 0 0 12px 0;
      color: #111827;
      font-size: 10px;
      line-height: 13px;
      font-weight: 700;
    }

    .summary-row span {
      color: #4b5563;
    }

    .summary-row strong {
      color: #111827;
      font-weight: 900;
      text-align: right;
    }

    .discount-row strong {
      color: var(--accent);
    }

    .summary-total {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 14px;
      margin-top: 4px;
      padding: 16px 0 16px 0;
      border-top: 1px solid var(--line);
      color: #111827;
      font-size: 11px;
      line-height: 16px;
      font-weight: 900;
    }

    .summary-total strong {
      color: var(--accent);
      font-family: "DejaVu Serif", Georgia, serif;
      font-size: 18px;
      line-height: 20px;
      font-weight: 900;
      letter-spacing: .02em;
      text-align: right;
    }

    .pay-button {
      display: block;
      width: 100%;
      padding: 12px 12px;
      background: var(--accent);
      border-radius: 6px;
      color: #ffffff;
      font-size: 11px;
      line-height: 13px;
      font-weight: 900;
      text-align: center;
      text-decoration: none;
    }

    .watermark {
      margin: 0 36px 18px 36px;
      color: #8b94a0;
      font-size: 10px;
      line-height: 14px;
      text-align: center;
    }

    @media print {
      .moderno-sheet {
        box-shadow: none;
      }
    }
  </style>
</div>
