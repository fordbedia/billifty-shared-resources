<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Invoice Preview</title>
	<style>
		:root {
			--blue: #279be8;
			--ink: #111827;
			--muted: #5f6b7a;
			--line: #e3e7ec;
			--surface: #ffffff;
			--page: #f7f7f8;
			--cta: #e9f2ff;
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

		.site-header {
			background: var(--surface);
			border-bottom: 1px solid var(--line);
		}

		.nav {
			width: min(100% - 32px, 1120px);
			min-height: 74px;
			margin: 0 auto;
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 24px;
		}

		.brand {
			display: inline-flex;
			align-items: center;
			flex: 0 0 auto;
		}

		.brand img {
			display: block;
			width: 132px;
			height: auto;
		}

		.nav-actions {
			display: flex;
			align-items: center;
			justify-content: flex-end;
			gap: 18px;
			font-size: 14px;
			font-weight: 700;
		}

		.link-button {
			color: #111827;
			white-space: nowrap;
		}

		.pill-button {
			min-height: 42px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 8px;
			border-radius: 999px;
			border: 1px solid transparent;
			padding: 0 22px;
			background: var(--blue);
			color: #ffffff;
			font-weight: 800;
			line-height: 1;
			box-shadow: 0 8px 18px rgba(39, 155, 232, 0.22);
			white-space: nowrap;
		}

		.pill-button.secondary {
			background: #ffffff;
			color: #111827;
			border-color: var(--line);
			box-shadow: none;
			font-weight: 700;
		}

		.pill-button svg {
			width: 16px;
			height: 16px;
			flex: 0 0 auto;
		}

		.page-shell {
			width: min(100% - 32px, 860px);
			margin: 46px auto 56px;
		}

		.preview-header {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 24px;
			margin-bottom: 28px;
		}

		.preview-header h1 {
			margin: 0;
			font-size: 20px;
			line-height: 1.2;
			font-weight: 800;
			letter-spacing: 0;
		}

		.preview-header p {
			margin: 6px 0 0;
			color: #111827;
			font-size: 15px;
		}

		.preview-actions {
			display: flex;
			align-items: center;
			justify-content: flex-end;
			gap: 10px;
			flex-wrap: wrap;
		}

		.placeholder-card {
			min-height: 680px;
			display: grid;
			place-items: center;
			overflow: hidden;
			border: 1px solid #dce2e8;
			border-radius: 14px;
			background: #ffffff;
			box-shadow: 0 1px 2px rgba(15, 23, 42, 0.02);
		}

		.placeholder-content {
			width: min(100% - 56px, 620px);
			min-height: 390px;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			gap: 18px;
			border: 1px dashed #c9d3df;
			border-radius: 14px;
			background: linear-gradient(135deg, rgba(39, 155, 232, 0.08), rgba(39, 155, 232, 0) 48%),
			#fbfdff;
			color: var(--muted);
			text-align: center;
			padding: 36px;
		}

		.placeholder-icon {
			width: 72px;
			height: 72px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			border-radius: 18px;
			background: rgba(39, 155, 232, 0.1);
			color: var(--blue);
		}

		.placeholder-icon svg {
			width: 34px;
			height: 34px;
		}

		.placeholder-content h2 {
			margin: 0;
			color: var(--ink);
			font-size: 22px;
			line-height: 1.2;
			font-weight: 800;
			letter-spacing: 0;
		}

		.placeholder-content p {
			width: min(100%, 390px);
			margin: 0;
			font-size: 15px;
		}

		.signup-footer {
			margin-top: 34px;
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 24px;
			border-radius: 14px;
			background: var(--cta);
			padding: 24px 36px;
			box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
		}

		.signup-footer h2 {
			margin: 0;
			font-size: 18px;
			line-height: 1.25;
			font-weight: 800;
			letter-spacing: 0;
		}

		.signup-footer p {
			margin: 5px 0 0;
			color: #111827;
			font-size: 15px;
		}

		@media (max-width: 760px) {
			.nav {
				width: min(100% - 24px, 1120px);
				min-height: 68px;
				gap: 16px;
			}

			.brand img {
				width: 118px;
			}

			.nav-actions {
				gap: 12px;
				font-size: 13px;
			}

			.nav-actions .pill-button {
				min-height: 38px;
				padding-inline: 16px;
			}

			.page-shell {
				width: min(100% - 24px, 860px);
				margin-top: 30px;
			}

			.preview-header,
			.signup-footer {
				align-items: flex-start;
				flex-direction: column;
			}

			.preview-actions {
				justify-content: flex-start;
			}

			.placeholder-card {
				min-height: 520px;
			}

			.placeholder-content {
				width: min(100% - 32px, 620px);
				min-height: 320px;
				padding: 28px;
			}

			.signup-footer {
				padding: 22px 24px;
			}
		}

		@media (max-width: 480px) {
			.nav {
				flex-wrap: wrap;
				padding: 14px 0;
			}

			.nav-actions {
				width: 100%;
				justify-content: space-between;
			}

			.nav-actions .pill-button,
			.preview-actions .pill-button {
				width: auto;
			}

			.preview-actions {
				width: 100%;
			}

			.preview-actions .pill-button {
				flex: 1 1 160px;
			}

			.signup-footer .pill-button {
				width: 100%;
			}
		}
	</style>
</head>
<body>
<header class="site-header">
	<nav class="nav" aria-label="Main navigation">
		<a class="brand" href="/" aria-label="Billifty home">
			<img src="{{ asset('images/Billifty-logo-light.png') }}" alt="Billifty">
		</a>
		<div class="nav-actions">
			<a class="link-button" href="/login">Sign In</a>
			<a class="pill-button" href="/register">Sign Up Free</a>
		</div>
	</nav>
</header>

<main class="page-shell">
	<section class="preview-header" aria-labelledby="invoice-preview-title">
		<div>
			<h1 id="invoice-preview-title">Invoice Preview</h1>
		</div>
		<div class="preview-actions">
			<a class="pill-button secondary" href="#">
				<svg aria-hidden="true" viewBox="0 0 24 24" fill="none">
					<path d="M12 3v11m0 0 4-4m-4 4-4-4M5 19h14" stroke="currentColor" stroke-width="2"
						  stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				Download PDF
			</a>
		</div>
	</section>

	<section class="placeholder-card" aria-label="Invoice preview">
		@include('invoicing::templates.show', [
			'invoice' => $invoice,
			'category' => $category,
			'colorScheme' => $colorScheme,
			'renderContext' => $renderContext,
		])
	</section>

	<footer class="signup-footer">
		<div>
			<h2>Create invoices like this in seconds</h2>
			<p>Sign up free and start billing your clients professionally.</p>
		</div>
		<a class="pill-button" href="/register">
			<svg aria-hidden="true" viewBox="0 0 24 24" fill="none">
				<path d="m22 2-7 20-4-9-9-4 20-7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"
					  stroke-linejoin="round"/>
				<path d="M22 2 11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"
					  stroke-linejoin="round"/>
			</svg>
			Get Started Free
		</a>
	</footer>
</main>
</body>
</html>
