@php
  // Pick your colors (fallbacks included)
  $fontFamily = $theme['fontFamily'] ?? "DejaVu Sans, Arial, sans-serif";
  $ink        = '#0f172a';
  $muted      = '#64748b';
  $bg         = '#ffffff';
  $card       = '#ffffff';
  $railColor  = match($category ?? 'ocean') {
    'forest'  => '#16a34a',
    'royal'   => '#6d28d9',
    'crimson' => '#dc2626',
    'sunset'  => '#f97316',
    default   => '#0ea5e9',
  };
  $border     = '#e2e8f0';
  $stripe     = '#f8fafc';
  $accentInk  = '#ffffff';

  $logoW = 150;
@endphp

<div class="aurora-root scheme cat-{{ $category }}">
  <div class="sheet">
    <div class="rail"></div>

    <div class="head clearfix">
      <div class="brand">
        @if(($bp?->logo_path))
          <img src="{{ $bp->logo_path }}" alt="logo" class="logo" />
        @endif
        <div class="brand-text">
          <div class="eyebrow">Invoice</div>
          <h1 class="title">{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</h1>
          <div class="meta tiny">
            <span>Issued: {{ $fmtDate($invoice->issued_on ?? null) }}</span>
            <span class="dot">•</span>
            <span>Currency: {{ $invoice->currency ?? 'USD' }}</span>
          </div>
        </div>
      </div>

      <div class="due">
        <div class="kicker tiny">Due Date</div>
        <div class="pill">{{ $fmtDate($invoice->due_on ?? null) }}</div>
        <div class="kicker tiny mt-8">Total</div>
        <div class="grand">{{ $fmtMoney($invoice->total_cents ?? 0, $invoice->currency ?? 'USD') }}</div>
      </div>
    </div>

    <div class="cards clearfix">
      <div class="card left">
        <div class="label">From</div>
        <div class="strong">{{ $bp?->name ?? 'Your Business' }}</div>
        <div class="muted">{{ $bp?->email }}@if($bp?->email && $bp?->phone) • @endif{{ $bp?->phone }}</div>
        <div class="muted">{{ $bp ? $addr($bp) : '' }}</div>
        @if($bp?->tax_id)<div class="muted">Tax ID: {{ $bp->tax_id }}</div>@endif
        @if($bp?->license_no)<div class="muted">License No: {{ $bp->license_no }}</div>@endif
      </div>

      <div class="card right">
        <div class="label">Bill To</div>
        <div class="strong">{{ $cl?->name ?? $cl?->company ?? 'Client' }}</div>
        <div class="muted">{{ $cl?->email }}@if($cl?->email && $cl?->phone) • @endif{{ $cl?->phone }}</div>
        <div class="muted">{{ $cl ? $addr($cl) : '' }}</div>
        @if($cl?->tax_id)<div class="muted">Tax ID: {{ $cl->tax_id }}</div>@endif
        @if($cl?->license_no)<div class="muted">License No: {{ $cl->license_no }}</div>@endif
      </div>
      <div class="clearfix"></div>
    </div>

    <div class="table-card">
      <table class="items" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="desc">Description</th>
            <th>Qty</th>
            <th>Rate</th>
            <th>Amount</th>
          </tr>
        </thead>
        <tbody>
        @if(($items instanceof \Illuminate\Support\Collection ? $items->count() : count($items)) === 0)
          <tr><td colspan="4" class="muted center">No items.</td></tr>
        @else
          @foreach($items as $it)
            <tr>
              <td>
                <div class="strong">{{ $it->name ?? 'Item' }}</div>
                @if(!empty($it->description))<div class="muted small">{{ $it->description }}</div>@endif
              </td>
              <td><span class="chip">{{ rtrim(rtrim((string)($it->quantity ?? 0), '0'), '.') }}{{ $it->unit ? ' '.$it->unit : '' }}</span></td>
              <td>{{ $fmtMoney($it->unit_price_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
              <td>{{ $fmtMoney($it->line_total_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
            </tr>
          @endforeach
        @endif
        </tbody>
      </table>
    </div>

    <div class="totals clearfix">
      <div class="totals-pad"></div>
      <div class="panel">
        <div class="prow clearfix">
					<span>Subtotal</span>
					<span>{{ $fmtMoney($invoice->subtotal_cents ?? 0, $invoice->currency ?? 'USD') }}</span>
				</div>
        @if((int)($invoice->discount_cents ?? 0) > 0)
          <div class="prow clearfix">
						<span>Discount</span>
						<span>-{{ $fmtMoney($invoice->discount_cents ?? 0, $invoice->currency ?? 'USD') }}</span>
					</div>
        @endif
        @if((int)($invoice->tax_cents ?? 0) > 0)
          <div class="prow clearfix">
						<span>Tax</span>
						<span>{{ $fmtMoney($invoice->tax_cents ?? 0, $invoice->currency ?? 'USD') }}</span>
					</div>
        @endif
        @if((int)($invoice->shipping_cents ?? 0) > 0)
          <div class="prow clearfix">
						<span>Shipping</span>
						<span>{{ $fmtMoney($invoice->shipping_cents ?? 0, $invoice->currency ?? 'USD') }}</span>
					</div>
        @endif
        <div class="prow clearfix grand">
					<span>Total</span>
					<span>{{ $fmtMoney($invoice->total_cents ?? 0, $invoice->currency ?? 'USD') }}</span>
				</div>
      </div>
      <div class="clearfix"></div>
    </div>

    <div class="footer clearfix">
      <div class="box left"><h4>Notes</h4><p>{{ $invoice->notes ?? '—' }}</p></div>
      <div class="box right"><h4>Terms</h4><p>{{ $invoice->terms ?? '—' }}</p></div>
      <div class="clearfix"></div>
    </div>

		<div class="watermark">Powered by Billifty.com</div>
  </div>

  <style>
    /* Basics */
    .sheet{width: 914px; position:relative; background: {{ $bg }}; border-radius:16px; padding:28px; font-family: {{ $fontFamily }}; color: {{ $ink }}; }
    /* Simple, supported shadow (optional; remove if you see artifacts) */
    .sheet{ box-shadow: 0 4px 14px rgba(2,6,23,0.08); }
		.head {margin-bottom: 12px;}
    /* Rail (simple gradient supported) */
    .rail{ position:absolute; left:0; top:0; bottom:0; width:10px; background: linear-gradient(180deg, {{ $railColor }}, rgba(255,255,255,0.7)); }

    .eyebrow{ color: {{ $muted }}; text-transform:uppercase; letter-spacing:.1em; font-size:12px; }
    .title{ margin:2px 0 6px; font-size:28px; font-weight:800; letter-spacing:.2px; }
    .tiny{ font-size:12px; } .small{ font-size:12px; } .strong{ font-weight:650; } .muted{ color: {{ $muted }}; }
    .mt-8{ margin-top:8px; } .dot{ margin:0 6px; color:#cbd5e1; }

    /* Floats instead of flex */
    .head.clearfix:after, .cards.clearfix:after, .totals.clearfix:after, .footer.clearfix:after, .clearfix:after { content:""; display:table; clear:both; }
    .brand{ float:left; width:70%; }
    .brand .logo{ width: {{ $logoW }}px; height:auto; border-radius:10px; }
    .brand .brand-text{ display:block; margin-left:0; }
    .due{ float:right; width:28%; text-align:right; }
    .pill{ display:inline-block; background: {{ $railColor }}; color: {{ $accentInk }}; padding:6px 10px; border-radius:999px; font-weight:700; }
    .grand{ font-size:20px; font-weight:800; letter-spacing:.2px; }

    /* Two cards: floats */
    .col-6{ float:left; width:50%; box-sizing:border-box; }
    .cards .col-6:first-child{ padding-right:9px; }
    .cards .col-6:last-child{ padding-left:9px; }
    .card{width: 400px;background: {{ $card }}; border:1px solid {{ $border }}; border-radius:14px; padding:14px 16px; }
    .label{ font-size:11px; color: {{ $muted }}; text-transform:uppercase; letter-spacing:.12em; margin-bottom:4px; }

    /* Items table */
    .table-card{ margin-top:20px; border:1px solid {{ $border }}; border-radius:16px; overflow:hidden; }
    table.items{ width:100%; border-collapse:collapse; }
    .items thead th{ background: {{ $railColor }}; color: {{ $accentInk }}; padding:10px 12px; font-size:12px; text-align:left; }
    .items thead th.desc{ border-left:6px solid rgba(255,255,255,.2); }
    .items tbody td{ padding:12px; border-top:1px solid {{ $border }}; vertical-align:top; font-size:13px; }
    .items tbody tr:nth-child(odd) td{ background: {{ $stripe }}; }
    .right{text-align:right;} .center{text-align:center;}
    .chip{ border:1px solid {{ $border }}; padding:2px 8px; border-radius:999px; font-size:12px; background:#fff; display:inline-block; }

    /* Totals: fake grid with floats */
		.totals {margin: 20px 0;}
    .totals .totals-pad{ float:left; width:65%; }
    .totals .panel{ float:right; width:35%; border:1px solid {{ $border }}; border-radius:14px; padding:12px 14px; background:#fff; box-sizing:border-box; }
    .panel .prow{ padding:8px 0; border-top:1px dashed {{ $border }}; display:block; }
    .panel .prow:first-child{ border-top:0; }
    .panel .prow span:first-child{ float:left; }
    .panel .prow span:last-child{ float:right; }
    .panel .grand{ font-weight:900; font-size:16px; }

    /* Footer two columns */
    .footer .box{width: 400px; border:1px dashed {{ $border }}; border-radius:12px; padding:14px; background:#fcfdff; box-sizing:border-box; }
    .footer .box h4{ margin:0 0 8px 0; font-size:12px; color: {{ $muted }}; text-transform:uppercase; letter-spacing:.1em; }
    .footer .box p{ margin:0; white-space:pre-wrap; font-size:13px; }
    .footer .box.col-6:first-child{ padding-right:9px; }
    .footer .box.col-6:last-child{ padding-left:9px; }
    .badge{ float:right; margin-top:10px; background:#0b1220; color:#e2e8f0; padding:6px 10px; border-radius:10px; font-size:12px; }
		.watermark{width: 300px;margin: 40px auto 0; font-weight: bold;}
    /* Print cleanup */
    @media print{ .sheet{ box-shadow:none; padding:18px; border-radius:0; } .rail{ display:none; } }
  </style>
</div>
