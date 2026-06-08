@php
  /**
   * Expected data:
   * - $invoice: Eloquent model (with relations: businessProfile, client, items)
   * - $theme: array (fontFamily, logoSize, etc.)
   * - $schemeName: "Ocean Blue" | "Forest Green" | "Royal Purple" | "Crimson Red" | "Sunset Orange"
   * - $categoryName: "Modern" | "Classic" | "Minimal"
   */

  $schemeMap = [
    'Ocean Blue'    => 'ocean',
    'Forest Green'  => 'forest',
    'Royal Purple'  => 'royal',
    'Crimson Red'   => 'crimson',
    'Sunset Orange' => 'sunset',
  ];
  $categoryMap = [
    'Modern'  => 'modern',
    'Classic' => 'classic',
    'Minimal' => 'minimal',
  ];

  $scheme   = $schemeMap[$schemeName ?? 'Ocean Blue'] ?? 'ocean';
  $category = $categoryMap[$categoryName ?? 'Modern'] ?? 'modern';

  // Helpers
  $fmtMoney = function ($cents, $currency = 'USD') {
      $val = ($cents ?? 0) / 100;
      $currency = is_string($currency) ? $currency : ($currency->code ?? 'USD');
      try {
          $fmt = new \NumberFormatter(\Locale::getDefault() ?: 'en_US', \NumberFormatter::CURRENCY);
          return $fmt->formatCurrency($val, $currency);
      } catch (\Throwable $e) {
          return number_format($val, 2) . ' ' . $currency;
      }
  };
  $fmtDate = fn($d) => $d ? \Carbon\Carbon::parse($d)->toFormattedDateString() : '—';
  $addr = function ($x) {
      $parts = array_filter([
        data_get($x, 'address_line1'),
        data_get($x, 'address_line2'),
        data_get($x, 'city'),
        data_get($x, 'state'),
        data_get($x, 'postal_code'),
        data_get($x, 'country'),
      ]);
      return implode(', ', $parts);
  };

  $bp = $invoice->businessProfile ?? null;
  $cl = $invoice->client ?? null;
  $items = $invoice->items ?? collect();
  $currency = $invoice->currency ?? 'USD';
  $currencyCode = strtoupper(trim((string) (is_string($currency) ? $currency : ($currency->code ?? ''))));
  $logoSrc = $logoSrc ?? (data_get($bp, 'logo_path') ?: null);
  $displayText = static function($value): string {
      if ($value instanceof \BackedEnum) {
          $value = $value->value;
      }

      return trim((string) ($value ?? ''));
  };
  $displayRow = static function(?string $label, $value) use ($displayText): ?array {
      $value = $displayText($value);

      return $value === ''
          ? null
          : ['label' => $label, 'value' => $value];
  };
  $businessName = $businessName ?? ($displayText(data_get($bp, 'name')) ?: 'Your Business');
  $businessLegalName = $businessLegalName ?? $displayText(data_get($bp, 'legal_name'));
  $clientCompany = $clientCompany ?? $displayText(data_get($cl, 'company'));
  $clientPersonalName = $clientPersonalName ?? $displayText(data_get($cl, 'name'));
  $clientName = $clientName ?? ($clientCompany !== '' ? $clientCompany : ($clientPersonalName ?: 'Client'));
  $bpAddress = $bpAddress ?? ($bp ? $addr($bp) : '');
  $clAddress = $clAddress ?? ($cl ? $addr($cl) : '');

  if (!isset($businessInfoRows)) {
    $businessInfoRows = array_values(array_filter([
      $businessLegalName !== '' && strcasecmp($businessLegalName, $businessName) !== 0
        ? ['label' => 'Legal Name', 'value' => $businessLegalName]
        : null,
      $displayRow('Address', $bpAddress),
      $displayRow('Email', data_get($bp, 'email')),
      $displayRow('Phone', data_get($bp, 'phone')),
      $displayRow('Website', data_get($bp, 'website')),
      $displayRow('Tax ID', data_get($bp, 'tax_id')),
      $displayRow('License No', data_get($bp, 'license_no')),
    ]));
  }

  if (!isset($clientInfoRows)) {
    $clientInfoRows = array_values(array_filter([
      $clientCompany !== '' && $clientPersonalName !== '' && strcasecmp($clientCompany, $clientPersonalName) !== 0
        ? ['label' => 'Contact', 'value' => $clientPersonalName]
        : null,
      $displayRow('Address', $clAddress),
      $displayRow('Email', data_get($cl, 'email')),
      $displayRow('Phone', data_get($cl, 'phone')),
      $displayRow('Tax ID', data_get($cl, 'tax_id')),
      $displayRow('License No', data_get($cl, 'license_no')),
    ]));
  }

  if (!isset($invoiceTotalsRows)) {
    $toCents = static fn($value) => max(0, (int) (is_numeric($value) ? $value : 0));
    $fmtRate = function($value) {
      $num = (float) ($value ?? 0);

      if ($num > 0 && $num < 1) {
        $num *= 100;
      }

      return rtrim(rtrim(number_format($num, 2), '0'), '.').'%';
    };
    $invoiceTotal = $toCents($invoice->total_cents ?? 0);
    $amountDue = min($invoiceTotal, $toCents($invoice->amount_due_cents ?? $invoiceTotal));
    $discountCents = $toCents($invoice->discount_cents ?? 0);
    $shippingCents = $toCents($invoice->shipping_cents ?? 0);
    $shippingTaxCents = $toCents($invoice->shipping_tax_cents ?? 0);
    $taxCents = $toCents($invoice->tax_cents ?? 0);
    $discountModeRaw = $invoice->discount_mode ?? 'none';
    $discountMode = $discountModeRaw instanceof \BackedEnum ? $discountModeRaw->value : (string) $discountModeRaw;
    $isInvoiceLevelDiscount = in_array($discountMode, ['amount', 'percent'], true)
      || ($discountMode !== 'per-line' && $discountCents > 0);
    $discountLabel = 'Discount';

    if ($discountMode === 'percent' && (float)($invoice->discount_rate ?? 0) > 0) {
      $discountLabel .= ' ('.$fmtRate($invoice->discount_rate).')';
    }

    $statusRawValue = $invoice->status ?? 'issued';
    $statusRaw = $statusRawValue instanceof \BackedEnum ? $statusRawValue->value : $statusRawValue;
    $paymentApplied = in_array($statusRaw, ['paid', 'partial', 'partially', 'partially_paid'], true)
      || !empty($invoice->paid_at)
      || ($amountDue > 0 && $amountDue < $invoiceTotal);
    $amountPaidCents = $paymentApplied ? max(0, $invoiceTotal - $amountDue) : 0;
    $fmtFinalMoney = function($cents) use ($fmtMoney, $currency, $currencyCode) {
      $formatted = $fmtMoney($cents, $currency);

      return $currencyCode !== '' && !str_ends_with($formatted, ' '.$currencyCode)
        ? $formatted.' '.$currencyCode
        : $formatted;
    };

    $invoiceTotalsRows = [
      ['type' => 'subtotal', 'label' => 'Subtotal', 'value' => $fmtMoney($invoice->subtotal_cents ?? 0, $currency)],
    ];
    if ($isInvoiceLevelDiscount && $discountCents > 0) {
      $invoiceTotalsRows[] = ['type' => 'discount', 'label' => $discountLabel, 'value' => '-'.$fmtMoney($discountCents, $currency)];
    }
    if ($shippingCents > 0) {
      $invoiceTotalsRows[] = ['type' => 'shipping', 'label' => 'Shipping', 'value' => $fmtMoney($shippingCents, $currency)];
    }
    if ($shippingTaxCents > 0) {
      $invoiceTotalsRows[] = ['type' => 'shipping_tax', 'label' => 'Shipping Tax', 'value' => $fmtMoney($shippingTaxCents, $currency)];
    }
    if ($taxCents > 0) {
      $invoiceTotalsRows[] = ['type' => 'tax', 'label' => 'Tax', 'value' => $fmtMoney($taxCents, $currency)];
    }
    if ($currencyCode !== '') {
      $invoiceTotalsRows[] = ['type' => 'currency', 'label' => 'Currency', 'value' => $currencyCode];
    }
    $invoiceTotalsRows[] = ['type' => 'total', 'label' => 'Total', 'value' => $fmtFinalMoney($invoiceTotal)];
    if ($amountPaidCents > 0) {
      $invoiceTotalsRows[] = ['type' => 'amount_paid', 'label' => 'Amount Paid', 'value' => '-'.$fmtMoney($amountPaidCents, $currency)];
      $invoiceTotalsRows[] = ['type' => 'balance_due', 'label' => 'Balance Due', 'value' => $fmtFinalMoney($amountDue)];
    }
  }
@endphp

<div class="invoice-root scheme-{{ $scheme }} cat-{{ $category }}">
  <div class="page">
    {{-- HEADER --}}
    <div class="brand">
      @if($logoSrc)
        <img src="{{ $logoSrc }}" alt="logo" class="logo" />
      @endif
      <div>
        <div class="kicker">BusinessProfile</div>
        <h1 class="title">{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</h1>
      </div>
      <div class="spacer"></div>
      <div class="pill">Due {{ $fmtDate($invoice->due_on ?? null) }}</div>
    </div>
    <div class="muted tiny">
      Issued: {{ $fmtDate($invoice->issued_on ?? null) }}
    </div>

    {{-- PARTIES --}}
    <div class="row">
      <div class="card">
        <div class="kicker">From</div>
        <div class="strong">{{ $businessName }}</div>
        @foreach($businessInfoRows as $row)
          <div class="muted">@if($row['label']){{ $row['label'] }}: @endif{{ $row['value'] }}</div>
        @endforeach
      </div>

      <div class="card">
        <div class="kicker">Bill To</div>
        <div class="strong">{{ $clientName }}</div>
        @foreach($clientInfoRows as $row)
          <div class="muted">@if($row['label']){{ $row['label'] }}: @endif{{ $row['value'] }}</div>
        @endforeach
      </div>
    </div>

    {{-- ITEMS --}}
    <div class="card mt-24">
      <table class="items">
        <thead>
          <tr>
            <th style="width:44%;">Description</th>
            <th>Qty</th>
            <th class="right">Rate</th>
            <th class="right">Amount</th>
          </tr>
        </thead>
        <tbody>
          @if(($items instanceof \Illuminate\Support\Collection ? $items->count() : count($items)) === 0)
            <tr>
              <td colspan="4" class="muted">No items.</td>
            </tr>
          @else
            @foreach($items as $it)
              <tr>
                <td>
                  <div class="strong">{{ $it->name ?? 'Item' }}</div>
                  @if(!empty($it->description))
                    <div class="muted">{{ $it->description }}</div>
                  @endif
                </td>
                <td>{{ rtrim(rtrim((string)($it->quantity ?? 0), '0'), '.') }}{{ $it->unit ? ' '.$it->unit : '' }}</td>
                <td class="right">{{ $fmtMoney($it->unit_price_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
                <td class="right">{{ $fmtMoney($it->line_total_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>

    {{-- TOTALS --}}
    <div class="totals">
      <div></div>
      <div class="panel">
        @if(!empty($invoiceShippingAddress))
          <div class="rowline">
            <span>Ship To:</span>
            <span>{{ $invoiceShippingAddress }}</span>
          </div>
        @endif
        @foreach($invoiceTotalsRows as $totalRow)
          <div class="rowline{{ in_array($totalRow['type'], ['total', 'balance_due'], true) ? ' grand' : '' }}">
            <span>{{ $totalRow['label'] }}</span>
            <span>{{ $totalRow['value'] }}</span>
          </div>
        @endforeach
      </div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
      <div class="box">
        <h4>Notes</h4>
        <p>{{ $invoice->notes ?? '—' }}</p>
      </div>
      <div class="box">
        <h4>Terms</h4>
        <p>{{ $invoice->terms ?? '—' }}</p>
      </div>
    </div>
  </div>

  {{-- Local CSS --}}
  <style>
    .invoice-root {
      --font-body: {{ $theme['fontFamily'] ?? "Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif" }};
      --radius: 14px;
      --edge: 32px;
      --muted: #6b7280;
      --ink: #111827;
      --bg: #ffffff;
      --table-stripe: #f9fafb;
      --border: #e5e7eb;
      --accent: #0ea5e9;      /* default; overridden by scheme */
      --accent-ink: #ffffff;  /* default; overridden by scheme */
    }
    .page { font-family: var(--font-body); color: var(--ink); background: var(--bg);
      width: 816px; margin: 0 auto; padding: var(--edge); border-radius: var(--radius);
      box-shadow: 0 2px 14px rgba(17,24,39,.08);
    }
    @media print { .page { box-shadow:none; width:auto; margin:0; padding:24px; border-radius:0; } }

    /* Category micro-tweaks */
    .title { font-size: 28px; margin: 0; }
    .cat-modern .title { letter-spacing:.2px; font-weight:700; }
    .cat-classic .title { font-weight:800; }
    .cat-minimal .title { font-weight:600; }

    .kicker{ color:var(--muted); font-size:12px; text-transform:uppercase; letter-spacing:.08em; margin-bottom:2px; }
    .tiny{ font-size:12px; }
    .strong{ font-weight:600; }

    .brand{ display:flex; align-items:center; gap:12px; }
    .brand .logo{
      width: {{ ($theme['logoSize'] ?? 'md') === 'lg' ? '64px' : (($theme['logoSize'] ?? 'md') === 'sm' ? '32px' : '48px') }};
      height:auto; border-radius:8px; object-fit:contain;
    }
    .muted{ color:var(--muted); }
    .spacer{ flex:1 1 auto; }

    .row{ display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-top:24px; }
    .card{ border:1px solid var(--border); border-radius:12px; padding:16px; background:#fff; }
    .mt-24{ margin-top:24px; }

    table.items{ width:100%; border-collapse:collapse; margin-top:8px; border:1px solid var(--border); border-radius:10px; overflow:hidden; }
    .items thead th{ text-align:left; font-weight:600; font-size:12px; padding:10px 12px; background:var(--accent); color:var(--accent-ink); }
    .items tbody td{ border-top:1px solid var(--border); padding:10px 12px; font-size:13px; vertical-align:top; }
    .items tbody tr:nth-child(odd){ background:var(--table-stripe); }
    .right{text-align:right;}

    .totals{ margin-top:16px; display:grid; grid-template-columns:1fr 280px; gap:24px; align-items:start; }
    .totals .panel{ border:1px solid var(--border); border-radius:12px; padding:16px; }
    .totals .rowline{ display:flex; justify-content:space-between; padding:8px 0; font-size:14px; border-top:1px dashed var(--border); }
    .totals .rowline:first-child{ border-top:0; }
    .totals .grand{ font-weight:800; font-size:16px; }

    .footer{ margin-top:24px; display:grid; grid-template-columns:1fr 1fr; gap:24px; }
    .footer .box{ border:1px solid var(--border); border-radius:12px; padding:16px; background:#fff; }
    .footer .box h4{ margin:0 0 8px 0; font-size:13px; color:var(--muted); text-transform:uppercase; letter-spacing:.08em; }
    .footer .box p{ margin:0; white-space:pre-wrap; font-size:13px; color:var(--ink); }

    /* Color schemes (root class toggles CSS variables) */
    .scheme-ocean  { --accent:#0ea5e9; --accent-ink:#ffffff; }
    .scheme-forest { --accent:#16a34a; --accent-ink:#ffffff; }
    .scheme-royal  { --accent:#6d28d9; --accent-ink:#ffffff; }
    .scheme-crimson{ --accent:#dc2626; --accent-ink:#ffffff; }
    .scheme-sunset { --accent:#f97316; --accent-ink:#111827; }

    .pill{ display:inline-block; background:var(--accent); color:var(--accent-ink);
      padding:6px 10px; border-radius:999px; font-size:12px; font-weight:600; }
  </style>
</div>
