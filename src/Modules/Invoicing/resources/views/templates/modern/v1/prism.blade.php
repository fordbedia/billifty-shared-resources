<div class="invoice-root scheme-{{ $scheme }} cat-{{ $category }}">
  <div class="page">
    <div class="banner">
      <div class="banner-inner">
        <div class="brand">
          @if(!empty($bp?->logo_path))
            <img src="{{ $bp->logo_path }}" alt="Business Logo" class="logo"/>
          @else
            <div class="logo placeholder"><span>{{ strtoupper(substr($bp?->name ?? 'B',0,1)) }}</span></div>
          @endif
          <div>
            <div class="kicker">Invoice</div>
            <h1 class="title">{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</h1>
          </div>
        </div>
        <div class="due">
          <div class="muted tiny">Issued {{ $fmtDate($invoice->issued_on ?? null) }}</div>
          <div class="duepill">Due {{ $fmtDate($invoice->due_on ?? null) }}</div>
        </div>
      </div>
      <div class="angle"></div>
    </div>

    <div class="grid2">
      <div class="tile">
        <div class="tile-h">From</div>
        <div class="tile-b">
          <div class="strong">{{ $bp?->name ?? 'Your Business' }}</div>
          <div class="muted">{{ $bp?->email }}@if($bp?->email && $bp?->phone) • @endif{{ $bp?->phone }}</div>
          <div class="muted">{{ $bp ? $addr($bp) : '' }}</div>
          @if($bp?->tax_id)<div class="muted">Tax ID: {{ $bp->tax_id }}</div>@endif
          @if($bp?->license_no)<div class="muted">License No: {{ $bp->license_no }}</div>@endif
        </div>
      </div>
      <div class="tile">
        <div class="tile-h">Bill To</div>
        <div class="tile-b">
          <div class="strong">{{ $cl?->name ?? $cl?->company ?? 'Client' }}</div>
          <div class="muted">{{ $cl?->email }}@if($cl?->email && $cl?->phone) • @endif{{ $cl?->phone }}</div>
          <div class="muted">{{ $cl ? $addr($cl) : '' }}</div>
          @if($cl?->tax_id)<div class="muted">Tax ID: {{ $cl->tax_id }}</div>@endif
          @if($cl?->license_no)<div class="muted">License No: {{ $cl->license_no }}</div>@endif
        </div>
      </div>
    </div>

    <div class="tablewrap">
      <table class="items">
        <thead>
          <tr>
            <th>Description</th><th class="right">Qty</th><th class="right">Rate</th><th class="right">Amount</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $it)
            <tr>
              <td>
                <div class="strong">{{ $it->name ?? 'Item' }}</div>
                @if(!empty($it->description))<div class="muted small">{{ $it->description }}</div>@endif
              </td>
              <td class="right">{{ rtrim(rtrim((string)($it->quantity ?? 0),'0'),'.') }}{{ $it->unit ? ' '.$it->unit : '' }}</td>
              <td class="right">{{ $fmtMoney($it->unit_price_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
              <td class="right">{{ $fmtMoney($it->line_total_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
            </tr>
          @empty
            <tr><td colspan="4" class="muted">No items.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="totals">
      <div></div>
      <div class="box">
        <div class="row"><span>Subtotal</span><span>{{ $fmtMoney($invoice->subtotal_cents ?? 0,$invoice->currency ?? 'USD') }}</span></div>
        @if((int)($invoice->discount_cents ?? 0)>0)<div class="row"><span>Discount</span><span>-{{ $fmtMoney($invoice->discount_cents ?? 0,$invoice->currency ?? 'USD') }}</span></div>@endif
        @if((int)($invoice->tax_cents ?? 0)>0)<div class="row"><span>Tax</span><span>{{ $fmtMoney($invoice->tax_cents ?? 0,$invoice->currency ?? 'USD') }}</span></div>@endif
        @if((int)($invoice->shipping_cents ?? 0)>0)<div class="row"><span>Shipping</span><span>{{ $fmtMoney($invoice->shipping_cents ?? 0,$invoice->currency ?? 'USD') }}</span></div>@endif
        <div class="row grand"><span>Total</span><span>{{ $fmtMoney($invoice->total_cents ?? 0,$invoice->currency ?? 'USD') }}</span></div>
      </div>
    </div>

    <div class="footer">
      <div class="fcard"><h4>Notes</h4><p>{{ $invoice->notes ?? '—' }}</p></div>
      <div class="fcard"><h4>Terms</h4><p>{{ $invoice->terms ?? '—' }}</p></div>
    </div>
  </div>

  <style>
    .invoice-root{--font: {{ $theme['fontFamily'] ?? "Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif" }};--ink:#0b1220;--muted:#6b7280;--border:#e5e7eb;--bg:#fff;--accent:#0ea5e9;--accent-ink:#fff;--grad1: #22d3ee;--grad2:#0ea5e9;}
    .scheme-forest{--accent:#16a34a;--grad1:#34d399;--grad2:#16a34a}
    .scheme-royal{--accent:#6d28d9;--grad1:#a78bfa;--grad2:#6d28d9}
    .scheme-crimson{--accent:#dc2626;--grad1:#fb7185;--grad2:#dc2626}
    .scheme-sunset{--accent:#f97316;--accent-ink:#111827;--grad1:#fb923c;--grad2:#f97316}

    .page{font-family:var(--font);width:816px;margin:0 auto;background:var(--bg);padding-bottom:24px;border-radius:16px;box-shadow:0 10px 28px rgba(2,6,23,.06)}
    .banner{position:relative;background:linear-gradient(120deg,var(--grad1),var(--grad2));color:var(--accent-ink);border-radius:16px 16px 0 0}
    .banner-inner{display:flex;justify-content:space-between;align-items:center;padding:18px 22px}
    .brand{display:flex;gap:12px;align-items:center}
    .logo{width:50px;height:50px;border-radius:10px;background:rgba(255,255,255,.15);object-fit:contain}
    .logo.placeholder{display:grid;place-items:center;font-weight:800}
    .kicker{opacity:.9;font-size:12px;letter-spacing:.08em;text-transform:uppercase}
    .title{margin:0;font-size:26px}
    .due{display:flex;flex-direction:column;gap:6px;align-items:flex-end}
    .tiny{font-size:12px}
    .muted{opacity:.9}
    .duepill{background:#fff;color:#111827;border-radius:999px;padding:6px 10px;font-weight:700}
    .angle{position:absolute;bottom:-18px;left:0;right:0;height:18px;background:linear-gradient(180deg,rgba(0,0,0,.08),transparent);filter:blur(6px);opacity:.3}

    .grid2{display:grid;grid-template-columns:1fr 1fr;gap:18px;padding:22px}
    .tile{border:1px solid var(--border);border-radius:12px;background:#fff}
    .tile-h{padding:10px 14px;border-bottom:1px solid var(--border);font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:#334155;background:linear-gradient(180deg,#f8fafc,#fff)}
    .tile-b{padding:14px}
    .strong{font-weight:600}

    .tablewrap{padding:0 22px}
    table.items{width:100%;border-collapse:collapse;margin-top:6px;border:1px solid var(--border);border-radius:12px;overflow:hidden}
    .items thead th{background:#0f172a;color:#e2e8f0;font-weight:600;font-size:12px;text-transform:uppercase;letter-spacing:.06em;text-align:left;padding:10px 12px}
    .items tbody td{padding:12px;border-top:1px solid var(--border)}
    .items tbody tr:nth-child(odd){background:#fafafa}
    .right{text-align:right}
    .small{font-size:12px;color:#64748b}

    .totals{display:grid;grid-template-columns:1fr 320px;gap:22px;padding:18px 22px 0}
    .box{border:2px solid var(--accent);border-radius:14px;background:#fff;padding:12px 14px}
    .row{display:flex;justify-content:space-between;padding:10px 0;border-top:1px dashed #e5e7eb}
    .row:first-child{border-top:0}
    .grand{font-weight:800;font-size:16px}

    .footer{display:grid;grid-template-columns:1fr 1fr;gap:18px;padding:18px 22px}
    .fcard{border:1px solid var(--border);border-radius:12px;padding:14px;background:#fff}
    .fcard h4{margin:0 0 6px 0;font-size:12px;color:#334155;text-transform:uppercase;letter-spacing:.08em}
    .fcard p{margin:0;white-space:pre-wrap}
  </style>
</div>
