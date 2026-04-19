@php
  $fontFamily = data_get($theme ?? null, 'fontFamily', 'DejaVu Sans, Arial, sans-serif');
  $accent = data_get($scheme ?? null, 'main.code', '#3154d4') ?: '#3154d4';
  $accentDark = data_get($scheme ?? null, 'dark.code', '#2847c7') ?: '#2847c7';
  $accentSoft = data_get($scheme ?? null, 'extra_light.code', data_get($scheme ?? null, 'light.code', '#eef3ff')) ?: '#eef3ff';
  $currency = $invoice->currency ?? 'USD';
  $totalDue = $invoice->amount_due_cents ?? $invoice->total_cents ?? 0;
  $bp = $bp ?? ($invoice->businessProfile ?? null);
  $cl = $cl ?? ($invoice->client ?? null);
  $items = $items ?? ($invoice->items ?? collect());
  $pi = $pi ?? ($bp?->payment_information ?? $bp?->paymentInformation ?? null);
  $clientName = $cl?->company ?: ($cl?->name ?? 'Client');
  $paymentMethod = $pi?->payment_method instanceof \BackedEnum ? $pi->payment_method->value : ($pi?->payment_method ?? null);

  $fmtRate = function($value) {
    $num = (float) ($value ?? 0);

    if ($num > 0 && $num < 1) {
      $num *= 100;
    }

    return rtrim(rtrim(number_format($num, 2), '0'), '.').'%';
  };

  $addressLines = function($entity) {
    if (!$entity) {
      return [];
    }

    $g = is_array($entity) ? $entity : (method_exists($entity, 'toArray') ? $entity->toArray() : (array) $entity);
    $cityState = implode(', ', array_filter([
      $g['city'] ?? null,
      $g['state'] ?? null,
    ]));
    $cityLine = trim(implode(' ', array_filter([
      $cityState,
      $g['postal_code'] ?? null,
    ])));

    return array_values(array_filter([
      $g['address_line1'] ?? null,
      $g['address_line2'] ?? null,
      $cityLine ?: null,
      $g['country'] ?? null,
    ]));
  };

  $textLines = function($value) {
    $text = trim((string) ($value ?? ''));

    if ($text === '') {
      return [];
    }

    return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $text))));
  };

  $freeAddressLines = function($value) {
    $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', trim((string) ($value ?? ''))))));

    if (count($lines) <= 1 && !empty($lines[0]) && str_contains($lines[0], ',')) {
      $lines = array_values(array_filter(array_map('trim', explode(',', $lines[0]))));
    }

    return $lines;
  };

  $maskAccount = function($value) {
    $raw = trim((string) $value);
    $digits = preg_replace('/\D+/', '', $raw);

    if ($digits === '') {
      return $raw;
    }

    return '**** '.substr($digits, -4);
  };

  $bpAddressLines = $addressLines($bp);
  $clAddressLines = $addressLines($cl);
  $shippingAddress = data_get($invoice ?? null, 'shipping_address');
  $shipAddressLines = $freeAddressLines($shippingAddress);

  $dueBaseDate = $invoice->issued_on ? \Carbon\Carbon::parse($invoice->issued_on)->startOfDay() : null;
  $dueDate = $invoice->due_on ? \Carbon\Carbon::parse($invoice->due_on)->startOfDay() : null;
  $daysRemaining = $dueBaseDate && $dueDate ? (int) round($dueBaseDate->diffInDays($dueDate, false)) : null;
  $paymentTerms = match (true) {
    $daysRemaining === null => null,
    $daysRemaining <= 0 => 'Due on receipt',
    default => "Net {$daysRemaining}",
  };

  $itemTaxRates = [];

  foreach ($items as $item) {
    $rate = (float) ($item->tax_rate ?? 0);

    if ($rate > 0) {
      $itemTaxRates[] = $fmtRate($rate);
    }
  }

  $itemTaxRates = array_values(array_unique($itemTaxRates));
  $taxLabel = count($itemTaxRates) === 1 ? 'Tax ('.$itemTaxRates[0].')' : 'Tax';
  $notesLines = $textLines($invoice->notes ?? null);
  $termsLines = $textLines($invoice->terms ?? null);
@endphp

<div class="mono--theme invoice-root mono-root scheme cat">
  <div class="mono-sheet">
    <header class="mono-header">
      <div class="mono-brand">
        <div class="mono-title">INVOICE</div>
		  @if($logoSrc)
			  <div class="brand-mark-cell">
				  <img src="{{ $logoSrc }}" alt="Business Logo" class="logo" />
			  </div>
			@endif
        <div class="mono-business-name">{{ $bp?->name ?? 'Your Business' }}</div>
        <div class="mono-business-lines">
          @foreach($bpAddressLines as $line)<div>{{ $line }}</div>@endforeach
          @if($bp?->email)<div>{{ $bp->email }}</div>@endif
          @if($bp?->phone)<div>{{ $bp->phone }}</div>@endif
          @if($bp?->website)<div>{{ $bp->website }}</div>@endif
        </div>
      </div>

      <div class="mono-invoice-card">
        <div class="mono-card-row mono-card-row-number">
          <span>Invoice Number</span>
          <strong>#{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</strong>
        </div>
        <div class="mono-card-row">
          <span>Invoice Date</span>
          <strong>{{ $fmtDate($invoice->issued_on ?? null) }}</strong>
        </div>
        <div class="mono-card-row">
          <span>Due Date</span>
          <strong>{{ $fmtDate($invoice->due_on ?? null) }}</strong>
        </div>
      </div>
    </header>

    <main class="mono-body">
      <section class="mono-parties">
        <div class="mono-party-box">
          <div class="mono-section-heading mono-heading-bill">Bill To</div>
          <div class="mono-party-name">{{ $clientName }}</div>
          @if($cl?->company && $cl?->name && $cl->company !== $cl->name)<div>Attn: {{ $cl->name }}</div>@endif
          @foreach($clAddressLines as $line)<div>{{ $line }}</div>@endforeach
          @if($cl?->email)<div>{{ $cl->email }}</div>@endif
          @if($cl?->phone)<div>{{ $cl->phone }}</div>@endif
          @if($cl?->tax_id)<div>Tax ID: {{ $cl->tax_id }}</div>@endif
          @if($cl?->license_no)<div>License No: {{ $cl->license_no }}</div>@endif
        </div>

        <div class="mono-party-box">
          <div class="mono-section-heading mono-heading-ship">Ship To</div>
          <div class="mono-party-name">{{ $clientName }}</div>
          @if(count($shipAddressLines) > 0)
            @foreach($shipAddressLines as $line)<div>{{ $line }}</div>@endforeach
          @else
            @foreach($clAddressLines as $line)<div>{{ $line }}</div>@endforeach
            <div>Same as billing address</div>
          @endif
        </div>
      </section>

      <section class="mono-items-section">
        <div class="mono-section-heading mono-heading-items">Items &amp; Services</div>
        <table class="mono-items{{ $hasLineDiscount ? ' has-discount' : '' }}">
          <thead>
            <tr>
              <th class="mono-desc-col">Description</th>
              <th class="mono-qty-col">Qty</th>
              <th class="mono-rate-col">Rate</th>
              @if($hasLineDiscount)<th class="mono-discount-col">Discount</th>@endif
              <th class="mono-amount-col">Amount</th>
            </tr>
          </thead>
          <tbody>
            @forelse($items as $it)
              <tr>
                <td class="mono-desc-col">
                  <div class="mono-item-title">{{ $it->name ?? 'Item' }}</div>
                  @if(!empty($it->description))<div class="mono-item-description">{{ $it->description }}</div>@endif
                </td>
                <td class="mono-qty-col">{{ rtrim(rtrim((string) ($it->quantity ?? 0), '0'), '.') }}{{ $it->unit ? ' '.$it->unit : '' }}</td>
                <td class="mono-rate-col">{{ $fmtMoney($it->unit_price_cents ?? 0, $currency) }}</td>
                @if($hasLineDiscount)<td class="mono-discount-col">{{ $fmtPercent($it->line_discount_rate ?? 0) }}</td>@endif
                <td class="mono-amount-col">{{ $fmtMoney($it->line_total_cents ?? 0, $currency) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="{{ $hasLineDiscount ? 5 : 4 }}" class="mono-empty">No items.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </section>

      <section class="mono-totals-wrap">
        <div class="mono-totals">
          <div class="mono-total-row">
            <span>Subtotal:</span>
            <strong>{{ $fmtMoney($invoice->subtotal_cents ?? 0, $currency) }}</strong>
          </div>
          @if((int)($invoice->discount_cents ?? 0) > 0)
            <div class="mono-total-row">
              <span>Discount:</span>
              <strong>-{{ $fmtMoney($invoice->discount_cents ?? 0, $currency) }}</strong>
            </div>
          @endif
          <div class="mono-total-row">
            <span>{{ $taxLabel }}:</span>
            <strong>{{ $fmtMoney($invoice->tax_cents ?? 0, $currency) }}</strong>
          </div>
          @if((int)($invoice->shipping_cents ?? 0) > 0)
            <div class="mono-total-row">
              <span>Shipping:</span>
              <strong>{{ $fmtMoney($invoice->shipping_cents ?? 0, $currency) }}</strong>
            </div>
          @endif
          <div class="mono-grand-total">
            <span>Total:</span>
            <strong>{{ $fmtMoney($totalDue, $currency) }}</strong>
          </div>
        </div>
      </section>

      <section class="mono-footer-grid">
        <div class="mono-info-box mono-payment-box">
          <div class="mono-section-heading mono-heading-payment">Payment Information</div>
          @if($paymentTerms)
            <div class="mono-info-line"><span>Payment Terms:</span> <strong>{{ $paymentTerms }}</strong></div>
          @endif
          @if($pi)
            @if($paymentMethod === 'bank_transfer')
              <div class="mono-info-line"><span>Payment Method:</span> <strong>Bank Transfer</strong></div>
              @if($pi->bank_name)<div class="mono-info-line"><span>Bank:</span> <strong>{{ $pi->bank_name }}</strong></div>@endif
              @if($pi->account_name)<div class="mono-info-line"><span>Account Name:</span> <strong>{{ $pi->account_name }}</strong></div>@endif
              @if($pi->account_number)<div class="mono-info-line"><span>Account:</span> <strong>{{ $maskAccount($pi->account_number) }}</strong></div>@endif
              @if($pi->routing_number)<div class="mono-info-line"><span>Routing:</span> <strong>{{ $pi->routing_number }}</strong></div>@endif
              @if($pi->iban)<div class="mono-info-line"><span>IBAN:</span> <strong>{{ $pi->iban }}</strong></div>@endif
              @if($pi->swift_code)<div class="mono-info-line"><span>Swift:</span> <strong>{{ $pi->swift_code }}</strong></div>@endif
            @elseif($paymentMethod === 'paypal' && $pi->paypal_email)
              <div class="mono-info-line"><span>Payment Method:</span> <strong>PayPal</strong></div>
              <div class="mono-info-line"><span>PayPal:</span> <strong>{{ $pi->paypal_email }}</strong></div>
            @elseif($paymentMethod === 'stripe' && $pi->stripe_payment_link)
              <div class="mono-info-line"><span>Payment Method:</span> <strong>Stripe</strong></div>
              <div class="mono-info-line"><span>Payment Link:</span> <strong>{{ $pi->stripe_payment_link }}</strong></div>
            @elseif($paymentMethod === 'cash_app' && $pi->cash_app)
              <div class="mono-info-line"><span>Payment Method:</span> <strong>Cash App</strong></div>
              <div class="mono-info-line"><span>Cash App:</span> <strong>{{ $pi->cash_app }}</strong></div>
            @else
              <div class="mono-payment-fallback">{!! $paymentInfo($pi, 'mono-payment-list') !!}</div>
            @endif
          @elseif(!$paymentTerms)
            <div class="mono-muted">&mdash;</div>
          @endif
        </div>

        <div class="mono-info-box mono-notes-box">
          <div class="mono-section-heading mono-heading-notes">Notes &amp; Terms</div>
          @if(count($notesLines) > 0 || count($termsLines) > 0)
            <ul class="mono-note-list">
              @foreach($notesLines as $line)<li>{{ $line }}</li>@endforeach
              @foreach($termsLines as $line)<li>{{ $line }}</li>@endforeach
            </ul>
          @else
            <div class="mono-muted">&mdash;</div>
          @endif
        </div>
      </section>
    </main>

    {!! $watermark() !!}
  </div>

  <style>
    body,
    .invoice-root,
    .mono-root {
      margin: 0;
      font-family: {{ $fontFamily }};
      color: #1f2430;
      background: linear-gradient(180deg, #f7f8fb 0%, #eef1f6 100%);
    }

    .mono-root,
    .mono-root * {
      box-sizing: border-box;
    }

    .mono-root {
      --mono-blue: {{ $accent }};
      --mono-blue-dark: {{ $accentDark }};
      --mono-blue-soft: {{ $accentSoft }};
      --mono-ink: #222835;
      --mono-muted: #5d6470;
      --mono-line: #dfe3ea;
      --mono-table-head: #f1f3f7;
      --mono-panel: #f8f9fc;
      --mono-surface: #ffffff;
      --mono-payment: #eef4ff;
      --mono-notes: #fffdf0;
      --mono-radius: 8px;
      --mono-shadow-soft: 0 14px 28px rgba(15, 23, 42, .055);
      --mono-shadow-page: 0 18px 40px rgba(15, 23, 42, .10);
      width: 100%;
      font-size: 11px;
      line-height: 1.45;
    }

    .mono-sheet {
      width: 100%;
      min-height: 100%;
      overflow: hidden;
      background: var(--mono-surface);
      border-radius: var(--mono-radius);
      box-shadow: var(--mono-shadow-page);
    }

    .mono-header {
      position: relative;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      min-height: 136px;
      padding: 27px 31px 26px 31px;
      overflow: hidden;
      background: {{$accent}};
      color: #ffffff;
    }

    .mono-header::after {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(90deg, rgba(255, 255, 255, .10), rgba(255, 255, 255, 0));
      pointer-events: none;
    }

    .mono-brand {
      position: relative;
      z-index: 1;
      flex: 1 1 auto;
      padding-top: 3px;
      padding-right: 28px;
    }
	.brand-mark-cell img {
		width: 34px;
	}

    .mono-title {
      margin-bottom: 12px;
      color: #ffffff;
      font-size: 23px;
      line-height: 27px;
      font-weight: 900;
      letter-spacing: 0;
    }

    .mono-business-name {
      margin-bottom: 1px;
      color: rgba(255, 255, 255, .98);
      font-size: 12px;
      line-height: 15px;
      font-weight: 800;
    }

    .mono-business-lines {
      max-width: 235px;
      color: rgba(255, 255, 255, .94);
      font-size: 9px;
      line-height: 13px;
      font-weight: 600;
    }

    .mono-invoice-card {
      position: relative;
      z-index: 1;
      flex: 0 0 142px;
      min-height: 103px;
      padding: 17px 15px 15px 15px;
      border-radius: 6px;
      background: rgba(255, 255, 255, .13);
      box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .08);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      text-align: right;
    }

    .mono-card-row {
      margin-bottom: 13px;
    }

    .mono-card-row:last-child {
      margin-bottom: 0;
    }

    .mono-card-row span {
      display: block;
      color: rgba(255, 255, 255, .64);
      font-size: 7px;
      line-height: 9px;
      font-weight: 800;
    }

    .mono-card-row strong {
      display: block;
      margin-top: 2px;
      color: #ffffff;
      font-size: 10px;
      line-height: 13px;
      font-weight: 900;
    }

    .mono-card-row-number strong {
      font-size: 11px;
      letter-spacing: .02em;
    }

    .mono-body {
      padding: 30px 31px 34px 31px;
      background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
    }

    .mono-parties {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
      column-gap: 22px;
      margin-bottom: 30px;
    }

    .mono-party-box {
      min-height: 117px;
      padding: 18px 19px 16px 19px;
      background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
      border: 1px solid rgba(223, 227, 234, .52);
      border-radius: 4px;
      color: var(--mono-ink);
      font-size: 10px;
      line-height: 15px;
      box-shadow: var(--mono-shadow-soft);
    }

    .mono-party-name {
      margin-bottom: 5px;
      color: #222835;
      font-size: 12px;
      line-height: 16px;
      font-weight: 900;
    }

    .mono-section-heading {
      position: relative;
      margin: 0 0 13px 0;
      padding-left: 18px;
      color: #303645;
      font-size: 12px;
      line-height: 15px;
      font-weight: 900;
    }

    .mono-section-heading::before {
      position: absolute;
      top: 3px;
      left: 0;
      width: 10px;
      height: 10px;
      border-radius: 2px;
      background: linear-gradient(135deg, var(--mono-blue), var(--mono-blue-dark));
      color: #ffffff;
      font-size: 7px;
      line-height: 10px;
      font-weight: 900;
      text-align: center;
    }

    .mono-heading-bill::before {
      content: "i";
    }

    .mono-heading-ship::before,
    .mono-heading-items::before,
    .mono-heading-payment::before {
      content: "";
    }

    .mono-heading-items::before {
      top: 5px;
      width: 12px;
      height: 2px;
      border-top: 2px solid var(--mono-blue);
      border-bottom: 2px solid var(--mono-blue);
      border-radius: 0;
      background: transparent;
    }

    .mono-heading-payment::before {
      top: 5px;
      width: 12px;
      height: 7px;
      border-top: 2px solid var(--mono-blue);
      border-bottom: 2px solid var(--mono-blue);
      border-radius: 1px;
      background: transparent;
    }

    .mono-heading-notes::before {
      content: "";
      border-radius: 50%;
      background: #d6a82e;
    }

    .mono-items-section {
      margin-top: 0;
    }

    .mono-items {
      width: 100%;
      overflow: hidden;
      border-collapse: separate;
      border-spacing: 0;
      table-layout: fixed;
      color: #252b37;
      border: 1px solid var(--mono-line);
      border-radius: 4px;
      box-shadow: 0 8px 20px rgba(15, 23, 42, .035);
    }

    .mono-items th {
      padding: 10px 13px;
      background: linear-gradient(180deg, #f6f8fb 0%, var(--mono-table-head) 100%);
      border-right: 1px solid var(--mono-line);
      border-bottom: 1px solid var(--mono-line);
      color: #303645;
      font-size: 9px;
      line-height: 12px;
      font-weight: 900;
      text-align: left;
    }

    .mono-items th:last-child,
    .mono-items td:last-child {
      border-right: 0;
    }

    .mono-items td {
      padding: 11px 13px 12px 13px;
      border-right: 1px solid var(--mono-line);
      border-bottom: 1px solid var(--mono-line);
      background: rgba(255, 255, 255, .88);
      color: #252b37;
      font-size: 10px;
      line-height: 14px;
      vertical-align: top;
    }

    .mono-items tbody tr:nth-child(even) td {
      background: #fbfcff;
    }

    .mono-items tbody tr:last-child td {
      border-bottom: 0;
    }

    .mono-desc-col {
      width: 58%;
      text-align: left;
    }

    .mono-qty-col {
      width: 9%;
      text-align: center !important;
    }

    .mono-rate-col,
    .mono-amount-col {
      width: 16.5%;
      text-align: right !important;
    }

    .mono-discount-col {
      width: 13%;
      text-align: right !important;
    }

    .mono-items.has-discount .mono-desc-col {
      width: 47%;
    }

    .mono-items.has-discount .mono-rate-col,
    .mono-items.has-discount .mono-amount-col {
      width: 15.5%;
    }

    .mono-item-title {
      color: #202633;
      font-weight: 900;
    }

    .mono-item-description {
      margin-top: 3px;
      color: #454d5d;
      font-size: 9px;
      line-height: 13px;
      font-weight: 500;
    }

    .mono-empty {
      padding: 18px;
      color: #6b7280;
      text-align: center;
    }

    .mono-totals-wrap {
      display: flex;
      justify-content: flex-end;
      margin-top: 29px;
      margin-bottom: 28px;
    }

    .mono-totals {
      width: 314px;
      padding: 15px 22px 17px 22px;
      background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
      border: 1px solid rgba(223, 227, 234, .55);
      border-radius: 4px;
      box-shadow: var(--mono-shadow-soft);
    }

    .mono-total-row,
    .mono-grand-total {
      display: flex;
      justify-content: space-between;
      gap: 24px;
      color: #222835;
      font-size: 10px;
      line-height: 14px;
      font-weight: 700;
    }

    .mono-total-row {
      margin-bottom: 10px;
    }

    .mono-total-row span {
      color: #333a49;
      font-weight: 700;
    }

    .mono-total-row strong {
      color: #202633;
      font-weight: 900;
      text-align: right;
    }

    .mono-grand-total {
      margin-top: 14px;
      padding-top: 13px;
      border-top: 1px solid var(--mono-line);
      font-size: 12px;
      line-height: 17px;
      font-weight: 900;
    }

    .mono-grand-total strong {
      color: var(--mono-blue-dark);
      font-size: 16px;
      line-height: 18px;
      font-weight: 900;
      text-align: right;
    }

    .mono-footer-grid {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
      column-gap: 22px;
      margin-top: 0;
    }

    .mono-info-box {
      min-height: 121px;
      padding: 18px 19px 17px 19px;
      border: 1px solid rgba(223, 227, 234, .42);
      border-radius: 4px;
      box-shadow: 0 10px 22px rgba(15, 23, 42, .035);
      color: #242a37;
      font-size: 9px;
      line-height: 15px;
    }

    .mono-payment-box {
      background: linear-gradient(180deg, var(--mono-payment) 0%, #f6f9ff 100%);
    }

    .mono-notes-box {
      background: linear-gradient(180deg, var(--mono-notes) 0%, #fffdf6 100%);
    }

    .mono-info-line {
      color: #242a37;
      font-weight: 600;
    }

    .mono-info-line span,
    .mono-payment-list .label {
      font-weight: 900;
    }

    .mono-info-line strong,
    .mono-payment-list .value {
      font-weight: 700;
    }

    .mono-payment-fallback {
      color: #242a37;
      font-size: 9px;
      line-height: 15px;
      font-weight: 600;
    }

    .mono-payment-list {
      margin: 0;
      padding: 0;
      list-style: none;
    }

    .mono-payment-list li {
      margin: 0;
      padding: 0;
    }

    .mono-note-list {
      margin: 0;
      padding: 0 0 0 8px;
      list-style-position: inside;
    }

    .mono-note-list li {
      margin: 0 0 3px 0;
      color: #242a37;
      font-size: 9px;
      line-height: 14px;
      font-weight: 600;
    }

    .mono-muted {
      color: #6b7280;
      font-size: 10px;
      line-height: 14px;
      font-weight: 700;
    }

    .mono-root .watermark {
      margin: 18px 31px 14px 31px;
      color: #8b94a0;
      font-size: 10px;
      line-height: 14px;
      text-align: center;
    }

    @media print {
      body,
      .invoice-root,
      .mono-root {
        background: #ffffff;
      }

      .mono-sheet {
        box-shadow: none;
      }

      .mono-invoice-card {
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
      }
    }

    @media (max-width: 680px) {
      .mono-header,
      .mono-parties,
      .mono-footer-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 18px;
      }

      .mono-invoice-card,
      .mono-totals {
        width: 100%;
      }

      .mono-body,
      .mono-header {
        padding-left: 22px;
        padding-right: 22px;
      }
    }
  </style>
</div>
