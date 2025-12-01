<div class="neo--theme invoice-root neo-root scheme cat">
  <div class="canvas header">
    <table class="header-table">
		<tr>
      <td class="dompdf-col left-col">
        <div class="brand">
          @if ($logoSrc)
            <img
              src="{{ $logoSrc }}"
              alt="Business Logo"
              class="logo"
            />
			@endif

          <div class="business-profile">
            <h1 class="title">{{ $bp?->name ?? 'Your Business' }}</h1>

            @if ($bp->address_line1)
			<div class="muted">
              <svg
                width="12"
                height="16"
                viewBox="0 0 12 16"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g clip-path="url(#clip0_171_219)">
                  <path
                    d="M6.74062 15.6C8.34375 13.5938 12 8.73125 12 6C12 2.6875 9.3125 0 6 0C2.6875 0 0 2.6875 0 6C0 8.73125 3.65625 13.5938 5.25938 15.6C5.64375 16.0781 6.35625 16.0781 6.74062 15.6ZM6 4C6.53043 4 7.03914 4.21071 7.41421 4.58579C7.78929 4.96086 8 5.46957 8 6C8 6.53043 7.78929 7.03914 7.41421 7.41421C7.03914 7.78929 6.53043 8 6 8C5.46957 8 4.96086 7.78929 4.58579 7.41421C4.21071 7.03914 4 6.53043 4 6C4 5.46957 4.21071 4.96086 4.58579 4.58579C4.96086 4.21071 5.46957 4 6 4Z"
                    fill="currentColor"
                  />
                </g>
                <defs>
                  <clipPath id="clip0_171_219">
                    <path d="M0 0H12V16H0V0Z" fill="white" />
                  </clipPath>
                </defs>
              </svg>

              <span>{{ $bp->address_line1 }}</span>
            </div>
			@endif

			@if ($bp?->email)
            <div class="muted">
              <svg
                width="16"
                height="16"
                viewBox="0 0 16 16"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g clip-path="url(#clip0_171_228)">
                  <path
                    d="M1.5 2C0.671875 2 0 2.67188 0 3.5C0 3.97187 0.221875 4.41562 0.6 4.7L7.4 9.8C7.75625 10.0656 8.24375 10.0656 8.6 9.8L15.4 4.7C15.7781 4.41562 16 3.97187 16 3.5C16 2.67188 15.3281 2 14.5 2H1.5ZM0 5.5V12C0 13.1031 0.896875 14 2 14H14C15.1031 14 16 13.1031 16 12V5.5L9.2 10.6C8.4875 11.1344 7.5125 11.1344 6.8 10.6L0 5.5Z"
                    fill="currentColor"
                  />
                </g>
                <defs>
                  <clipPath id="clip0_171_228">
                    <rect width="16" height="16" fill="white" />
                  </clipPath>
                </defs>
              </svg>

              <span>{{ $bp?->email }}</span>
            </div>
			@endif

			@if ($bp?->phone)
            <div class="muted">
              <svg
                width="16"
                height="16"
                viewBox="0 0 16 16"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M5.15312 0.768966C4.9125 0.187716 4.27812 -0.121659 3.67188 0.0439663L0.921875 0.793966C0.378125 0.943966 0 1.43772 0 2.00022C0 9.73147 6.26875 16.0002 14 16.0002C14.5625 16.0002 15.0563 15.6221 15.2063 15.0783L15.9563 12.3283C16.1219 11.7221 15.8125 11.0877 15.2312 10.8471L12.2312 9.59709C11.7219 9.38459 11.1313 9.53147 10.7844 9.95959L9.52188 11.5002C7.32188 10.4596 5.54063 8.67834 4.5 6.47834L6.04063 5.21897C6.46875 4.86897 6.61562 4.28147 6.40312 3.77209L5.15312 0.772091V0.768966Z"
                  fill="currentColor"
                />
              </svg>
              <span>{{ $bp?->phone }}</span>
            </div>
			@endif
          </div>
        </div>
      </td>
      <td class="dompdf-col right-col">
        <h2 class="text-right">INVOICE</h2>

        <div class="box px-4 py-4">
          <div class="inner-box">
            <div class="label">Invoice Number</div>
            <div class="value">{{ $invoice->invoice_number ?? 'INV-XXXXXX' }}</div>

            <div class="label">Issue Date</div>
            <div class="value">{{ $fmtDate($invoice->issued_on ?? null) }}</div>

            <div class="label">Due Date</div>
            <div class="value">{{ $fmtDate($invoice->due_on ?? null) }}</div>
          </div>
        </div>
      </td>
		</tr>
    </table>
  </div>

  <div class="section--bill-to">
  <h2>Bill To</h2>

  <table class="billto-table">
    <tr>
      {{-- Left column: client info --}}
      <td class="billto-cell billto-left">
        <div class="box">
          <h2>{{ $cl?->company }}</h2>

          <ul>
            <li>
              {{ $cl?->name }}
            </li>

            @if ($cl->address_line1 || $cl->address_line2)
              <li>
                {{ $cl->address_line1 }}<br />
                {{ $cl->address_line2 }}
              </li>
            @endif

            @if ($cl?->email)
              <li>
                {{ $cl?->email }}
              </li>
            @endif

            @if ($cl?->phone)
              <li>
                {{ $cl?->phone }}
              </li>
            @endif
          </ul>
        </div>
      </td>

      {{-- Right column: notes & terms --}}
      <td class="billto-cell billto-right">
        <div class="box">
          <h2>Notes & Terms</h2>

          <div class="panel">
            <h4>Notes</h4>
            <p>{{ $invoice->notes ?? '—' }}</p>
          </div>

          <div class="panel">
            <h4>Terms</h4>
            <p>{{ $invoice->terms ?? '—' }}</p>
          </div>
        </div>
      </td>
    </tr>
  </table>
</div>


  <div class="section--table">
    <div class="card table">
      <table class="grid">
        <thead>
          <tr>
            <th>#</th>
            <th class="desc">Description</th>
            <th>Qty</th>
            <th class="right">Unit Price</th>
            <th class="right">Tax</th>
            <th class="right">Amount</th>
          </tr>
        </thead>

        <tbody>
          @php
            $itemsCount = $items instanceof \Illuminate\Support\Collection
              ? $items->count()
              : count($items);
          @endphp

          @if ($itemsCount === 0)
            <tr>
              <td colspan="6" class="muted center">No items.</td>
            </tr>
          @else
            @foreach ($items as $index => $it)
              <tr>
                <td>{{ $index + 1 }}</td>

                <td>
                  <div class="strong">{{ $it->name ?? 'Item' }}</div>

                  @if (!empty($it->description))
                    <div class="muted small">{{ $it->description }}</div>
                  @endif
                </td>

                <td>
                  <span class="tag">
                    {{ rtrim(rtrim((string)($it->quantity ?? 0), '0'), '.') }}
                    {{ $it->unit ? ' ' . $it->unit : '' }}
                  </span>
                </td>

                <td class="right">
                  {{ $fmtMoney($it->unit_price_cents ?? 0, $invoice->currency ?? 'USD') }}
                </td>

                <td class="right">
                  {{ $fmtMoney($it->tax_cents ?? 0, $invoice->currency ?? 'USD') }}
                </td>

                <td class="right">
                  {{ $fmtMoney($it->line_total_cents ?? 0, $invoice->currency ?? 'USD') }}
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>

  <div class="section__total clearfix">
    <div class="right">
      <div class="box">
        <div class="summary">
          <div class="row">
            <span class="left">Subtotal</span>
            <span class="right">
              {{ $fmtMoney($invoice->subtotal_cents ?? 0, $invoice->currency ?? 'USD') }}
            </span>
          </div>

          @if ((int) ($invoice->discount_cents ?? 0) > 0)
            <div class="row">
              <span class="left">Discount</span>
              <span class="right">
                -{{ $fmtMoney($invoice->discount_cents ?? 0, $invoice->currency ?? 'USD') }}
              </span>
            </div>
          @endif

          @if ((int) ($invoice->tax_cents ?? 0) > 0)
            <div class="row">
              <span class="left">Tax</span>
              <span class="right">
                {{ $fmtMoney($invoice->tax_cents ?? 0, $invoice->currency ?? 'USD') }}
              </span>
            </div>
          @endif

          @if ((int) ($invoice->shipping_cents ?? 0) > 0)
            <div class="row">
              <span class="left">Shipping</span>
              <span class="right">
                {{ $fmtMoney($invoice->shipping_cents ?? 0, $invoice->currency ?? 'USD') }}
              </span>
            </div>
          @endif

          <div class="row grand">
            <span class="left">Total</span>
            <span class="right value">
              {{ $fmtMoney($invoice->total_cents ?? 0, $invoice->currency ?? 'USD') }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
	<div class="watermark">Powered by <strong>Billifty.com</strong></div>

  @php
    $tbodyBorderColor = $scheme->table_tbody_color->code ?? '#E5E7EB';
  @endphp

<style>
  body {
    font-family: '{{ $theme->fontFamily ?? "DejaVu Sans" }}', sans-serif;
    font-size: 12px;
  }

  .container {
    width: 916px;
    margin-bottom: 12px;
    border-radius: 12px;
    box-shadow: 0 0 12px rgba(51, 51, 51, 0.2);
  }
  /* ===========================
     BRAND: LOGO + TITLE LAYOUT
     =========================== */

	 .brand {
	  margin-top: 12px;
	  display: table;
	  table-layout: fixed;
		 overflow: hidden;
	}

	.brand .logo {
	  display: table-cell;
	  float: none !important;
	}

	.brand .business-profile {
	  display: table-cell;
	  float: none !important;
	  padding-left: 12px;

	}

  .brand .logo img {
    max-width: 100%;
    max-height: 100%;
  }

  .brand .logo.placeholder {
    font-weight: 800;
    background-color: {{ $scheme->light->code }};
    color: #111827;
    line-height: 100px;             /* center initial vertically */
  }

  .brand .business-profile .title {
    margin: 0 0 4px;
    font-size: 22px;
    font-weight: 700;
    color: #FFFFFF;
    word-wrap: break-word;
	  line-height: 1.4rem;
  }

  .brand .business-profile .muted {
    color: #FFFFFF;
    padding: 2px 0;
  }

  .brand .business-profile .muted svg {
    color: #FFFFFF;
    vertical-align: middle;
    margin-right: 4px;
  }

  .brand .business-profile .muted span {
    vertical-align: middle;
	  font-size: 15px;
  }

  /* If .business-profile is used elsewhere, keep it harmless */
  .business-profile {
    margin-left: 12px;
    width: 300px;
    float: none;
  }

  /* ===========================
     HEADER / GENERAL
     =========================== */

  .inner-box {
    /*float: right;*/
    text-align: right;
  }

  .canvas {
    background: #fff;
    border-top-left-radius: 18px;
    border-top-right-radius: 18px;
    box-shadow: 0 12px 34px rgba(2, 6, 23, 0.08);
    padding: 22px;
    color: #111827;
  }

  .header {
    background-color: {{ $scheme->main->code }};
    border-bottom: 1px solid #E5E7EB;
	display: block;
    overflow: visible;
	  clear: both;
  }
  .header-table {
	width: 100%;
  	border-collapse: collapse;
  }
  .header-table h2 {
	  font-size: 22px;
  }
  .header-table .inner-box .label,
  .header-table .inner-box .value{
	  font-size: 16px;
  }
	.header-table .inner-box .value {
		font-weight: bold;
	}

  .eyebrow {
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    font-size: 12px;
  }

  .num {
    font-size: 28px;
    margin: 2px 0 6px;
    letter-spacing: 0.2px;
    font-weight: 800;
  }

  .right .due {
    font-size: 12px;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.12em;
  }

  .right .dueval {
    background: {{ $scheme->main->code }};
    color: #111827;
    padding: 6px 10px;
    border-radius: 10px;
    font-weight: 800;
    margin-top: 6px;
    text-align: right;
  }

  .left h2 {
    color: #FFFFFF;
  }

  .left {
    text-align: left;
  }

  .right {
    text-align: right;
  }

  .center {
    text-align: center;
  }

  .left .box .value,
  .right .box .value {
    font-size: 20px;
    font-weight: bold;
  }

  .left .box .label,
  .right .box .label {
    font-size: 14px;
  }

  .desc {
    width: 50%;
  }

  .strong {
    font-weight: 700;
  }

  .muted {
    color: #6B7280;
  }

  .tiny {
    font-size: 12px;
  }

  .small {
    font-size: 12px;
  }

  /* ===========================
     TABLE
     =========================== */

  .card.table {
    border: 1px solid #E5E7EB;
    border-radius: 14px;
    overflow: hidden;
    margin-top: 16px;
  }

  table.grid {
    width: 100%;
    border-collapse: collapse;
  }

  /* Dompdf-safe: use solid color here instead of gradient */
  table.grid thead {
    background-color: {{ $scheme->main->code }};
    color: #FFFFFF;
  }

  table.grid thead th {
    text-align: left;
    font-size: 12px;
    padding: 12px;
    border-bottom: 1px solid #E5E7EB;
  }

  @if (isset($scheme->table_tbody_color) && $scheme->table_tbody_color->code)
    table.grid tbody {
      background-color: {{ $scheme->table_tbody_color->code }};
    }
  @endif

  table.grid tbody td {
    padding: 12px;
    border-top: 1px solid #E5E7EB;
    font-size: 13px;
  }

  table.grid tbody tr:nth-child(odd) td {
    background: #F9FAFB;
  }

  table.grid tbody tr td .small {
    font-size: 14px;
    font-weight: lighter;
    padding-top: 7px;
  }

  table.grid tbody tr td .strong {
    font-size: 16px;
    font-weight: bold;
  }

  .tag {
    border: 1px solid #E5E7EB;
    background: #FFFFFF;
    border-radius: 999px;
    padding: 2px 8px;
    font-size: 12px;
  }

  /* ===========================
     NOTES / PANELS
     =========================== */

  .notes {
    margin-top: 16px;
  }

  .panel {
    border: 1px dashed #E5E7EB;
    border-radius: 12px;
    padding: 12px 14px;
    background: #fcfdff;
  }

  .panel h4 {
    margin: 0 0 8px;
    font-size: 12px;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.1em;
  }

  .panel p {
    margin: 0;
    white-space: pre-wrap;
    font-size: 13px;
  }

  /* ===========================
     SUMMARY / TOTALS
     =========================== */

  .summary {
    border-radius: 14px;
    padding: 12px 14px;
    background: #fff;
  }

  .summary .row {
    padding: 22px 0;
    border-top: 1px dashed #E5E7EB;
  }

  .summary .row:first-child {
    border-top: 0;
  }

  .summary .grand {
    font-weight: 900;
    font-size: 24px;
    border-top: 1px solid {{ $tbodyBorderColor }};
	  padding-top: 7px;
  }

  .summary .grand .value {
    color: {{ $scheme->main->code }};
  }

  	/* ===========================
	BILL TO
	=========================== */

	.section--bill-to {
	  background-color: {{ $scheme->light->code }};
	  padding: 16px 22px 18px 22px;
	  clear: both;
	  display: block;
	  overflow: visible;   /* let content define height */
	}

	.section--bill-to h2 {
	  color: {{ $scheme->main->code }};
	  font-size: 14px;
	  margin: 0 0 8px 0;
	}

	/* Layout table for the two columns */
	.billto-table {
	  width: 100%;
	  border-collapse: collapse;
	}

	.billto-cell {
	  vertical-align: top;
	  padding: 0;
	}

	/* Left and right widths */
	.billto-left {
	  width: 40%;
	  padding-right: 12px;
	}

	.billto-right {
	  width: 60%;
	  padding-left: 12px;
	}

	/* Card styling inside each cell */
	.section--bill-to .box {
	  margin-top: 4px;
	  background-color: #fff;
	  padding: 20px 30px;
	  box-shadow: 0 0 12px #aeaab3;
	}

	.section--bill-to .box h2 {
	  font-size: 20px;
	  color: #000000;
	  margin: 0 0 6px 0;
	}

	.section--bill-to .box ul {
	  list-style: none;
	  padding: 0;
	  margin: 12px 0 0;
	}

	.section--bill-to .box ul li {
	  color: #000000;
	  padding: 6px 0;
	}

	.section--bill-to .box .panel h4,
	.section--bill-to .box .panel p {
	  color: #000000;
	}


  /* ===========================
     TABLE + TOTALS SECTIONS
     =========================== */

  .section--table {
    padding: 12px 22px;
    background-color: #fff;
  }

  .section__total {
    padding: 12px 22px;
  }

  .section__total .box {
    width: 500px;
    color: #4B5563;
    border-radius: 12px;
    box-shadow: 0 0 12px rgba(122, 101, 101, 0.17);
  }
  .watermark {
	  margin-top: 22px;
  }

  /* ===========================
     PRINT
     =========================== */

  @media print {
    .canvas {
      box-shadow: none;
      padding: 16px;
      border-radius: 0;
    }
  }
</style>
</div>
