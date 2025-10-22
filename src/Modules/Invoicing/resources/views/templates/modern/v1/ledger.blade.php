<div class="ledger-root scheme-{{ $scheme }} cat-{{ $categoryName }}">
  <div class="wrap">
    <div class="header">
      <div class="side">
        <div class="eyebrow">Invoice</div>
        <h1 class="id">{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</h1>
        <div class="chips">
          <span class="chip">Issued {{ $fmtDate($invoice->issued_on ?? null) }}</span>
          <span class="chip accent">Due {{ $fmtDate($invoice->due_on ?? null) }}</span>
        </div>
      </div>
      <div class="org">
        <div class="strong">{{ $bp?->name ?? 'Your Business' }}</div>
        <div class="muted tiny">{{ $bp?->email }}@if($bp?->email && $bp?->phone) • @endif{{ $bp?->phone }}</div>
        <div class="muted tiny">{{ $bp ? $addr($bp) : '' }}</div>
        @if($bp?->tax_id)<div class="muted tiny">Tax ID: {{ $bp->tax_id }}</div>@endif
        @if($bp?->license_no)<div class="muted tiny">License No: {{ $bp->license_no }}</div>@endif
      </div>
    </div>

    <div class="to">
      <div class="box">
        <div class="label tiny">Bill To</div>
        <div class="strong">{{ $cl?->name ?? $cl?->company ?? 'Client' }}</div>
        <div class="muted tiny">{{ $cl?->email }}@if($cl?->email && $cl?->phone) • @endif{{ $cl?->phone }}</div>
        <div class="muted tiny">{{ $cl ? $addr($cl) : '' }}</div>
        @if($cl?->tax_id)<div class="muted tiny">Tax ID: {{ $cl->tax_id }}</div>@endif
        @if($cl?->license_no)<div class="muted tiny">License No: {{ $cl->license_no }}</div>@endif
      </div>
      @if(($bp?->logo_path))
        <img src="{{ $bp->logo_path }}" class="logo" alt="logo" />
      @endif
    </div>

    <div class="gridcard">
      <table class="gridtbl">
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
                  @if(!empty($it->description))<div class="muted xsmall">{{ $it->description }}</div>@endif
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

    <div class="totals">
      <div class="pad"></div>
      <div class="sum">
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

    <div class="foot">
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
  .ledger-root{
    --font: {{ $theme['fontFamily'] ?? "Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif" }};
    --ink:#0f172a; --muted:#64748b; --bg:#fff; --accent:#0ea5e9; --accent-ink:#fff; --grid:#eef2f7; --border:#e5e7eb;
  }
  .scheme-forest.ledger-root{ --accent:#16a34a; }
  .scheme-royal.ledger-root{ --accent:#6d28d9; }
  .scheme-crimson.ledger-root{ --accent:#dc2626; }
  .scheme-sunset.ledger-root{ --accent:#f97316; --accent-ink:#111827; }

  .wrap{ background:#fff; border-radius:16px; box-shadow:0 10px 32px rgba(2,6,23,.07); padding:22px; font-family:var(--font); color:var(--ink); }
  .eyebrow{ color:var(--muted); text-transform:uppercase; letter-spacing:.1em; font-size:12px; }
  .id{ margin:2px 0 6px; font-size:26px; font-weight:800; letter-spacing:.3px; }
  .header{ display:flex; justify-content:space-between; align-items:flex-start; gap:18px; }
  .chips{ display:flex; gap:8px; flex-wrap:wrap; }
  .chip{ border:1px solid var(--border); border-radius:999px; padding:4px 10px; font-size:12px; background:#fff; color:#0b1220; }
  .chip.accent{ border:none; background:var(--accent); color:var(--accent-ink); }
  .org{ text-align:right; }
  .strong{ font-weight:700; } .muted{ color:var(--muted); } .tiny{ font-size:12px; } .xsmall{ font-size:11px; }
  .to{ display:flex; justify-content:space-between; align-items:center; margin-top:16px; }
  .box .label{ color:var(--muted); text-transform:uppercase; letter-spacing:.12em; }
  .logo{ width:{{ ($theme['logoSize'] ?? 'md')==='lg'?'70px':(($theme['logoSize'] ?? 'md')==='sm'?'28px':'44px') }}; border-radius:10px; }

  .gridcard{ margin-top:18px; border:1px solid var(--border); border-radius:14px; overflow:hidden; }
  .gridtbl{ width:100%; border-collapse:separate; border-spacing:0; background:
    linear-gradient(#fff, #fff) padding-box,
    repeating-linear-gradient(0deg, var(--grid), var(--grid) 1px, transparent 1px, transparent 32px) border-box; }
  .gridtbl thead th{ background:var(--accent); color:var(--accent-ink); padding:10px 12px; font-size:12px; text-align:left; }
  .gridtbl tbody td{ padding:12px; border-top:1px solid var(--border); font-size:13px; }
  .qty{ background:#fff; border:1px dashed var(--border); padding:2px 8px; border-radius:8px; font-size:12px; }
  .right{text-align:right;} .center{text-align:center;}
  .desc{ width:48%; }

  .totals{ display:grid; grid-template-columns:1fr 280px; gap:16px; margin-top:14px; }
  .sum{ border:1px solid var(--border); border-radius:12px; padding:12px 14px; background:#fff; }
  .sum .row{ display:flex; justify-content:space-between; padding:8px 0; border-top:1px dashed var(--border); }
  .sum .row:first-child{ border-top:0; }
  .grand{ font-size:16px; font-weight:900; }

  .foot{ display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-top:16px; }
  .panel{ border:1px dashed var(--border); border-radius:12px; padding:12px 14px; background:#fcfdff; }
  .panel h4{ margin:0 0 8px; font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:.1em; }
  .panel p{ margin:0; white-space:pre-wrap; font-size:13px; }
  @media print{ .wrap{ box-shadow:none; padding:18px; border-radius:0; } }
  </style>
</div>
