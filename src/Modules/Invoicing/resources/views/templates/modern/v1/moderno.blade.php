<div class="invoice-root scheme cat-{{ $category }}">
  <div class="page">

    <div class="banner">
      <div class="banner-inner row justify-content-between mx-5">
        <div class="brand">
          @if(!empty($bp?->logo_path))
            <img src="{{ $bp->logo_path }}" alt="Business Logo" class="logo"/>
          @else
            <div class="logo placeholder"><span>{{ strtoupper(substr($bp?->name ?? 'B',0,1)) }}</span></div>
          @endif
          <div>
            <h1 class="title">{{ $bp?->name ?? 'Your Business' }}</h1>
          	<div class="muted">{{ $bp ? $addr($bp) : '' }}</div>
						<div class="muted">{{ $bp?->email }}</div>
						<div class="muted">@if($bp?->email && $bp?->phone) @endif{{ $bp?->phone }}</div>
          </div>
        </div>

        <div class="due">
					<h2>INVOICE</h2>
					<div class="due--bg">
						<div class="muted">Invoice: <strong>{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</strong></div>
						@if ($invoice->issued_on)<div class="muted tiny">Date {{ $fmtDate($invoice->issued_on ?? null) }}</div>@endif
						@if ($invoice->due_on)<div class="duepill">Due {{ $fmtDate($invoice->due_on ?? null) }}</div>@endif
					</div>
        </div>
      </div>
      <div class="angle"></div>
    </div>


    <div class="grid2">

{{--      <div class="tile">--}}
{{--        <div class="tile-h">Bill To</div>--}}
{{--        <div class="tile-b">--}}
{{--          <div class="strong"></div>--}}

{{--          @if($bp?->tax_id)<div class="muted">Tax ID: {{ $bp->tax_id }}</div>@endif--}}
{{--          @if($bp?->license_no)<div class="muted">License No: {{ $bp->license_no }}</div>@endif--}}
{{--        </div>--}}
{{--      </div>--}}

      <div class="tile">
        <div class="tile-h">
					<svg class="img-color" width="16" height="28" viewBox="0 0 16 28" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M15.75 28H0V0H15.75V28Z" stroke="#E5E7EB"/>
						<g clip-path="url(#clip0_146_126)">
						<path d="M7.875 13.25C6.68153 13.25 5.53693 12.7759 4.69302 11.932C3.84911 11.0881 3.375 9.94347 3.375 8.75C3.375 7.55653 3.84911 6.41193 4.69302 5.56802C5.53693 4.72411 6.68153 4.25 7.875 4.25C9.06847 4.25 10.2131 4.72411 11.057 5.56802C11.9009 6.41193 12.375 7.55653 12.375 8.75C12.375 9.94347 11.9009 11.0881 11.057 11.932C10.2131 12.7759 9.06847 13.25 7.875 13.25ZM7.35117 16.8781L6.69727 15.7883C6.47227 15.4121 6.74297 14.9375 7.17891 14.9375H7.875H8.56758C9.00352 14.9375 9.27422 15.4156 9.04922 15.7883L8.39531 16.8781L9.56953 21.234L10.8352 16.0695C10.9055 15.7848 11.1797 15.5984 11.4645 15.6723C13.9289 16.291 15.75 18.5199 15.75 21.1707C15.75 21.7684 15.2648 22.25 14.6707 22.25H10.0371C9.96328 22.25 9.89648 22.2359 9.8332 22.2113L9.84375 22.25H5.90625L5.9168 22.2113C5.85352 22.2359 5.7832 22.25 5.71289 22.25H1.0793C0.485156 22.25 0 21.7648 0 21.1707C0 18.5164 1.82461 16.2875 4.28555 15.6723C4.57031 15.602 4.84453 15.7883 4.91484 16.0695L6.18047 21.234L7.35469 16.8781H7.35117Z" fill="currentColor"/>
						</g>
						<defs>
						<clipPath id="clip0_146_126">
						<path d="M0 4.25H15.75V22.25H0V4.25Z" fill="white"/>
						</clipPath>
						</defs>
					</svg>
					Bill To</div>
        <div class="tile-b">
          <div class="strong">{{ $cl?->name ?? $cl?->company ?? 'Client' }}</div>
          <div class="muted">{{ $cl?->email }}@if($cl?->email && $cl?->phone) • @endif{{ $cl?->phone }}</div>
          @if($cl?->tax_id)<div class="muted">Tax ID: {{ $cl->tax_id }}</div>@endif
          @if($cl?->license_no)<div class="muted">License No: {{ $cl->license_no }}</div>@endif
					<div class="muted">{{ $cl ? $addr($cl) : '' }}</div>
        </div>
      </div>
    </div>

    <div class="tablewrap">
			<h2>
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<g clip-path="url(#clip0_146_58)">
					<path d="M5.94141 1.49204C6.32813 1.83969 6.35938 2.42954 6.01172 2.81626L3.19922 5.94126C3.02734 6.13266 2.78516 6.24594 2.52734 6.24985C2.26953 6.25376 2.02344 6.1561 1.83984 5.97641L0.273438 4.41391C-0.0898438 4.04673 -0.0898438 3.45298 0.273438 3.08579C0.636719 2.7186 1.23438 2.7186 1.59766 3.08579L2.46094 3.94907L4.61328 1.55844C4.96094 1.17173 5.55078 1.14048 5.9375 1.48813L5.94141 1.49204ZM5.94141 7.74204C6.32813 8.08969 6.35938 8.67954 6.01172 9.06626L3.19922 12.1913C3.02734 12.3827 2.78516 12.4959 2.52734 12.4999C2.26953 12.5038 2.02344 12.4061 1.83984 12.2264L0.273438 10.6639C-0.09375 10.2967 -0.09375 9.70298 0.273438 9.33969C0.640625 8.97641 1.23438 8.97251 1.59766 9.33969L2.46094 10.203L4.61328 7.81235C4.96094 7.42563 5.55078 7.39438 5.9375 7.74204H5.94141ZM8.75 3.74985C8.75 3.05844 9.30859 2.49985 10 2.49985H18.75C19.4414 2.49985 20 3.05844 20 3.74985C20 4.44126 19.4414 4.99985 18.75 4.99985H10C9.30859 4.99985 8.75 4.44126 8.75 3.74985ZM8.75 9.99985C8.75 9.30844 9.30859 8.74985 10 8.74985H18.75C19.4414 8.74985 20 9.30844 20 9.99985C20 10.6913 19.4414 11.2499 18.75 11.2499H10C9.30859 11.2499 8.75 10.6913 8.75 9.99985ZM6.25 16.2499C6.25 15.5584 6.80859 14.9999 7.5 14.9999H18.75C19.4414 14.9999 20 15.5584 20 16.2499C20 16.9413 19.4414 17.4999 18.75 17.4999H7.5C6.80859 17.4999 6.25 16.9413 6.25 16.2499ZM1.875 14.3749C2.37228 14.3749 2.84919 14.5724 3.20083 14.924C3.55246 15.2757 3.75 15.7526 3.75 16.2499C3.75 16.7471 3.55246 17.224 3.20083 17.5757C2.84919 17.9273 2.37228 18.1249 1.875 18.1249C1.37772 18.1249 0.900805 17.9273 0.549175 17.5757C0.197544 17.224 -1.11759e-08 16.7471 -1.11759e-08 16.2499C-1.11759e-08 15.7526 0.197544 15.2757 0.549175 14.924C0.900805 14.5724 1.37772 14.3749 1.875 14.3749Z" fill="currentColor"/>
					</g>
					<defs>
					<clipPath id="clip0_146_58">
					<path d="M0 0H20V20H0V0Z" fill="white"/>
					</clipPath>
					</defs>
				</svg>

				Invoice Items</h2>
      <table class="items">
        <thead>
          <tr>
            <th>Description</th>
						<th class="right">Qty</th>
						<th class="right">Rate</th>
						<th class="right">Amount</th>
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
      <div class="box col-12">
				<h2 class="row">Invoice Summary</h2>
        <div class="row"><span>Subtotal</span><span>{{ $fmtMoney($invoice->subtotal_cents ?? 0,$invoice->currency ?? 'USD') }}</span></div>
        @if((int)($invoice->discount_cents ?? 0)>0)<div class="row"><span class="label">Discount</span><span>-{{ $fmtMoney($invoice->discount_cents ?? 0,$invoice->currency ?? 'USD') }}</span></div>@endif
        @if((int)($invoice->tax_cents ?? 0)>0)<div class="row"><span class="label">Tax</span><span>{{ $fmtMoney($invoice->tax_cents ?? 0,$invoice->currency ?? 'USD') }}</span></div>@endif
        @if((int)($invoice->shipping_cents ?? 0)>0)<div class="row"><span class="label">Shipping</span><span>{{ $fmtMoney($invoice->shipping_cents ?? 0,$invoice->currency ?? 'USD') }}</span></div>@endif
        <div class="row grand"><span>Total Amount</span><span class="total">{{ $fmtMoney($invoice->total_cents ?? 0,$invoice->currency ?? 'USD') }}</span></div>
      </div>
    </div>

    <div class="footer">
      <div class="fcard"><h4>
					<svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
						<g clip-path="url(#clip0_146_230)">
						<path d="M2.25 1.125C1.00898 1.125 0 2.13398 0 3.375V14.625C0 15.866 1.00898 16.875 2.25 16.875H10.125V12.9375C10.125 12.0059 10.8809 11.25 11.8125 11.25H15.75V3.375C15.75 2.13398 14.741 1.125 13.5 1.125H2.25ZM15.75 12.375H14.1574H11.8125C11.5031 12.375 11.25 12.6281 11.25 12.9375V15.2824V16.875L12.375 15.75L14.625 13.5L15.75 12.375Z" fill="currentColor"/>
						</g>
						<defs>
						<clipPath id="clip0_146_230">
						<path d="M0 0H15.75V18H0V0Z" fill="white"/>
						</clipPath>
						</defs>
					</svg>
					Notes</h4><p>{{ $invoice->notes ?? '—' }}</p></div>
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
    .banner{position:relative;background-color: {{$scheme['main']['hex_color'] }}; color:var(--accent-ink);border-radius:16px 16px 0 0}
    .banner-inner{display:flex;justify-content:space-between;align-items:center;padding:18px 22px}
    .brand{display:flex;gap:12px;align-items:center}
    .logo{width:50px;height:50px;border-radius:10px;background:rgba(255,255,255,.15);object-fit:contain}
    .logo.placeholder{display:grid;place-items:center;font-weight:800}
    .kicker{opacity:.9;font-size:12px;letter-spacing:.08em;text-transform:uppercase}
    .title{margin:0;font-size:26px;}
    .due{display:flex;flex-direction:column;gap:6px;align-items:flex-end}
		.due--bg {background-color: {{$scheme['extra_light']['hex_color']}}; padding: 12px 12px 12px 12px; border-radius: 10px; }
    .tiny{font-size:12px}
    .muted{opacity:.9}
    .duepill{background:#fff;color:#111827;border-radius:999px;padding:6px 10px;font-weight:700}
    .angle{position:absolute;bottom:-18px;left:0;right:0;height:18px;background:linear-gradient(180deg,rgba(0,0,0,.08),transparent);filter:blur(6px);opacity:.3}
		.img-color {color: {{$scheme['main']['hex_color']}}; }

    .grid2{display:grid;grid-template-columns:1fr 1fr;gap:18px;padding:22px;background-color: #F9FAFB;}
    .tile{border-radius:12px;background:#fff}
    .tile-h{padding:10px 14px;font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:#334155;
			font-weight: bold;}
    .tile-b{padding:14px}
    .strong{font-weight:600}

    .tablewrap{padding:0 22px}
		.tablewrap h2 {font-size: 20px;}
		.tablewrap h2 svg {color: {{$scheme['main']['hex_color']}} }
    table.items{width:100%;border-collapse:collapse;margin-top:6px;border:1px solid var(--border);border-radius:12px;}
    .items thead th{background:#F9FAFB;color:#374151;font-weight:600;font-size:12px;text-transform:uppercase;letter-spacing:.06em;text-align:left;padding:10px 12px}
		table thead th{border-radius: 12px;}
    .items tbody td{padding:12px;border-top:1px solid var(--border)}
    .items tbody tr:nth-child(odd){background:#fafafa}
    .right{text-align:right}
    .small{font-size:12px;color:#64748b}

    .totals{display:grid;grid-template-columns:1fr 400px;gap:22px;padding:18px 22px 0}
		.totals h2{font-size: 18px;}
		.totals .label {font-size: 16px;}
    .box{border:2px solid #F3F4F6;border-radius:14px;background:#fff;padding: 16px 30px;}
    .row{display:flex;justify-content:space-between;padding:10px 0;border-top:1px dashed #e5e7eb}
    .row:first-child{border-top:0}
    .grand{font-weight:800;font-size:24px}
		.grand .total {color: {{$scheme['main']['hex_color']}};}

    .footer{display:grid;grid-template-columns:1fr 1fr;gap:18px;padding:18px 22px}
    .fcard{border:1px solid var(--border);border-radius:12px;padding:14px;background:#fff}
    .fcard h4{margin:0 0 6px 0;font-size:12px;color:#334155;text-transform:uppercase;letter-spacing:.08em}
    .fcard p{margin:0;white-space:pre-wrap}
  </style>
</div>
