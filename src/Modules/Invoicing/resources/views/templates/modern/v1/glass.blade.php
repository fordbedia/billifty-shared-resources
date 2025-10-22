<div class="glass-root scheme-{{ $scheme }} cat-{{ $categoryName }}">
  <div class="paper">
    <div class="topbar">
      <div class="left">
        <div class="tag">Invoice</div>
        <h1 class="no">{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</h1>
      </div>
      <div class="right">
        <div class="stamp">
          <div class="tiny muted">Due</div>
          <div class="big">{{ $fmtDate($invoice->due_on ?? null) }}</div>
        </div>
      </div>
    </div>

    <div class="grid">
      <div class="pane">
        <div class="k tiny">From</div>
        <div class="strong">{{ $bp?->name ?? 'Your Business' }}</div>
        <div class="muted">{{ $bp?->email }}@if($bp?->email && $bp?->phone) • @endif{{ $bp?->phone }}</div>
        <div class="muted">{{ $bp ? $addr($bp) : '' }}</div>
        @if($bp?->tax_id)<div class="muted">Tax ID: {{ $bp->tax_id }}</div>@endif
        @if($bp?->license_no)<div class="muted">License No: {{ $bp->license_no }}</div>@endif
      </div>
      <div class="pane">
        <div class="k tiny">Bill To</div>
        <div class="strong">{{ $cl?->name ?? $cl?->company ?? 'Client' }}</div>
        <div class="muted">{{ $cl?->email }}@if($cl?->email && $cl?->phone) • @endif{{ $cl?->phone }}</div>
        <div class="muted">{{ $cl ? $addr($cl) : '' }}</div>
        @if($cl?->tax_id)<div class="muted">Tax ID: {{ $cl->tax_id }}</div>@endif
        @if($cl?->license_no)<div class="muted">License No: {{ $cl->license_no }}</div>@endif
      </div>
      <div class="pane logo-pane">
        @if(($bp?->logo_path))
          <img src="{{ $bp->logo_path }}" class="logo" alt="logo" />
        @endif
        <div class="tiny muted">Issued {{ $fmtDate($invoice->issued_on ?? null) }}</div>
      </div>
    </div>

    <div class="glass-card">
      <table class="tbl">
        <thead>
          <tr>
            <th class="desc">Item</th>
            <th>Qty</th>
            <th class="right">Rate</th>
            <th class="right">Amount</th>
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
              <td><span class="qty">{{ rtrim(rtrim((string)($it->quantity ?? 0), '0'), '.') }}{{ $it->unit ? ' '.$it->unit : '' }}</span></td>
              <td class="right">{{ $fmtMoney($it->unit_price_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
              <td class="right">{{ $fmtMoney($it->line_total_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
            </tr>
          @endforeach
        @endif
        </tbody>
      </table>
    </div>

    <div class="summary">
      <div class="gap"></div>
      <div class="card">
        <div class="row"><span>Subtotal</span><span>{{ $fmtMoney($invoice->subtotal_cents ?? 0, $invoice->currency ?? 'USD') }}</span></div>
        @if((int)($invoice->discount_cents ?? 0) > 0)
          <div class="row"><span>Discount</span><span>-{{ $fmtMoney($invoice->discount_cents ?? 0, $invoice->currency ?? 'USD') }}</span></div>
        @endif
        @if((int)($invoice->tax_cents ?? 0) > 0)
          <div class="row"><span>Tax</span><span>{{ $fmtMoney($invoice->tax_cents ?? 0, $invoice->currency ?? 'USD') }}</span></div>
        @endif
        @if((int)($invoice->shipping_cents ?? 0) > 0)
          <div class="row"><span>Shipping</span><span>{{ $fmtMoney($invoice->shipping_cents ?? 0, $invoice->currency ?? 'USD') }}</span></div>
        @endif
        <div class="row grand"><span>Total</span><span>{{ $fmtMoney($invoice->total_cents ?? 0, $invoice->currency ?? 'USD') }}</span></div>
      </div>
    </div>

    <div class="notes">
      <div class="panel">
        <h4>Notes</h4>
        <p>{{ $invoice->notes ?? '—' }}</p>
      </div>
      <div class="panel">
        <h4>Terms</h4>
        <p>{{ $invoice->terms ?? '—' }}</p>
      </div>
    </div>
  </div>

  <style>
  .glass-root{
    --font: {{ $theme['fontFamily'] ?? "Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif" }};
    --bg:#f6f7fb; --card:rgba(255,255,255,.7); --ink:#0b1324; --muted:#6b7280; --border:#eaeef5;
    --accent:#0ea5e9; --accent-ink:#fff;
  }
  .scheme-forest.glass-root{ --accent:#16a34a; }
  .scheme-royal.glass-root{ --accent:#6d28d9; }
  .scheme-crimson.glass-root{ --accent:#dc2626; }
  .scheme-sunset.glass-root{ --accent:#f97316; --accent-ink:#111827; }
  .paper{ background:linear-gradient(180deg, #fff 0%, #f9fbff 100%); border-radius:20px; padding:26px; box-shadow:0 12px 40px rgba(2,6,23,.06); font-family:var(--font); color:var(--ink); }
  .topbar{ display:flex; justify-content:space-between; align-items:flex-end; background:linear-gradient(90deg, var(--accent), color-mix(in srgb, var(--accent) 40%, #c7d2fe)); border-radius:16px; padding:14px 16px; color:var(--accent-ink); }
  .tag{ font-size:12px; text-transform:uppercase; letter-spacing:.12em; opacity:.9; }
  .no{ margin:0; font-size:26px; letter-spacing:.3px; }
  .stamp{ background:rgba(255,255,255,.2); padding:8px 12px; border-radius:12px; text-align:right; }
  .big{ font-size:16px; font-weight:800; }
  .tiny{ font-size:12px; } .small{ font-size:12px; } .muted{ color:var(--muted); } .strong{ font-weight:700; }

  .grid{ display:grid; grid-template-columns:1fr 1fr auto; gap:16px; margin-top:18px; }
  .pane{ backdrop-filter: blur(8px); background:var(--card); border:1px solid var(--border); border-radius:14px; padding:12px 14px; }
  .logo-pane{ display:flex; flex-direction:column; align-items:flex-end; gap:8px; }
  .logo{ width:{{ ($theme['logoSize'] ?? 'md')==='lg'?'72px':(($theme['logoSize'] ?? 'md')==='sm'?'30px':'48px') }}; border-radius:12px; }

  .glass-card{ margin-top:16px; background:var(--card); border:1px solid var(--border); border-radius:16px; overflow:hidden; }
  table.tbl{ width:100%; border-collapse:separate; border-spacing:0; }
  .tbl thead th{ background:#fff; padding:12px; font-size:12px; text-align:left; border-bottom:1px solid var(--border); position:sticky; top:0; }
  .tbl tbody td{ padding:12px; border-top:1px solid var(--border); font-size:13px; }
  .tbl tbody tr:hover td{ background:#f8fafc; }
  .qty{ background:#fff; border:1px solid var(--border); padding:2px 8px; border-radius:999px; font-size:12px; }
  .right{text-align:right;} .center{text-align:center;}
  .desc{ width:46%; }

  .summary{ display:grid; grid-template-columns:1fr 320px; gap:16px; margin-top:14px; }
  .card{ background:var(--card); border:1px solid var(--border); border-radius:14px; padding:12px 14px; }
  .row{ display:flex; justify-content:space-between; padding:8px 0; border-top:1px dashed var(--border); }
  .row:first-child{ border-top:0; }
  .grand{ font-size:16px; font-weight:900; }

  .notes{ display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-top:16px; }
  .panel{ background:var(--card); border:1px solid var(--border); border-radius:14px; padding:12px 14px; }
  .panel h4{ margin:0 0 8px; font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:.1em; }
  .panel p{ margin:0; white-space:pre-wrap; font-size:13px; }
  @media print{ .paper{ box-shadow:none; padding:18px; border-radius:0; } .topbar{ -webkit-print-color-adjust:exact; print-color-adjust:exact; } }
  </style>
</div>
