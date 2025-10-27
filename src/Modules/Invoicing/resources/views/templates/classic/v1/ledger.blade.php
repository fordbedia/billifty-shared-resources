@php
  $fontFamily = $theme['fontFamily'] ?? "DejaVu Sans, Arial, sans-serif";
  $ink        = '#0f172a';
  $muted      = '#64748b';
  $bg         = '#ffffff';
  $accent     = match($category ?? 'ocean') {
    'forest'  => '#16a34a',
    'royal'   => '#6d28d9',
    'crimson' => '#dc2626',
    'sunset'  => '#f97316',
    default   => '#0ea5e9',
  };
  $accentInk  = ($category ?? 'ocean') === 'sunset' ? '#111827' : '#ffffff';
  $grid       = '#eef2f7';
  $border     = '#e5e7eb';

  $logoW = 150;
@endphp

<div class="ledger-root scheme cat-{{ $category }}">
  <div class="wrap">
    <div class="header clearfix">
      <div class="side">
        <div class="eyebrow">Invoice</div>
        <h1 class="id">{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</h1>
        <div class="chips">
          <span class="chip">Issued {{ $fmtDate($invoice->issued_on ?? null) }}</span>
          <span class="chip accent">Due {{ $fmtDate($invoice->due_on ?? null) }}</span>
        </div>
      </div>
      <div class="org">
				 @if(($bp?->logo_path))
					<div class="clearfix">
						<img src="{{ $bp->logo_path }}" class="logo" alt="logo" />
					</div>
				@endif
        <div class="strong">{{ $bp?->name ?? 'Your Business' }}</div>
        <div class="muted tiny">{{ $bp?->email }}</div>
				@if ($bp?->phone) <div class="muted tiny">{{$bp?->phone}}</div>@endif
        <div class="muted tiny">{{ $bp ? $addr($bp) : '' }}</div>
        @if($bp?->tax_id)<div class="muted tiny">Tax ID: {{ $bp->tax_id }}</div>@endif
        @if($bp?->license_no)<div class="muted tiny">License No: {{ $bp->license_no }}</div>@endif
      </div>
    </div>

    <div class="to clearfix">
      <div class="box">
        <div class="label tiny">Bill To</div>
        <div class="strong">{{ $cl?->name ?? $cl?->company ?? 'Client' }}</div>
        <div class="muted tiny">{{ $cl?->email }}</div>
				@if ($cl?->phone)<div class="muted tiny">{{ $cl?->phone }}</div>@endif
        <div class="muted tiny">{{ $cl ? $addr($cl) : '' }}</div>
        @if($cl?->tax_id)<div class="muted tiny">Tax ID: {{ $cl->tax_id }}</div>@endif
        @if($cl?->license_no)<div class="muted tiny">License No: {{ $cl->license_no }}</div>@endif
      </div>
    </div>

    <div class="gridcard">
      <table class="gridtbl" cellspacing="0" cellpadding="0">
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
                  @if(!empty($it->description))<div class="muted xsmall">{{ $it->description }}</div>@endif
                </td>
                <td><span class="qty">{{ rtrim(rtrim((string)($it->quantity ?? 0), '0'), '.') }}{{ $it->unit ? ' '.$it->unit : '' }}</span></td>
                <td>{{ $fmtMoney($it->unit_price_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
                <td>{{ $fmtMoney($it->line_total_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>

    <div class="totals clearfix">
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

    <div class="foot clearfix">
      <div class="panel left">
        <h4>Notes</h4>
        <p>{{ $invoice->notes ?? '—' }}</p>
      </div>
      <div class="panel right">
        <h4>Terms</h4>
        <p>{{ $invoice->terms ?? '—' }}</p>
      </div>
      <div class="clearfix"></div>
    </div>
		<div class="watermark">Powered by Billifty.com</div>
  </div>

  <style>
    .wrap{width: 900px;background:#fff; border-radius:16px; padding:22px; font-family: {{ $fontFamily }}; color: {{ $ink }}; box-shadow: 0 4px 14px rgba(2,6,23,0.07); }
    .eyebrow{ color: {{ $muted }}; text-transform:uppercase; letter-spacing:.1em; font-size:12px; }
    .id{ margin:2px 0 6px; font-size:26px; font-weight:800; letter-spacing:.3px; }
		.org .strong {font-weight: bold; font-size: 20px;}

    /* Header (floats instead of flex) */
    .header.clearfix:after, .to.clearfix:after, .totals.clearfix:after, .foot.clearfix:after, .clearfix:after { content:""; display:table; clear:both; }
    .header .side{ float:left; width:65%; }
    .header .org{ float:right; width:33%; text-align:right; }
		.totals {margin: 20px 0;}

    /* Chips (inline-block instead of flex) */
    .chips{ margin-top:6px; }
    .chip{ border:1px solid {{ $border }}; border-radius:999px; padding:4px 10px; font-size:12px; background:#fff; color:#0b1220; display:inline-block; margin-right:6px; }
    .chip.accent{ border:none; background: {{ $accent }}; color: {{ $accentInk }}; }

    /* Bill To row */
    .to .box{ float:left; width:50%; border:1px solid {{ $border }}; border-radius:12px; padding:12px 14px; box-sizing:border-box; background:#fff; }
    .to .box .label{ color: {{ $muted }}; text-transform:uppercase; letter-spacing:.12em; }
    .logo{ float:right; width: {{ $logoW }}px; border-radius:10px; }

    /* Items table */
    .gridcard{ margin-top:18px; border:1px solid {{ $border }}; border-radius:14px; overflow:hidden; }
    .gridtbl{ width:100%; border-collapse:collapse; }
    .gridtbl thead th{ background: {{ $accent }}; color: {{ $accentInk }}; padding:10px 12px; font-size:12px; text-align:left; }
    .gridtbl tbody td{ padding:12px; border-top:1px solid {{ $border }}; font-size:13px; }
    .qty{ background:#fff; border:1px dashed {{ $border }}; padding:2px 8px; border-radius:8px; font-size:12px; display:inline-block; }
    .right{text-align:right;} .center{text-align:center;}
    .desc{ width:48%; }

    /* Totals (floats instead of grid) */
    .totals .pad{ float:left; width:60%; }
    .totals .sum{ float:right; width:38%; border:1px solid {{ $border }}; border-radius:12px; padding:12px 14px; background:#fff; box-sizing:border-box; }
    .sum .row{ padding:8px 0; border-top:1px dashed {{ $border }}; display:block; }
    .sum .row:first-child{ border-top:0; }
    .sum .row span:first-child{ float:left; }
    .sum .row span:last-child{ float:right; }
    .grand{ font-size:16px; font-weight:900; }

    /* Foot (two columns via floats) */
    .col-6{ float:left; width:50%; box-sizing:border-box; }
    .foot .panel{width: 400px;border:1px dashed {{ $border }}; border-radius:12px; padding:12px 14px; background:#fcfdff; }
    .foot .panel:first-child{ padding-right:9px; }
    .foot .panel:last-child{ padding-left:9px; }
    .panel h4{ margin:0 0 8px; font-size:12px; color: {{ $muted }}; text-transform:uppercase; letter-spacing:.1em; }
    .panel p{ margin:0; white-space:pre-wrap; font-size:13px; }
		.watermark{width: 300px;margin: 40px auto 0; font-weight: bold;}

    @media print{ .wrap{ box-shadow:none; padding:18px; border-radius:0; } }
  </style>
</div>
