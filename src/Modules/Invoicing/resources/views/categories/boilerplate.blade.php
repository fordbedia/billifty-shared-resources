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
      try {
          $fmt = new \NumberFormatter(\Locale::getDefault() ?: 'en_US', \NumberFormatter::CURRENCY);
          return $fmt->formatCurrency($val, $currency);
      } catch (\Throwable $e) {
          return number_format($val, 2) . ' ' . $currency;
      }
  };
  $fmtDate = fn($d) => $d ? \Carbon\Carbon::parse($d)->toFormattedDateString() : '—';
  $addr = function ($x) {
      $g = is_array($x) ? $x : (method_exists($x, 'toArray') ? $x->toArray() : []);
      $parts = array_filter([
        $g['address_line1'] ?? null,
        $g['address_line2'] ?? null,
        $g['city'] ?? null,
        $g['state'] ?? null,
        $g['postal_code'] ?? null,
        $g['country'] ?? null,
      ]);
      return implode(', ', $parts);
  };

  $bp = $invoice->businessProfile ?? null;
  $cl = $invoice->client ?? null;
  $items = $invoice->items ?? collect();
@endphp

<div class="invoice-root scheme-{{ $scheme }} cat-{{ $category }}">
  <div class="page">
    {{-- HEADER --}}
    <div class="brand">
      @if(($bp?->logo_path))
        <img src="{{ $bp->logo_path }}" alt="logo" class="logo" />
      @endif
      <div>
        <div class="kicker">Invoice</div>
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
        <div class="strong">{{ $bp?->name ?? 'Your Business' }}</div>
        <div class="muted">
          {{ $bp?->email }}@if($bp?->email && $bp?->phone) • @endif{{ $bp?->phone }}
        </div>
        <div class="muted">{{ $bp ? $addr($bp) : '' }}</div>
        @if($bp?->tax_id)
          <div class="muted">Tax ID: {{ $bp->tax_id }}</div>
        @endif
      </div>

      <div class="card">
        <div class="kicker">Bill To</div>
        <div class="strong">{{ $cl?->name ?? $cl?->company ?? 'Client' }}</div>
        <div class="muted">
          {{ $cl?->email }}@if($cl?->email && $cl?->phone) • @endif{{ $cl?->phone }}
        </div>
        <div class="muted">{{ $cl ? $addr($cl) : '' }}</div>
        @if($cl?->tax_id)
          <div class="muted">Tax ID: {{ $cl->tax_id }}</div>
        @endif
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
        <div class="rowline">
          <span>Subtotal</span>
          <span>{{ $fmtMoney($invoice->subtotal_cents ?? 0, $invoice->currency ?? 'USD') }}</span>
        </div>

        @if((int)($invoice->discount_cents ?? 0) > 0)
          <div class="rowline">
            <span>Discount</span>
            <span>-{{ $fmtMoney($invoice->discount_cents ?? 0, $invoice->currency ?? 'USD') }}</span>
          </div>
        @endif

        @if((int)($invoice->tax_cents ?? 0) > 0)
          <div class="rowline">
            <span>Tax</span>
            <span>{{ $fmtMoney($invoice->tax_cents ?? 0, $invoice->currency ?? 'USD') }}</span>
          </div>
        @endif

        @if((int)($invoice->shipping_cents ?? 0) > 0)
          <div class="rowline">
            <span>Shipping</span>
            <span>{{ $fmtMoney($invoice->shipping_cents ?? 0, $invoice->currency ?? 'USD') }}</span>
          </div>
        @endif

        <div class="rowline grand">
          <span>Total</span>
          <span>{{ $fmtMoney($invoice->total_cents ?? 0, $invoice->currency ?? 'USD') }}</span>
        </div>
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
