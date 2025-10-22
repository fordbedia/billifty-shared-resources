<div class="ribbon-root scheme-{{ $scheme }} cat-{{ $categoryName }}">
  <div class="frame">
    <div class="ribbon">
      <div class="left">
        <span class="eyebrow">Invoice</span>
        <span class="id">{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</span>
      </div>
      <div class="right">
        <span class="chip">Issued {{ $fmtDate($invoice->issued_on ?? null) }}</span>
        <span class="chip strong accent">Due {{ $fmtDate($invoice->due_on ?? null) }}</span>
      </div>
    </div>

    <div class="who">
      <div class="card">
        <div class="k">From</div>
        <div class="strong">{{ $bp?->name ?? 'Your Business' }}</div>
        <div class="muted">{{ $bp?->email }}@if($bp?->email && $bp?->phone) • @endif{{ $bp?->phone }}</div>
        <div class="muted">{{ $bp ? $addr($bp) : '' }}</div>
        @if($bp?->tax_id)<div class="muted">Tax ID: {{ $bp->tax_id }}</div>@endif
        @if($bp?->license_no)<div class="muted">License No: {{ $bp->license_no }}</div>@endif
      </div>
      <div class="card">
        <div class="k">Bill To</div>
        <div class="strong">{{ $cl?->name ?? $cl?->company ?? 'Client' }}</div>
        <div class="muted">{{ $cl?->email }}@if($cl?->email && $cl?->phone) • @endif{{ $cl?->phone }}</div>
        <div class="muted">{{ $cl ? $addr($cl) : '' }}</div>
        @if($cl?->tax_id)<div class="muted">Tax ID: {{ $cl->tax_id }}</div>@endif
        @if($cl?->license_no)<div class="muted">License No: {{ $cl->license_no }}</div>@endif
      </div>
      @if(($bp?->logo_path))
        <div class="logo-card"><img src="{{ $bp->logo_path }}" class="logo" alt="logo" /></div>
      @endif
    </div>

    <div class="tablewrap">
      <table class="roundtbl">
        <thead>
          <tr>
            <th class="desc">Description</th>
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
              <tr class="row">
                <td>
                  <div class="strong">{{ $it->name ?? 'Item' }}</div>
                  @if(!empty($it->description))<div class="muted xsmall">{{ $it->description }}</div>@endif
                </td>
                <td><span class="bullet">{{ rtrim(rtrim((string)($it->quantity ?? 0), '0'), '.') }}{{ $it->unit ? ' '.$it->unit : '' }}</span></td>
                <td class="right">{{ $fmtMoney($it->unit_price_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
                <td class="right">{{ $fmtMoney($it->line_total_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>

    <div class="sumgrid">
      <div></div>
      <div class="sumcard">
        <div class="line"><span>Subtotal</span><span>{{ $fmtMoney($invoice->subtotal_cents ?? 0, $invoice->currency ?? 'USD') }}</span></div>
        @if((int)($invoice->discount_cents ?? 0) > 0)
          <div class="line"><span>Discount</span><span>-{{ $fmtMoney($invoice->discount_cents ?? 0, $invoice->currency ?? 'USD') }}</span></div>
        @endif
        @if((int)($invoice->tax_cents ?? 0) > 0)
          <div class="line"><span>Tax</span><span>{{ $fmtMoney($invoice->tax_cents ?? 0, $invoice->currency ?? 'USD') }}</span></div>
        @endif
        @if((int)($invoice->shipping_cents ?? 0) > 0)
          <div class="line"><span>Shipping</span><span>{{ $fmtMoney($invoice->shipping_cents ?? 0, $invoice->currency ?? 'USD') }}</span></div>
        @endif
        <div class="line grand"><span>Total</span><span class="pill">{{ $fmtMoney($invoice->total_cents ?? 0, $invoice->currency ?? 'USD') }}</span></div>
      </div>
    </div>

    <div class="notes">
      <div class="panel"><h4>Notes</h4><p>{{ $invoice->notes ?? '—' }}</p></div>
      <div class="panel"><h4>Terms</h4><p>{{ $invoice->terms ?? '—' }}</p></div>
    </div>
  </div>

  <style>
  .ribbon-root{
    --font: {{ $theme['fontFamily'] ?? "Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif" }};
    --ink:#0b1220; --muted:#6b7280; --bg:#fff; --border:#e6e8ee; --stripe:#f8fafc;
    --accent:#0ea5e9; --accent-ink:#fff;
  }
  .scheme-forest.ribbon-root{ --accent:#16a34a; }
  .scheme-royal.ribbon-root{ --accent:#6d28d9; }
  .scheme-crimson.ribbon-root{ --accent:#dc2626; }
  .scheme-sunset.ribbon-root{ --accent:#f97316; --accent-ink:#111827; }

  .frame{ background:#fff; border-radius:18px; box-shadow:0 8px 28px rgba(2,6,23,.08); overflow:hidden; font-family:var(--font); color:var(--ink); }
  .ribbon{ display:flex; justify-content:space-between; align-items:center; padding:14px 18px; background:linear-gradient(90deg, var(--accent), color-mix(in srgb, var(--accent) 40%, white)); color:var(--accent-ink); }
  .eyebrow{ text-transform:uppercase; font-size:12px; letter-spacing:.12em; opacity:.9; margin-right:8px; }
  .id{ font-weight:900; letter-spacing:.3px; }
  .chip{ background:rgba(255,255,255,.2); padding:6px 10px; border-radius:999px; font-size:12px; }
  .chip.accent{ background:#fff; color:#0b1220; }
  .who{ display:grid; grid-template-columns:1fr 1fr auto; gap:16px; padding:16px 18px; }
  .card{ border:1px solid var(--border); border-radius:14px; padding:12px 14px; }
  .k{ font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:.12em; margin-bottom:2px; }
  .strong{ font-weight:700; } .muted{ color:var(--muted); }
  .logo-card{ display:flex; align-items:center; justify-content:flex-end; }
  .logo{ width:{{ ($theme['logoSize'] ?? 'md')==='lg'?'76px':(($theme['logoSize'] ?? 'md')==='sm'?'32px':'52px') }}; height:auto; border-radius:12px; }

  .tablewrap{ padding:0 18px 18px; }
  .roundtbl{ width:100%; border-collapse:separate; border-spacing:0 8px; }
  .roundtbl thead th{ text-align:left; font-size:12px; color:#475569; padding:0 10px 6px; border-bottom:1px solid var(--border); }
  .roundtbl tbody tr.row td{ background:var(--stripe); border:1px solid var(--border); padding:12px; font-size:13px; }
  .roundtbl tbody tr.row td:first-child{ border-radius:12px 0 0 12px; }
  .roundtbl tbody tr.row td:last-child{ border-radius:0 12px 12px 0; }
  .xsmall{ font-size:12px; } .right{text-align:right;} .center{text-align:center;}
  .bullet{ display:inline-block; padding:2px 8px; border-radius:999px; background:#fff; border:1px solid var(--border); font-size:12px; }

  .sumgrid{ display:grid; grid-template-columns:1fr 320px; gap:16px; padding:0 18px 18px; }
  .sumcard{ border:1px solid var(--border); border-radius:14px; padding:12px 14px; background:#fff; }
  .line{ display:flex; justify-content:space-between; padding:8px 0; border-top:1px dashed var(--border); }
  .line:first-child{ border-top:0; }
  .grand{ font-weight:900; }
  .pill{ background:var(--accent); color:var(--accent-ink); padding:4px 10px; border-radius:999px; }

  .notes{ display:grid; grid-template-columns:1fr 1fr; gap:16px; padding:0 18px 18px; }
  .panel{ border:1px dashed var(--border); border-radius:12px; padding:12px 14px; background:#fcfdff; }
  .panel h4{ margin:0 0 8px; font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:.1em; }
  .panel p{ margin:0; white-space:pre-wrap; font-size:13px; }
  @media print{ .frame{ box-shadow:none; } .ribbon{ -webkit-print-color-adjust:exact; print-color-adjust:exact; } }
  </style>
</div>
