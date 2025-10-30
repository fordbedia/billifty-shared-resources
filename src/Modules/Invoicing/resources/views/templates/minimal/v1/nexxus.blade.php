@php
  // Pick your colors (fallbacks included)
  $fontFamily = $theme->fontFamily ?? "DejaVu Sans, Arial, sans-serif";
  $ink        = '#0f172a';
  $muted      = '#64748b';
  $bg         = '#ffffff';
  $card       = '#ffffff';
  $railColor  = $scheme->main->code;
  $border     = '#e2e8f0';
  $stripe     = '#f8fafc';
  $accentInk  = '#ffffff';

  $logoW = 150;
	$pi = $invoice->paymentInformation;

@endphp

<div class="invoice-root scheme">
  <div class="page">
    <div class="wall"></div>

    <div class="content clearfix">

			<div class="header clearfix">
				<div class="left">
					<div class="logo-div left">
						@if(!empty($bp?->logo_path))
							<img src="{{ asset($bp->logo_path) }}" alt="Business Logo" class="logo"/>
						@else
							<div class="logo placeholder"><span>{{ strtoupper(substr($bp?->name ?? 'B',0,1)) }}</span></div>
						@endif
					</div>
					<div class="info-div left">
            <h1 class="title">{{ $bp?->name ?? 'Your Business' }}</h1>
          	<div class="muted">{{ $bp ? $addr($bp) : '' }}</div>
						<div class="muted">{{ $bp?->email }}</div>
						<div class="muted">@if($bp?->email && $bp?->phone) @endif{{ $bp?->phone }}</div>
          </div>
				</div>
				<div class="right">
					<h2 class="text-right">Bill To</h2>
					<div class="box">
						<div class="tile-b text-right">
							<div class="strong">{{ $cl?->name ?? $cl?->company ?? 'Client' }}</div>
							<div class="muted">{{ $cl?->email }}}</div>
							@if ($cl?->phone) <div class="muted">{{$cl?->phone}}</div> @endif
							@if($cl?->tax_id)<div class="muted">Tax ID: {{ $cl->tax_id }}</div>@endif
							@if($cl?->license_no)<div class="muted">License No: {{ $cl->license_no }}</div>@endif
							<div class="muted">{{ $cl ? $addr($cl) : '' }}</div>
						</div>
					</div>
				</div>
			</div>

			<div class="invoice-info clearfix">
				<div class="left">
					<h2>Invoice</h2>
				</div>

				<div class="right">
					<div class="right">
						<div class="section">
							<div class="label">Issue Date</div>
							<div class="value">@if ($invoice->issued_on)<div class="muted tiny">Date {{ $fmtDate($invoice->issued_on ?? null) }}</div>@else -- @endif</div>
						</div>
						<div class="section">
							<div class="label">Due Date</div>
							<div class="value">@if ($invoice->due_on)<div class="duepill">Due {{ $fmtDate($invoice->due_on ?? null) }}</div>@else -- @endif</div>
						</div>
					</div>

					<div class="right">
						<div class="section">
							<div class="label">Invoice Number</div>
							<div class="value">{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</div>
						</div>
					</div>
				</div>
			</div>

			<div class="invoice-table">
				<table class="items">
					<thead>
						<tr>
							<th>Description</th>
							<th>Qty</th>
							<th>Rate</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody>
						@forelse($items as $it)
							<tr>
								<td>
									<div class="strong">{{ $it->name ?? 'Item' }}</div>
									@if(!empty($it->description))<div class="muted small">{{ $it->description }}</div>@endif
								</td>
								<td>{{ rtrim(rtrim((string)($it->quantity ?? 0),'0'),'.') }}{{ $it->unit ? ' '.$it->unit : '' }}</td>
								<td>{{ $fmtMoney($it->unit_price_cents ?? 0, $invoice->currency ?? 'USD') }}</td>
								<td><strong>{{ $fmtMoney($it->line_total_cents ?? 0, $invoice->currency ?? 'USD') }}</strong></td>
							</tr>
						@empty
							<tr><td colspan="4" class="muted">No items.</td></tr>
						@endforelse
					</tbody>
					<tfoot>
						<tr>
							<td></td>
							<td></td>
							<td class="label">Subtotal</td>
							<td class="value">{{ $fmtMoney($invoice->subtotal_cents ?? 0,$invoice->currency ?? 'USD') }}</td>
						</tr>
						@if((int)($invoice->discount_cents ?? 0)>0)
							<tr>
								<td></td>
								<td></td>
								<td class="label">Discount</td>
								<td class="value">-{{ $fmtMoney($invoice->discount_cents ?? 0,$invoice->currency ?? 'USD') }}</td>
							</tr>
						@endif
						@if((int)($invoice->tax_cents ?? 0)>0)
							<tr>
								<td></td>
								<td></td>
								<td class="label">Tax</td>
								<td class="value">{{ $fmtMoney($invoice->tax_cents ?? 0,$invoice->currency ?? 'USD') }}</td>
							</tr>
						@endif
						@if((int)($invoice->shipping_cents ?? 0)>0)
							<tr>
								<td></td>
								<td></td>
								<td class="label">Shipping</td>
								<td class="value">{{ $fmtMoney($invoice->shipping_cents ?? 0,$invoice->currency ?? 'USD') }}</td>
							</tr>
						@endif
						<tr>
							<td></td>
							<td></td>
							<td class="line label">Total</td>
							<td class="line value">{{ $fmtMoney($invoice->total_cents ?? 0,$invoice->currency ?? 'USD') }}</td>
						</tr>
					</tfoot>
      	</table>
			</div>

			<div class="payment_info__notes">
				<div class="left box payment_info">
					<h2>Payment Information</h2>
					<ul>
						@if(($pi->bank_name ?? 0)>0)<li><span class="label">Bank:</span> <span class="value">{{$pi->bank_name}}</span></li>@endif
						@if(($pi->account_name ?? 0)>0)<li><span class="label">Account Name:</span> <span class=value> {{$pi->account_name}}</span></li>@endif
						@if(($pi->account_number ?? 0)>0)<li><span class="label">Account:</span> <span class="value"> {{$pi->account_number}}</span></li>@endif
						@if(($pi->routing_number ?? 0)>0)<li><span class="label">Routing:</span> <span class="value"> {{$pi->routing_number}}</span></li>@endif
						@if(($pi->swift_code ?? 0) > 0)<li><span class="label">Swift:</span> <span class="value"> {{$pi->swift_code}}</span></li>@endif
						@if(($pi->iban ?? 0)>0)<li><span class="label">IBAN:</span> <span class="value">{{$pi->iban}}</span></li>@endif
						@if(($pi->paypal_email ?? 0)>0)<li><span class="label">PayPal:</span> <span class="value"> {{$pi->paypal_email}}</span></li>@endif
					</ul>
				</div>
				<div class="right box notes">
					<h2>Notes</h2>
					@if ($invoice->notes)<p>{{$invoice->notes}}</p>@else -- @endif
				</div>
			</div>

    </div>
  </div>
</div>

<style>
	.bg-light {
		margin-top: 0;
	}
  .page {
    position: relative;         /* anchor for abs. children */
    /* optional if you want spacing so text doesn't sit under the wall */
    padding-left: 14px;
		padding-top: 30px;
		padding-bottom: 30px;
		font-family:{{ $theme->fontFamily ?? "Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif" }};
		width: 1224px;
		background: linear-gradient({{$scheme->gradient_bg_1_light->code}});
  }
	.content {
		width: 1024px;
		margin: 0 auto;
	}

  .wall {
    position: absolute;
    top: 0;
    bottom: 0;                  /* <-- stretches to parent's full height */
    left: 0;
    width: 7px;
		min-height: 20px;

    /* Dompdf prefers the shorthand 'background' */
    background: linear-gradient(
      {{ $scheme->gradient_bg_1->code }}
    );
  }
	.logo{width:100px;height:100px;border-radius:10px;background:rgba(255,255,255,.15);object-fit:contain}
	.logo.placeholder{display:grid;place-items:center;font-weight:800}
	.box {border: 1px solid #E0E7FF; border-radius: 12px;background-color: #fff;padding: 14px 16px;}
	.header .right h2 {font-size: 14px; padding-bottom: 5px;}
	.header .right .strong{font-weight: bold;font-size: 16px;}
	.header .right .muted {font-size: 14px;}
	.invoice-info {padding: 30px 0;}
	.invoice-info .section {padding-left: 30px;padding-top: 30px;}
	.invoice-info .section .label {font-size: 14px; color:#6B7280;}
	.invoice-info .section .value {font-size: 14px;color: #111827;font-weight: bold;}

	table.items{width:100%;border-collapse:collapse;margin-top:6px;border:1px solid #F3F4F6;}
	.items thead{background:#EEF2FF;color:#374151;font-weight:600;font-size:12px;text-transform:uppercase;letter-spacing:.06em;text-align:left;border-radius: 12px;}
	table.items thead th {padding:17px 12px;}
	table.items thead th:first-child {border-top-left-radius: 12px;}
	table.items thead th:last-child {border-top-right-radius: 12px;}
	.items tbody td{padding:12px 16px;border-top:1px solid var(--border)}
	/*.items tbody tr:nth-child(odd){background:#fafafa}*/
	.items tbody tr {border-bottom: 1px solid #F3F4F6;}
	.items tbody td .strong{font-size: 16px;color: #111827;font-weight: bold;}
	.items tbody td .muted {font-size: 14px;color:#4B5563;padding-top: 6px;}
	table.items tfoot {background-color: {{$scheme->extra_light->code}};}
	table.items tfoot td{padding: 15px 0;}
	table.items tfoot td.line{border-top: 1px solid #E5E7EB;}
	table.items tfoot td.label{color: #374151;font-size: 16px;}
	table.items tfoot td.value{color: #374151;font-size: 16px;font-weight: 600;}
	table.items tfoot td.label.line {font-size: 18px;font-weight: bold;}
	table.items tfoot td.value.line {font-size: 18px;font-weight: bold;color: {{$scheme->main->code}};}
	table.items tfoot tr:last-child td:first-child{border-bottom-left-radius: 12px;}
	table.items tfoot tr:last-child td:last-child{border-bottom-right-radius: 12px;}
	.payment_info__notes{margin-top:25px;}
	.payment_info__notes .payment_info.box {width: 450px;}
	.payment_info__notes .payment_info.box h2 {font-size: 16px;color: #111827; font-weight: 600;}
	.payment_info__notes .payment_info.box ul {list-style: none;padding-left: 0;}
	.payment_info__notes .payment_info.box ul li {line-height: 20px;}
	.payment_info__notes .payment_info.box ul li span.label {font-size: 14px;color:#4B5563;font-weight: 600;}
	.payment_info__notes .payment_info.box ul li span.value {font-size: 14px;color:#4B5563;font-weight: 300;}
	.payment_info__notes .notes.box {width: 450px;}
	.payment_info__notes .notes.box h2 {font-size: 16px;color: #111827;font-weight: 600;}
	.payment_info__notes .notes.box p{color: #4B5563;font-size: 14px;line-height: 20px;font-weight: 300;}
</style>

