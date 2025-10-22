<div class="neo-root scheme-{{ $scheme }} cat-{{ $categoryName }}">
  <div class="canvas">
    <div class="header">
      <div class="left">
        <div class="eyebrow">Invoice</div>
        <h1 class="num">{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</h1>
        <div class="tiny muted">Issued {{ $fmtDate($invoice->issued_on ?? null) }}</div>
      </div>
      <div class="right">
        <div class="due">Due</div>
        <div class="dueval">{{ $fmtDate($invoice->due_on ?? null) }}</div>
      </div>
    </div>

    <div class="columns">
      <div class="col main">
        <div class="split">
          <div class="info">
            <div class="k tiny">From</div>
            <div class="strong">{{ $bp?->name ?? 'Your Business' }}</div>
            <div class="muted tiny">{{ $bp?->email }}@if($bp?->email && $bp?->phone) • @endif{{ $bp?->phone }}</div>
            <div class="muted tiny">{{ $bp ? $addr($bp) : '' }}</div>
            @if($bp?->tax_id)<div class="muted tiny">Tax ID: {{ $bp->tax_id }}</div>@endif
            @if($bp?->license_no)<div class="muted tiny">License No: {{ $bp->license_no }}</div>@endif
          </div>
          <div class="info">
            <div class="k tiny">Bill To</div>
            <div class="strong">{{ $cl?->name ?? $cl?->company ?? 'Client' }}</div>
            <div class="muted tiny">{{ $cl?->email }}@if($cl?->email && $cl?->phone) • @endif{{ $cl?->phone }}</div>
            <div class="muted tiny">{{ $cl ? $addr($cl) : '' }}</div>
            @if($cl?->tax_id)<div class="muted tiny">Tax ID: {{ $cl->tax_id }}</div>@endif
            @if($cl?->license_no)<div class="muted tiny">License No: {{ $cl->license_no }}</div>@endif
          </div>
        </div>

        <div class="card table">
          <table class="grid">
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
                  <tr>
                    <td>
                      <div class="strong">{{ $it->name ?? 'Item' }}</div>
                      @if(!empty($it->description))<div class="muted small">{{ $it->description }}</div>@endif
                    </td>
                    <td><span class="tag">{{ rtrim(rtrim((string)($it->quantity ?? 0), '0'), '.') }}{{ $it->unit ? ' '.$it->unit : '' }}</span></td>
                    <td class="right">{{ $fmtMoney($it->unit_price_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
                    <td class="right">{{ $fmtMoney($it->line_total_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
                  </tr>
                @endforeach
              @endif
            </tbody>
          </table>
        </div>

        <div class="notes">
          <div class="panel"><h4>Notes</h4><p>{{ $invoice->notes ?? '—' }}</p></div>
          <div class="panel"><h4>Terms</h4><p>{{ $invoice->terms ?? '—' }}</p></div>
        </div>
      </div>

      <div class="col side">
        <div class="summary">
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

        @if(($bp?->logo_path))
          <div class="brand">
            <img src="{{ $bp->logo_path }}" alt="logo" class="logo"/>
          </div>
        @endif
      </div>
    </div>
  </div>

  <style>
  .neo-root{
    --font: {{ $theme['fontFamily'] ?? "Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif" }};
    --ink:#111827; --muted:#6b7280; --bg:#fff; --border:#e5e7eb; --stripe:#fafafa;
    --accent:#0ea5e9; --accent-ink:#fff;
  }
  .scheme-forest.neo-root{ --accent:#16a34a; }
  .scheme-royal.neo-root{ --accent:#6d28d9; }
  .scheme-crimson.neo-root{ --accent:#dc2626; }
  .scheme-sunset.neo-root{ --accent:#f97316; --accent-ink:#111827; }

  .canvas{ background:#fff; border-radius:18px; box-shadow:0 12px 34px rgba(2,6,23,.08); padding:22px; font-family:var(--font); color:var(--ink); }
  .header{ display:flex; justify-content:space-between; align-items:end; border-bottom:1px solid var(--border); padding-bottom:12px; }
  .eyebrow{ color:var(--muted); text-transform:uppercase; letter-spacing:.12em; font-size:12px; }
  .num{ font-size:28px; margin:2px 0 6px; letter-spacing:.2px; font-weight:800; }
  .right .due{ font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:.12em; }
  .right .dueval{ background:var(--accent); color:var(--accent-ink); padding:6px 10px; border-radius:10px; font-weight:800; margin-top:6px; text-align:right; }

  .columns{ display:grid; grid-template-columns:1fr 320px; gap:18px; margin-top:16px; }
  .split{ display:grid; grid-template-columns:1fr 1fr; gap:16px; }
  .info .k{ color:var(--muted); text-transform:uppercase; letter-spacing:.12em; margin-bottom:4px; }
  .strong{ font-weight:700; } .muted{ color:var(--muted); } .tiny{ font-size:12px; } .small{ font-size:12px; }

  .card.table{ border:1px solid var(--border); border-radius:14px; overflow:hidden; margin-top:16px; }
  table.grid{ width:100%; border-collapse:separate; border-spacing:0; }
  table.grid thead th{ background:#fff; position:sticky; top:0; text-align:left; font-size:12px; padding:12px; border-bottom:1px solid var(--border); }
  table.grid tbody td{ padding:12px; border-top:1px solid var(--border); font-size:13px; }
  table.grid tbody tr:nth-child(odd) td{ background:var(--stripe); }
  .right{text-align:right;} .center{text-align:center;}
  .desc{ width:50%; }
  .tag{ border:1px solid var(--border); background:#fff; border-radius:999px; padding:2px 8px; font-size:12px; }

  .notes{ display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-top:16px; }
  .panel{ border:1px dashed var(--border); border-radius:12px; padding:12px 14px; background:#fcfdff; }
  .panel h4{ margin:0 0 8px; font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:.1em; }
  .panel p{ margin:0; white-space:pre-wrap; font-size:13px; }

  .side{ position:sticky; top:12px; height:fit-content; align-self:start; }
  .summary{ border:1px solid var(--border); border-radius:14px; padding:12px 14px; background:#fff; }
  .summary .row{ display:flex; justify-content:space-between; padding:8px 0; border-top:1px dashed var(--border); }
  .summary .row:first-child{ border-top:0; }
  .summary .grand{ font-weight:900; font-size:16px; }
  .brand{ display:flex; justify-content:flex-end; margin-top:12px; }
  .logo{ width:{{ ($theme['logoSize'] ?? 'md')==='lg'?'72px':(($theme['logoSize'] ?? 'md')==='sm'?'30px':'52px') }}; border-radius:12px; }
  @media print{ .canvas{ box-shadow:none; padding:16px; border-radius:0; } .side{ position:static; } }
  </style>
</div>
