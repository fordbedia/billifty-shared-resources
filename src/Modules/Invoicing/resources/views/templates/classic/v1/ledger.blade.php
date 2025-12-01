@php
  $fontFamily = $theme->fontFamily ?? "DejaVu Sans, Arial, sans-serif";
  $ink        = '#0f172a';
  $muted      = '#64748b';
  $bg         = '#ffffff';
  $accent     = $scheme->main->code;
  $accentInk  = ($category ?? 'ocean') === 'sunset' ? '#111827' : '#ffffff';
  $grid       = '#eef2f7';
  $border     = '#e5e7eb';

  $logoW = 120;
@endphp

<div class="ledger--theme ledger-root scheme cat">
  <div class="wrap">
    <div class="header clearfix">
		<table class="dompdf-table">
		<tr>
      <td class="side">
        <div class="eyebrow">Business Profile</div>
        <h1 class="id">{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</h1>
        <div class="chips">
          <span class="chip">Issued {{ $fmtDate($invoice->issued_on ?? null) }}</span>
          <span class="chip accent">Due {{ $fmtDate($invoice->due_on ?? null) }}</span>
        </div>
      </td>
      <td class="org dompdf-col">
		  @if($logoSrc)
			  <div class="clearfix">
				  <img src="{{ $logoSrc }}" class="logo" alt="logo" />
			  </div>
		  @endif
        <div class="strong">{{ $bp?->name ?? 'Your Business' }}</div>
        <div class="muted tiny">Email: <strong>{{ $bp?->email }}</strong></div>
		@if ($bp?->phone) <div class="muted tiny">Phone: <strong>{{$bp?->phone}}</strong></div>@endif
			  @if ($bp->address_line1)<div class="muted tiny">Address: <strong>{{ $bp->address_line1  }}</strong></div>@endif
			  @if ($bp->address_line2)<div class="muted tiny">Address 2: <strong>{{ $bp->address_line2  }}</strong></div>@endif
			  @if($bp?->tax_id)<div class="muted tiny">Tax ID: <strong>{{ $bp->tax_id }}</strong></div>@endif
			  @if($bp?->license_no)<div class="muted tiny">License No: <strong>{{ $bp->license_no }}</strong></div>@endif
      </td>
		</tr>
	</table>
    </div>

	<table class="dompdf-table">
		<tr>
		@if ($pi)<td class="dompdf-col to">
		  <div class="box paymentinfo-box">
			<div class="label tiny">Payment Information</div>
			{!! $paymentInfo($pi) !!}
		  </div>
		</td>@endif
		<td class="dompdf-col to">
		  <div class="box billto-box">
			<div class="label tiny">Bill To</div>
			  <ul>
				<li><span class="label">Name: </span><span class="value">{{ $cl?->name ?? 'Client' }}</span></li>
				@if ($cl?->company)<li><span class="label">Company: </span><span class="value">{{ $cl->company }}</span></li>@endif
				@if ($cl?->email)<li><span class="label">Email: </span><span class="value">{{ $cl?->email }}</span></li>@endif
				@if ($cl?->phone)<li><span class="label">Phone: </span><span class="value">{{ $cl?->phone }}</span></li>@endif
				@if ($cl->address_line1)<li><span class="label">Address: </span><span class="value">{{ $cl->address_line1 }}</span></li>@endif
				@if ($cl->address_line2)<li><span class="label">Address 2: </span><span class="value">{{ $c->address_line2 }}</span></li>@endif
				@if($cl?->tax_id)<li><span class="label">Tax ID: </span><span class="value">{{ $cl->tax_id }}</span></li>@endif
				@if($cl?->license_no)<li><span class="label">License No: </span><span class="value">{{ $cl->license_no }}</span></li>@endif
			</ul>
		  </div>
		</td>
		</tr>
	</table>

    <div class="gridcard">
      <table class="gridtbl" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="desc">Description</th>
            <th>Qty</th>
            <th>Unit Price</th>
			<th>Tax</th>\
			@if ($invoice->discount_mode === 'per-line')<th>Discount</th>@endif
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
				<td>{{ $fmtMoney($it->tax_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
				@if ($invoice->discount_mode === 'per-line')<td><strong>{{ $fmtPercent($it->line_discount_rate) }}</strong></td>@endif
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
        <div class="row">
			<span class="left">Subtotal</span>
			<span class="right">{{ $fmtMoney($invoice->subtotal_cents ?? 0, $invoice->currency ?? 'USD') }}</span>
		</div>
        @if((int)($invoice->discount_cents ?? 0) > 0)
          <div class="row">
			  <span class="left">Discount</span>
			  <span class="right">-{{ $fmtMoney($invoice->discount_cents ?? 0, $invoice->currency ?? 'USD') }}</span>
		  </div>
        @endif
        @if((int)($invoice->tax_cents ?? 0) > 0)
          <div class="row">
			  <span class="left">Tax</span>
			  <span class="right">{{ $fmtMoney($invoice->tax_cents ?? 0, $invoice->currency ?? 'USD') }}</span>
		  </div>
        @endif
        @if((int)($invoice->shipping_cents ?? 0) > 0)
          <div class="row">
			  <span class="left">Shipping</span>
			  <span class="right">{{ $fmtMoney($invoice->shipping_cents ?? 0, $invoice->currency ?? 'USD') }}</span>
		  </div>
        @endif
        <div class="row grand">
			<span class="left">Total</span>
			<span class="right">{{ $fmtMoney($invoice->total_cents ?? 0, $invoice->currency ?? 'USD') }}</span>
		</div>
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
		{!! $watermark() !!}
  </div>

  <style>
    .wrap{ width: 100%;              /* 👈 fill the .invoice-page container */
    max-width: 100%;          /* 👈 don't overflow horizontally */
    box-sizing: border-box;background:#fff; border-radius:16px; padding:22px; font-family: {{ $fontFamily }}; color: {{ $ink }}; box-shadow: 0 4px 14px rgba(2,6,23,0.07); }
    .eyebrow{ color: {{ $muted }}; text-transform:uppercase; letter-spacing:.1em; font-size:12px; }
    .id{ margin:2px 0 6px; font-size:26px; font-weight:800; letter-spacing:.3px; }
		.org .strong {font-weight: bold; font-size: 20px;}

    /* Header (floats instead of flex) */
	.header {margin-bottom: 12px;}
    .header.clearfix:after, .to.clearfix:after, .totals.clearfix:after, .foot.clearfix:after, .clearfix:after { content:""; display:table; clear:both; }
    .header .side{ width:40%; }
    .header .org{ width:40%; text-align:right; }
	.header .org .muted {line-height: 22px;font-size: 15px;}
	.totals {margin: 20px 0;}

    /* Chips (inline-block instead of flex) */
    .chips{ margin-top:6px; }
    .chip{ border:1px solid {{ $border }}; border-radius:999px; padding:4px 10px; font-size:12px; background:#fff; color:#0b1220; display:inline-block; margin-right:6px; }
    .chip.accent{ border:none; background: {{ $accent }}; color: {{ $accentInk }}; }

    /* Bill To row */
    .to .box{border:1px solid {{ $border }}; border-radius:12px; padding:12px 14px; background:#fff;}
    .to .box .label{ color: {{ $muted }}; text-transform:uppercase; letter-spacing:.12em; }
    .logo{ width: {{ $logoW }}px; border-radius:10px; }
		.to .box .muted {line-height: 22px;}
	.to .box .paymentinfo {list-style: none;padding-left: 0;}
	.to .box.billto-box {margin-left: 12px;}
	.to .box.billto-box ul {padding-left: 0;list-style: none;}
	.to .box.paymentinfo-box {margin-right: 12px;}

    /* Items table */
    .gridcard{ margin-top:18px; border:1px solid {{ $border }}; border-radius:14px; overflow:hidden; }
    .gridtbl{ width:100%; border-collapse:collapse; }
    .gridtbl thead th{ background: {{ $accent }}; color: {{ $accentInk }}; padding:10px 12px; font-size:12px; text-align:left; }
    .gridtbl tbody td{ padding:12px; border-top:1px solid {{ $border }}; font-size:13px; }
		.gridtbl tbody td .strong {font-weight: bold;}
		.gridtbl tbody td .muted {line-height: 17px;}
    .qty{ background:#fff; border:1px dashed {{ $border }}; padding:2px 8px; border-radius:8px; font-size:12px; display:inline-block; }
    .right{text-align:right;} .center{text-align:center;}
    .desc{ width:48%; }

    /* Totals (floats instead of grid) */
    .totals .pad{ float:left; width:60%; }
    .totals .sum{ float:right; width:38%; border:1px solid {{ $border }}; border-radius:12px; padding:12px 14px; background:#fff; box-sizing:border-box; }
    .sum .row{ padding:20px 0; border-top:1px dashed {{ $border }}; display:block; }
    .sum .row:first-child{ border-top:0; }
    /*.sum .row span:first-child{ float:left; }*/
    /*.sum .row span:last-child{ float:right; }*/
    .grand{ font-size:16px; font-weight:900; }

    /* Foot (two columns via floats) */
    .col-6{ float:left; width:50%; box-sizing:border-box; }
    .foot .panel{width: 280px;max-width: 280px; min-height: 80px; border:1px dashed {{ $border }}; border-radius:12px; padding:12px 14px; background:#fcfdff; }
    .foot .panel:first-child{ padding-right:9px; }
    .foot .panel:last-child{ padding-left:9px; }
    .panel h4{ margin:0 0 8px; font-size:12px; color: {{ $muted }}; text-transform:uppercase; letter-spacing:.1em; }
    .panel p{ margin:0; white-space:pre-wrap; font-size:13px; }
		.watermark{text-align: center;font-size: 15px; font-weight: bold;margin-top: 50px;}

    @media print{ .wrap{ box-shadow:none; padding:18px; border-radius:0; } }
  </style>
</div>
