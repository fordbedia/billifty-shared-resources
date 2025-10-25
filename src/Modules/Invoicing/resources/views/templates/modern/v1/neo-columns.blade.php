<div class="neo-root scheme cat-{{ $category }}">
	 <div class="canvas header">
		 <div class="container row">
      <div class="left col-6">
				<div class="brand">
					@if(!empty($bp?->logo_path))
						<img src="{{ $bp->logo_path }}" alt="Business Logo" class="logo"/>
					@else
						<div class="logo placeholder"><span>{{ strtoupper(substr($bp?->name ?? 'B',0,1)) }}</span></div>
					@endif
					<div class="business-profile">
						<h1 class="title">{{ $bp?->name ?? 'Your Business' }}</h1>
						<div class="muted">
							<svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g clip-path="url(#clip0_171_219)">
								<path d="M6.74062 15.6C8.34375 13.5938 12 8.73125 12 6C12 2.6875 9.3125 0 6 0C2.6875 0 0 2.6875 0 6C0 8.73125 3.65625 13.5938 5.25938 15.6C5.64375 16.0781 6.35625 16.0781 6.74062 15.6ZM6 4C6.53043 4 7.03914 4.21071 7.41421 4.58579C7.78929 4.96086 8 5.46957 8 6C8 6.53043 7.78929 7.03914 7.41421 7.41421C7.03914 7.78929 6.53043 8 6 8C5.46957 8 4.96086 7.78929 4.58579 7.41421C4.21071 7.03914 4 6.53043 4 6C4 5.46957 4.21071 4.96086 4.58579 4.58579C4.96086 4.21071 5.46957 4 6 4Z" fill="currentColor"/>
								</g>
								<defs>
								<clipPath id="clip0_171_219">
								<path d="M0 0H12V16H0V0Z" fill="white"/>
								</clipPath>
								</defs>
							</svg>
							{{ $bp ? $addr($bp) : '' }}</div>
						<div class="muted">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g clip-path="url(#clip0_171_228)">
								<path d="M1.5 2C0.671875 2 0 2.67188 0 3.5C0 3.97187 0.221875 4.41562 0.6 4.7L7.4 9.8C7.75625 10.0656 8.24375 10.0656 8.6 9.8L15.4 4.7C15.7781 4.41562 16 3.97187 16 3.5C16 2.67188 15.3281 2 14.5 2H1.5ZM0 5.5V12C0 13.1031 0.896875 14 2 14H14C15.1031 14 16 13.1031 16 12V5.5L9.2 10.6C8.4875 11.1344 7.5125 11.1344 6.8 10.6L0 5.5Z" fill="currentColor"/>
								</g>
								<defs>
								<clipPath id="clip0_171_228">
								<rect width="16" height="16" fill="white"/>
								</clipPath>
								</defs>
							</svg>
							{{ $bp?->email }}</div>
						<div class="muted">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M5.15312 0.768966C4.9125 0.187716 4.27812 -0.121659 3.67188 0.0439663L0.921875 0.793966C0.378125 0.943966 0 1.43772 0 2.00022C0 9.73147 6.26875 16.0002 14 16.0002C14.5625 16.0002 15.0563 15.6221 15.2063 15.0783L15.9563 12.3283C16.1219 11.7221 15.8125 11.0877 15.2312 10.8471L12.2312 9.59709C11.7219 9.38459 11.1313 9.53147 10.7844 9.95959L9.52188 11.5002C7.32188 10.4596 5.54063 8.67834 4.5 6.47834L6.04063 5.21897C6.46875 4.86897 6.61562 4.28147 6.40312 3.77209L5.15312 0.772091V0.768966Z" fill="currentColor"/>
							</svg>
							{{ $bp?->phone }}</div>
					</div>
				</div>
      </div>

      <div class="left col-6">
				<h2 class="text-right">INVOICE</h2>
       	<div class="box px-4 py-4 clearfix">
					<div class="inner-box">
						<div class="label">Invoice Number</div>
						<div class="value">{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</div>

						<div class="label">Issue Date</div>
						<div class="value">{{ $fmtDate($invoice->issued_on ?? null) }}</div>

						<div class="label">Due Date</div>
						<div class="value">{{ $fmtDate($invoice->due_on ?? null) }}</div>
					</div>
				</div>
      </div>
    </div>
	 </div>



</div>
<!-- End -->

<div class="neo-root scheme cat-{{ $category }}">
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
    --ink:#111827; --muted:#fff; --bg:#fff; --border:#e5e7eb; --stripe:#fafafa;
    --accent:#0ea5e9; --accent-ink:#fff;
  }
	body {
		font-family: var(--font);
	}
  .scheme-forest.neo-root{ --accent:#16a34a; }
  .scheme-royal.neo-root{ --accent:#6d28d9; }
  .scheme-crimson.neo-root{ --accent:#dc2626; }
  .scheme-sunset.neo-root{ --accent:#f97316; --accent-ink:#111827; }
	.business-profile {margin-left: 12px;}
	.inner-box{float: right;text-align: right; padding-right: 20px;}
  .canvas{ background:#fff; border-radius:18px; box-shadow:0 12px 34px rgba(2,6,23,.08); padding:22px; font-family:var(--font); color:var(--ink); }
  .header{ background-color: {{$scheme['main']['hex_color']}}; background: linear-gradient({{$scheme['gradient_bg_1']['hex_color']}}); border-bottom:1px solid var(--border); padding-bottom:12px; }
  .eyebrow{ color:var(--muted); text-transform:uppercase; letter-spacing:.12em; font-size:12px; }
  .num{ font-size:28px; margin:2px 0 6px; letter-spacing:.2px; font-weight:800; }
  .right .due{ font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:.12em; }
  .right .dueval{ background:var(--accent); color:var(--accent-ink); padding:6px 10px; border-radius:10px; font-weight:800; margin-top:6px; text-align:right; }
	.left h2 {color: #FFFFFF;}

  .columns{ display:grid; grid-template-columns:1fr 320px; gap:18px; margin-top:16px; }
  .split{ display:grid; grid-template-columns:1fr 1fr; gap:16px; }
  .info .k{ color:var(--muted); text-transform:uppercase; letter-spacing:.12em; margin-bottom:4px; }
  .strong{ font-weight:700; } .muted{ color:var(--muted); } .tiny{ font-size:12px; } .small{ font-size:12px; }

  .card.table{ border:1px solid var(--border); border-radius:14px; overflow:hidden; margin-top:16px; }
  table.grid{ width:100%; border-collapse:separate; border-spacing:0; }
  table.grid thead th{ background:#fff; position:sticky; top:0; text-align:left; font-size:12px; padding:12px; border-bottom:1px solid var(--border); }
  table.grid tbody td{ padding:12px; border-top:1px solid var(--border); font-size:13px; }
  table.grid tbody tr:nth-child(odd) td{ background:var(--stripe); }
  .left{text-align:left;} .center{text-align:center;}
	.left .box .value {font-size:20px; font-weight: bold;}
	.left .box .label {font-size: 14px;}
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
  .brand{ display:flex;margin-top:12px; }
	.brand h1 {color: #FFFFFF; font-family: var(--font)}
	.brand .muted {padding: 4px 0 7px 0;}
	.logo{width:50px;height:50px;border-radius:10px;background:rgba(255,255,255,.15);object-fit:contain}
	.logo.placeholder{display:grid;place-items:center;font-weight:800; background-color: {{$scheme['light']['hex_color']}};}
  .logo{ width:{{ ($theme['logoSize'] ?? 'md')==='lg'?'72px':(($theme['logoSize'] ?? 'md')==='sm'?'30px':'52px') }}; border-radius:12px; }
	.box {background-color: {{$scheme['extra_light']['hex_color']}};color: #FFFFFF;border-radius: 12px;margin-top: 10px;}
  @media print{ .canvas{ box-shadow:none; padding:16px; border-radius:0; } .side{ position:static; } }
  </style>
</div>
