<div class="moderno--theme invoice-root scheme cat">
  <div class="page">

   <div class="banner">
	  <table class="dompdf-table">
		<tr>
			@if($logoSrc)
				<td class="dompdf-col logo-div">
					<img
						src="{{ $logoSrc }}"
						alt="Business Logo"
						class="logo"
					/>
				</td>
			@endif
			<td class="brand dompdf-col">
			  <div class="info-div">
				  <div class="">
					<h1 class="title">{{ $bp?->name ?? 'Your Business' }}</h1>
					@if ($bp->address_line1)<div class="muted">{{ $bp->address_line1 }}</div>@endif
					@if($bp?->email)<div class="muted">{{ $bp?->email }}</div>@endif
					@if($bp?->phone)<div class="muted">
					  {{ $bp?->phone }}
					</div>@endif
				  </div>
			  </div>
			</td>


		{{-- Right side: Invoice Info --}}
		<td class="due dompdf-col">
		  <h2 class="text-right">INVOICE</h2>
		  <div class="due--bg text-right">
			<div class="muted">
			  Invoice #:
			  <strong>{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</strong>
			</div>
			@if ($invoice->issued_on)
			  <div class="muted tiny">Date {{ $fmtDate($invoice->issued_on ?? null) }}</div>
			@endif
			@if ($invoice->due_on)
			  <div class="duepill">Due {{ $fmtDate($invoice->due_on ?? null) }}</div>
			@endif
		  </div>
		</td>
		</tr>

	  </table>
	</div>

	  <div class="grid2 clearfix">
		<table class="dompdf-table">
			<tr>
				@if ($pi)<td class="dompdf-col">
					<div class="tile tile-b">
						<div class="billto-text">Payment Information:</div>
						{!! $paymentInfo($pi) !!}
					</div>
				</td>@endif
				<td class="dompdf-col">
					<div class="tile tile-b">
						<div class="billto-text">Bill To:</div>
					  <div class="strong">{{ $cl?->name ?? 'Client' }}</div>
						@if ($cl?->company)<div class="muted">{{$cl->company}}</div>@endif
						@if ($cl?->email)<div class="muted">{{ $cl->email }}</div>@endif
						@if ($cl?->phone )<div class="muted">{{ $cl->phone }}</div>@endif
						@if($cl?->tax_id)<div class="muted">Tax ID: {{ $cl->tax_id }}</div>@endif
						@if($cl?->license_no)<div class="muted">License No: {{ $cl->license_no }}</div>@endif
						@if ($cl->address_line1)<div class="muted">{{ $cl->address_line1 }}</div>@endif
					</div>
				</td>
			</tr>
		</table>
    </div>
    <div class="clearfix"></div>

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

      </h2>
      <table class="items">
        <thead>
        <tr>
          <th>Description</th>
          <th>Qty</th>
          <th>Unit Price</th>
          <th>Tax</th>
		  @if ($invoice->discount_mode === 'per-line')<th>Discount</th>@endif
          <th>Amount</th>
        </tr>
        </thead>
        <tbody>
        @forelse($items as $it)
          <tr>
            <td>
              <div class="strong">{{ $it->name ?? 'Item' }}</div>
              @if(!empty($it->description))
                <div class="muted small">{{ $it->description }}</div>
              @endif
            </td>
            <td>{{ rtrim(rtrim((string)($it->quantity ?? 0),'0'),'.') }}{{ $it->unit ? ' '.$it->unit : '' }}</td>
            <td>{{ $fmtMoney($it->unit_price_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
            <td>{{ $fmtMoney($it->tax_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
	  		@if ($invoice->discount_mode === 'per-line')<td><strong>{{ $fmtPercent($it->line_discount_rate) }}</strong></td>@endif
            <td>{{ $fmtMoney($it->line_total_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
          </tr>
        @empty
          <tr><td colspan="5" class="muted">No items.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <div class="totals">
      <div></div>
      <div class="box col-12">
        <h2 class="row">Summary</h2>
        <div class="row">
          <span class="left">Subtotal</span>
          <span class="right">{{ $fmtMoney($invoice->subtotal_cents ?? 0,$invoice->currency ?? 'USD') }}</span>
        </div>
        @if((int)($invoice->discount_cents ?? 0)>0)
          <div class="row">
            <span class="label left">Discount</span>
            <span class="right">-{{ $fmtMoney($invoice->discount_cents ?? 0,$invoice->currency ?? 'USD') }}</span>
          </div>
        @endif
        @if((int)($invoice->tax_cents ?? 0)>0)
          <div class="row">
            <span class="label left">Tax</span>
            <span class="right">{{ $fmtMoney($invoice->tax_cents ?? 0,$invoice->currency ?? 'USD') }}</span>
          </div>
        @endif
        @if((int)($invoice->shipping_cents ?? 0)>0)
          <div class="row">
            <span class="label left">Shipping</span>
            <span class="right">{{ $fmtMoney($invoice->shipping_cents ?? 0,$invoice->currency ?? 'USD') }}</span>
          </div>
        @endif
        <div class="row grand">
          <span class="left">Total Amount</span>
          <span class="right total">{{ $fmtMoney($invoice->total_cents ?? 0,$invoice->currency ?? 'USD') }}</span>
        </div>
      </div>
    </div>

    <div class="footer">
      <div class="fcard">
        <h4>
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
          Notes
        </h4>
        <p>{{ $invoice->notes ?? '—' }}</p>
      </div>
      <div class="fcard">
        <h4>Terms</h4>
        <p>{{ $invoice->terms ?? '—' }}</p>
      </div>
    </div>
  </div>

	{!! $watermark() !!}

  <style>
    /* Dompdf-friendly font */
    body, .invoice-root, .page {
      font-family: "DejaVu Sans", sans-serif !important;
    }

    .invoice-root{
      --font: {{ $theme['fontFamily'] ?? "Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif" }};
      --ink:#0b1220;
      --muted:#6b7280;
      --border:#e5e7eb;
      --bg:#fff;
      --accent:#0ea5e9;
      --accent-ink:#fff;
      --grad1: #22d3ee;
      --grad2:#0ea5e9;
    }

    .scheme-forest{--accent:#16a34a;--grad1:#34d399;--grad2:#16a34a}
    .scheme-royal{--accent:#6d28d9;--grad1:#a78bfa;--grad2:#6d28d9}
    .scheme-crimson{--accent:#dc2626;--grad1:#fb7185;--grad2:#dc2626}
    .scheme-sunset{--accent:#f97316;--accent-ink:#111827;--grad1:#fb923c;--grad2:#f97316}

    .page{
      width: 100%;
      max-width: 100%;
      box-sizing: border-box;
      background:var(--bg);
      padding-bottom:24px;
      border-radius:16px;
      box-shadow:0 10px 28px rgba(2,6,23,.06);
    }

    .banner{
      position:relative;
      background-color: {{$scheme->main->code }};
      color:var(--accent-ink);
      border-radius:16px 16px 0 0;
      padding: 20px 20px 20px 20px;
		height: auto;
		min-height: 150px;
    }

	.banner{
	  position:relative;
	  background-color: {{$scheme->main->code }};
	  color:var(--accent-ink);
	  border-radius:16px 16px 0 0;
	  padding: 20px 20px 20px 20px;
	  height: auto;
	  min-height: auto;
		overflow: hidden;
	}
	.clearfix::after {
	  content: "";
	  display: block;
	  clear: both;
	}

    .banner-inner {
	  padding: 18px 0;
	}

	/* Logo + info inside brand */
	.brand {
	  	width: 45%;
	}

	/* Logo column */
	.logo-div {
	  width: 10%;
	}

	.logo {
	  border-radius: 10px;
	  background: rgba(255,255,255,.15);
	  padding: 7px;
	  width: 90px;
	}

	/* Info column */
	.info-div {
	  width: auto;
	  min-height: 80px;
		height: auto;
		margin-left: 7px;
	}

	.due {
	  width: 28%;
	  padding-right: 20px;
	}


	.title {
	  margin: 0;
	  font-size: 22px;
		line-height: 1.4rem;
		margin-bottom:5px;
	}
	.info-div .muted {
		font-size: 14px;
	}
	/*.brand .info-div {*/
	/*  float: none;          !* override .left float *!*/
	/*  margin-left: 100px;   !* logo width (80) + margin (10) + a bit of padding *!*/
	/*}*/


    .kicker{opacity:.9;font-size:12px;letter-spacing:.08em;text-transform:uppercase}

    .due--bg {
      background-color: {{$scheme->extra_light->code}};
      padding: 12px 12px 12px 12px;
      border-radius: 10px;
      margin-top:5px;
    }

    .tiny{font-size:12px}
    .muted{opacity:.9}
    .duepill{
      background:#fff;
      color:#111827;
      border-radius:999px;
      padding:6px 10px;
      font-weight:700;
    }

    .angle{
      position:absolute;
      bottom:-4px;
      left:0;
      right:0;
      height:4px;
      background:#000;
      opacity:0.06;
    }

    .img-color {color: {{$scheme->main->code}}; }
    .billto-text {padding-bottom: 4px;font-size: 16px;font-weight:bold;}
	.billto-text .muted {
		font-size: 14px;
	}

    .grid2{
      padding:22px 12px;
      background-color: #F9FAFB;
    }

    .tile{border-radius:12px;background:#fff;min-height: 110px;overflow: visible; display: block;}
	.tile .paymentinfo {
		list-style: none;
		padding-left: 0;
	}
	.tile .paymentinfo .value {
		font-weight: bold;
	}
    .tile-h{
      padding:10px 0;
      font-size:12px;
      text-transform:uppercase;
      letter-spacing:.08em;
      color:#334155;
      font-weight: bold;
    }

    .tile-b{padding:14px;
		margin:0 12px;
		font-size: 14px;}
    .strong{font-weight:600}
    .tile-b .muted {padding: 3px 0;}

    /*.tablewrap{padding:22px 22px}*/
    .tablewrap h2 {font-size: 20px;}
    .tablewrap h2 svg {color: {{$scheme->main->code}} }

    table.items{
      width:100%;
      border-collapse:collapse;
      margin-top:6px;
      border:1px solid var(--border);
      border-radius:12px;
    }

    .items thead th{
      background:#F9FAFB;
      color:#374151;
      font-weight:600;
      font-size:12px;
      text-transform:uppercase;
      letter-spacing:.06em;
      text-align:left;
      padding:10px 12px
    }

    table thead th{border-radius: 12px;font-size: 15px;}
    .items tbody td{font-size: 15px;padding:12px;border-top:1px solid var(--border)}
    .items tbody tr:nth-child(odd){background:#fafafa}
    .right{text-align:right}
    .small{font-size:15px;color:#64748b;line-height: 22px;}

    .totals{
      width:auto;
      /*padding:18px 22px 0;*/
		margin-top: 12px;
    }

    /* hide the empty first column div */
    .totals > div:first-child{
      display:none;
    }

    .totals .box{
      border:2px solid #F3F4F6;
      border-radius:14px;
      background:#fff;
      padding:16px 30px;
      width:auto;
      box-sizing:border-box;
    }

    .totals h2{font-size: 16px;}
    .totals .label {font-size: 16px;}
    .totals .box .left {font-size: 16px; padding: 12px 0 12px 0;}
    .totals .box .right {font-size: 16px; padding: 12px 0 12px 0;}

    .row{border-top:1px dashed #e5e7eb}
    .row:first-child{border-top:0}
    .grand{font-weight:800;font-size:18px; font-family: "DejaVu Sans", sans-serif !important;}
    .grand .total {font-size:18px;color: {{$scheme->main->code}};}

    .footer{
      width:100%;
      /*padding:18px 22px;*/
		margin-top: 22px;
    }

    .fcard{
      border:1px solid var(--border);
      border-radius:12px;
      padding:14px;
      background:#fff;
      margin-bottom:12px;
      box-sizing:border-box;
    }

    .fcard:last-child{
      margin-bottom:0;
    }

    .fcard h4{
      margin:0 0 6px 0;
      font-size:12px;
      color:#334155;
      text-transform:uppercase;
      letter-spacing:.08em;
    }

    .fcard p{margin:0;white-space:pre-wrap}
  </style>
</div>
