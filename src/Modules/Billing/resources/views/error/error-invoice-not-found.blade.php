@php
	$link = url()->current() ?? null;
@endphp
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Invoice Not Found</title>
	<style>
		:root {
			--blue: #279be8;
			--blue-soft: #e9f2ff;
			--danger: #ef233c;
			--danger-soft: #ffe6eb;
			--ink: #111827;
			--muted: #5f6b7a;
			--line: #e3e7ec;
			--surface: #ffffff;
			--panel: #f8fafc;
			--page: #ffffff;
		}

		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			background: var(--page);
			color: var(--ink);
			font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
			font-size: 15px;
			line-height: 1.5;
		}

		a {
			color: inherit;
			text-decoration: none;
		}

		.page-shell {
			width: min(100% - 32px, 860px);
			margin: 34px auto 42px;
			text-align: center;
		}

		.error-badge {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 10px;
			min-height: 42px;
			border-radius: 999px;
			background: var(--danger-soft);
			color: var(--danger);
			padding: 0 24px;
			font-size: 16px;
			font-weight: 800;
			line-height: 1;
		}

		.error-badge svg {
			width: 20px;
			height: 20px;
			flex: 0 0 auto;
		}

		.hero-title {
			margin: 24px 0 8px;
			font-size: clamp(34px, 5vw, 48px);
			line-height: 1.08;
			font-weight: 900;
			letter-spacing: 0;
		}

		.hero-copy {
			margin: 0 auto;
			color: #4b5563;
			font-size: clamp(18px, 2.6vw, 22px);
		}

		.invoice-card {
			position: relative;
			overflow: hidden;
			margin-top: 40px;
			border: 1px solid var(--line);
			border-radius: 14px;
			background: var(--surface);
			text-align: left;
			box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02);
		}

		.invoice-card::after {
			content: "404";
			position: absolute;
			top: 45%;
			left: 50%;
			z-index: 2;
			width: 318px;
			height: 128px;
			display: grid;
			place-items: center;
			border: 11px solid rgba(239, 35, 60, 0.22);
			border-radius: 10px;
			color: rgba(239, 35, 60, 0.25);
			font-size: 82px;
			font-weight: 900;
			letter-spacing: 16px;
			line-height: 1;
			transform: translate(-50%, -50%) rotate(-19deg);
			pointer-events: none;
		}

		.invoice-top {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 24px;
			padding: 38px 34px;
			filter: blur(5px);
			user-select: none;
		}

		.business {
			display: flex;
			align-items: center;
			gap: 14px;
		}

		.business-mark,
		.help-icon {
			width: 48px;
			height: 48px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			flex: 0 0 auto;
			border-radius: 12px;
			background: var(--blue);
			color: #ffffff;
			box-shadow: inset 0 -3px 0 rgba(0, 0, 0, 0.08);
		}

		.business-mark svg,
		.help-icon svg {
			width: 25px;
			height: 25px;
		}

		.business h2,
		.invoice-title h2 {
			margin: 0;
			font-size: 22px;
			line-height: 1.1;
			font-weight: 900;
			letter-spacing: 0;
		}

		.business p,
		.invoice-title p {
			margin: 4px 0 0;
			color: var(--muted);
			font-size: 16px;
		}

		.invoice-title {
			text-align: right;
		}

		.invoice-title h2 {
			font-size: 32px;
		}

		.invoice-title strong {
			color: var(--danger);
		}

		.invoice-meta {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			border-top: 1px solid var(--line);
			border-bottom: 1px solid var(--line);
			background: var(--panel);
			padding: 26px 34px;
			gap: 28px;
		}

		.meta-item {
			min-width: 0;
		}

		.meta-label,
		.table-head {
			color: #7b8794;
			font-size: 14px;
			font-weight: 900;
			letter-spacing: 0.08em;
			text-transform: uppercase;
		}

		.meta-value {
			display: inline-block;
			margin-top: 12px;
			color: #111827;
			font-size: 15px;
			font-weight: 800;
			filter: blur(5px);
			user-select: none;
		}

		.status-pill {
			display: inline-flex;
			align-items: center;
			gap: 7px;
			border-radius: 999px;
			background: var(--danger-soft);
			color: var(--danger);
			padding: 4px 12px;
			filter: none;
		}

		.status-dot {
			width: 8px;
			height: 8px;
			border-radius: 999px;
			background: currentColor;
		}

		.invoice-body {
			padding: 28px 34px 34px;
			min-height: 360px;
		}

		.invoice-table {
			width: 100%;
			border-collapse: collapse;
		}

		.invoice-table th,
		.invoice-table td {
			border-bottom: 1px solid var(--line);
			padding: 15px 4px;
			text-align: left;
		}

		.invoice-table th:not(:first-child),
		.invoice-table td:not(:first-child) {
			text-align: right;
		}

		.invoice-table td {
			color: #111827;
			font-weight: 800;
			filter: blur(5px);
			user-select: none;
		}

		.invoice-summary {
			width: min(100%, 330px);
			margin: 26px 0 0 auto;
		}

		.summary-row {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 20px;
			padding: 8px 0;
			color: var(--muted);
			filter: blur(5px);
			font-weight: 700;
			user-select: none;
		}

		.summary-row.total {
			margin-top: 8px;
			border-top: 1px solid var(--line);
			color: var(--ink);
			filter: none;
			font-size: 20px;
			font-weight: 900;
		}

		.summary-row.total span:last-child {
			color: var(--blue);
			filter: blur(5px);
		}

		.invoice-footer {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 24px;
			border-top: 1px solid var(--line);
			background: var(--panel);
			padding: 18px 34px;
			color: #8190a0;
			font-size: 15px;
		}

		.footer-lock,
		.footer-link {
			display: inline-flex;
			align-items: center;
			gap: 10px;
			min-width: 0;
		}

		.footer-link span {
			overflow: hidden;
			text-decoration: line-through;
			text-overflow: ellipsis;
			white-space: nowrap;
		}

		.footer-lock svg,
		.footer-link svg {
			width: 17px;
			height: 17px;
			flex: 0 0 auto;
		}

		.help-card {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 26px;
			margin-top: 34px;
			border-radius: 14px;
			background: var(--blue-soft);
			padding: 28px 32px;
			text-align: left;
		}

		.help-copy {
			display: flex;
			align-items: center;
			gap: 22px;
			min-width: 0;
		}

		.help-copy h2 {
			margin: 0;
			font-size: 20px;
			line-height: 1.2;
			font-weight: 900;
			letter-spacing: 0;
		}

		.help-copy p {
			margin: 8px 0 0;
			color: #4b5563;
			font-size: 17px;
			line-height: 1.35;
		}

		.contact-button {
			min-height: 48px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 10px;
			flex: 0 0 auto;
			border-radius: 10px;
			background: var(--blue);
			color: #ffffff;
			padding: 0 24px;
			font-size: 16px;
			font-weight: 900;
			box-shadow: inset 0 -3px 0 rgba(0, 0, 0, 0.08);
		}

		.contact-button svg {
			width: 20px;
			height: 20px;
			flex: 0 0 auto;
		}

		.error-code {
			margin-top: 34px;
			color: #8190a0;
			font-size: 15px;
			font-weight: 700;
		}

		.error-code strong {
			color: var(--danger);
		}

		@media (max-width: 760px) {
			.page-shell {
				width: min(100% - 24px, 860px);
				margin-top: 26px;
			}

			.hero-title {
				margin-top: 20px;
			}

			.invoice-card {
				margin-top: 30px;
			}

			.invoice-card::after {
				top: 47%;
				width: 238px;
				height: 100px;
				border-width: 8px;
				font-size: 58px;
				letter-spacing: 10px;
			}

			.invoice-top,
			.invoice-footer,
			.help-card {
				padding-inline: 22px;
			}

			.invoice-top,
			.invoice-footer,
			.help-card,
			.help-copy {
				align-items: flex-start;
				flex-direction: column;
			}

			.invoice-title {
				text-align: left;
			}

			.invoice-meta {
				grid-template-columns: repeat(2, 1fr);
				padding: 22px;
				gap: 22px;
			}

			.invoice-body {
				min-height: 330px;
				padding: 24px 22px 28px;
			}

			.invoice-table th:nth-child(3),
			.invoice-table td:nth-child(3) {
				display: none;
			}

			.contact-button {
				width: 100%;
			}
		}

		@media (max-width: 480px) {
			.error-badge {
				width: 100%;
				padding-inline: 16px;
			}

			.invoice-meta {
				grid-template-columns: 1fr;
			}

			.invoice-table th:nth-child(2),
			.invoice-table td:nth-child(2) {
				display: none;
			}

			.invoice-footer {
				gap: 12px;
			}
		}
	</style>
</head>
<body>
<main class="page-shell">
	<div class="error-badge" aria-label="Invoice not found">
		<svg aria-hidden="true" viewBox="0 0 24 24" fill="none">
			<path d="M10.3 4.1 2.5 17.6A2 2 0 0 0 4.2 20h15.6a2 2 0 0 0 1.7-2.4L13.7 4.1a2 2 0 0 0-3.4 0Z" fill="currentColor"/>
			<path d="M12 8v5M12 16.5h.01" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
		</svg>
		Invoice Not Found
	</div>

	<h1 class="hero-title">This invoice is not accessible</h1>
	<p class="hero-copy">Please contact the sender to get a valid invoice link.</p>

	<section class="invoice-card" aria-label="Unavailable invoice preview">
		<header class="invoice-top">
			<div class="business">
				<div class="business-mark" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none">
						<path d="m13 2-8 11h6l-1 9 9-13h-6l1-7Z" fill="currentColor"/>
					</svg>
				</div>
				<div>
					<h2>Acme Corp</h2>
					<p>acme@example.com</p>
				</div>
			</div>
			<div class="invoice-title">
				<h2>INVOICE</h2>
				<p>#INV-<strong>404</strong></p>
			</div>
		</header>

		<div class="invoice-meta">
			<div class="meta-item">
				<div class="meta-label">Issue Date</div>
				<div class="meta-value">Jan 10, 2026</div>
			</div>
			<div class="meta-item">
				<div class="meta-label">Due Date</div>
				<div class="meta-value">Jan 31, 2026</div>
			</div>
			<div class="meta-item">
				<div class="meta-label">Bill To</div>
				<div class="meta-value">John Doe</div>
			</div>
			<div class="meta-item">
				<div class="meta-label">Status</div>
				<div class="meta-value status-pill"><span class="status-dot"></span> Not Found</div>
			</div>
		</div>

		<div class="invoice-body">
			<table class="invoice-table" aria-hidden="true">
				<thead>
				<tr>
					<th class="table-head">Description</th>
					<th class="table-head">Qty</th>
					<th class="table-head">Unit Price</th>
					<th class="table-head">Total</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>Web Design Services</td>
					<td>1</td>
					<td>$2,400.00</td>
					<td>$2,400.00</td>
				</tr>
				<tr>
					<td>Development Hours</td>
					<td>18</td>
					<td>$85.00</td>
					<td>$1,530.00</td>
				</tr>
				<tr>
					<td>Hosting & Maintenance</td>
					<td>1</td>
					<td>$250.00</td>
					<td>$250.00</td>
				</tr>
				</tbody>
			</table>

			<div class="invoice-summary" aria-hidden="true">
				<div class="summary-row">
					<span>Subtotal</span>
					<span>$4,180.00</span>
				</div>
				<div class="summary-row">
					<span>Tax (8%)</span>
					<span>$334.40</span>
				</div>
				<div class="summary-row total">
					<span>Total</span>
					<span>$4,514.40</span>
				</div>
			</div>
		</div>

		<footer class="invoice-footer">
			<div class="footer-lock">
				<svg aria-hidden="true" viewBox="0 0 24 24" fill="none">
					<path d="M7 11V8a5 5 0 0 1 10 0v3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
					<path d="M6 11h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-8a1 1 0 0 1 1-1Z" fill="currentColor"/>
				</svg>
				Secure Invoice Link
			</div>
			<div class="footer-link">
				<svg aria-hidden="true" viewBox="0 0 24 24" fill="none">
					<path d="M10 13a5 5 0 0 0 7.1 0l2-2a5 5 0 0 0-7.1-7.1l-1.1 1.1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
					<path d="M14 11a5 5 0 0 0-7.1 0l-2 2A5 5 0 0 0 12 20.1l1.1-1.1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
				</svg>
				<span>{{ $link }}</span>
			</div>
		</footer>
	</section>

	<section class="help-card" aria-label="Invoice access help">
		<div class="help-copy">
			<div class="help-icon" aria-hidden="true">
				<svg viewBox="0 0 24 24" fill="none">
					<path d="M4 8h16v11H4V8Z" fill="currentColor" opacity=".9"/>
					<path d="m4 8 8 6 8-6" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					<path d="M8 8V5h8v3" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
				</svg>
			</div>
			<div>
				<h2>Need access to this invoice?</h2>
				<p>The link you followed may be expired or incorrect. Reach out to the person who sent you this invoice and ask them to resend the correct link.</p>
			</div>
		</div>
	</section>

	<div class="error-code">Error Code: <strong>404</strong> - Public Invoice Not Accessible</div>
</main>
</body>
</html>
