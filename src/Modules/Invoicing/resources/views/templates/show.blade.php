{{-- /src/Modules/Invoicing/resources/views/templates/show.blade.php --}}
@php
	// === your boilerplate ===
	use Illuminate\Support\Facades\Storage;
	$schemeMap = [
	  'Ocean Blue'    => 'ocean',
	  'Forest Green'  => 'forest',
	  'Royal Purple'  => 'royal',
	  'Crimson Red'   => 'crimson',
	  'Sunset Orange' => 'sunset',
	];
	$categoryMap = [
	  'Modern'  => 'modern',
	  'Classic' => 'classic',
	  'Minimal' => 'minimal',
	];

	  $categoryName = $category->slug;

	  $scheme = $colorScheme->colors;

	$category = $categoryMap[$categoryName ?? 'Modern'] ?? 'modern';

	$fmtMoney = function ($cents, object|string $currency = 'USD') {
		$val = ($cents ?? 0) / 100;
		$currency = is_string($currency) ? $currency : $currency->code;
		try {
			$fmt = new \NumberFormatter(\Locale::getDefault() ?: 'en_US', \NumberFormatter::CURRENCY);
			return $fmt->formatCurrency($val, $currency);
		} catch (\Throwable $e) {
			return number_format($val, 2) . ' ' . $currency;
		}
	};
	$fmtPercent = function ($value, int $decimals = 2) {
		$num = ($value ?? 0);

		// If it's stored like 5.0000, just render 5%
		// If your DB stores 0.05 for 5%, then multiply
		if($num < 1) {
			$num *= 100;
		}

		return number_format($num, $decimals) . '%';
	};
	$getCurrency = function(object|string $currency) {
		if (is_object($currency)) {
			return $currency->code;
		}
		return $currency;
	};
	$fmtDate = fn($d) => $d ? \Carbon\Carbon::parse($d)->toFormattedDateString() : '—';
	$addr = function ($x) {
		$g = is_array($x) ? $x : (method_exists($x, 'toArray') ? $x->toArray() : []);
		$parts = array_filter([
		  $g['address_line1'] ?? null,
		  $g['address_line2'] ?? null,
		  $g['city'] ?? null,
		  $g['state'] ?? null,
		  $g['postal_code'] ?? null,
		  $g['country'] ?? null,
		]);
		return implode(', ', $parts);
	};

	$bp = $invoice->businessProfile ?? null;
	$cl = $invoice->client ?? null;
	$items = $invoice->items ?? collect();

	$pi = $invoice->businessProfile?->payment_information;

	// Decide which visual template to render (DB-driven or fallback)
	$template = $invoice->template->view ?? 'modern.v1.aurora';
	$template = preg_replace('/^templates\./', '', $template); // just in case
	  // Auto detect image if being load from html or generating via Dompdf
	  $logoSrc = null;
	  $logoPath = $bp?->logo_path ?? null;

	  if ($logoPath) {
		  $context = $renderContext ?? 'html';   // default safely to 'html'
		  $defaultDisk = config('filesystems.default'); // e.g. 'public', 's3'
		  $isS3 = $defaultDisk === 's3';

		  if ($context === 'pdf' && !$isS3) {
			  // PDF + local/public disk → prefer local file path (Dompdf loves this)
			  $local = public_path('storage/'.$logoPath); // public/storage/...
			  if (file_exists($local)) {
				  $logoSrc = 'file://'.$local;
			  } else {
				  // fallback to URL if file doesn't exist for some reason
				  $logoSrc = url(Storage::url($logoPath));
			  }
		  } else {
			  // HTML preview OR S3 disk → use normal URL
			  $logoSrc = url(Storage::url($logoPath));
		  }
	  }

@endphp


@extends('invoicing::templates.main')

@section('content')
	<div class="invoice-page">
		@include("invoicing::templates.$template")
	</div>
@endsection

@section('globalcss')
	<style>
		body, .page, .invoice-root {
			font-family: "DejaVu Sans", sans-serif !important;
		}

		.col-6 {
			float: left;
			width: 45%;
		}

		.left {
			float: left;
		}

		.right {
			float: right;
		}

		.pb-4, .py-4 {
			padding-bottom: 1.5rem !important;
		}

		.pt-4, .py-4 {
			padding-top: 1.5rem !important;
		}

		.row {
			display: block;
			clear: both;
			width: 100%;
		}

		/*.container, .container-lg, .container-md, .container-sm, .container-xl {*/
		/*	max-width: 1140px;*/
		/*}*/
		.clearfix::after {
			content: "";
			display: table;
			clear: both;
		}

		.row {
			width: 100%;
		}

		.row::after {
			content: "";
			display: table;
			clear: both;
		}

		h1, h2, h3 {
			margin: 0;
		}

		.to-right {
			float: right;
		}

		.to-left {
			float: left;
		}

		.text-right {
			text-align: right;
		}

		.text-left {
			text-align: left;
		}

		@page {
			/* A4 portrait with normal margins */
			size: A4 portrait;
			margin: 15mm;
		}

		body {
			margin: 0;
			padding: 0;
			font-family: DejaVu Sans, sans-serif;
			font-size: 11px;
		}

		.invoice-page {
			/* Page width = 210mm - left/right margins (15 + 15) = 180mm */
			/* Keep it slightly smaller to avoid edge issues */
			width: 180mm;
			margin: 0 auto;
		}
		.row-cols {
		  width: 100%;
		}

		/* Shared column style */
		.col {
		  float: left;
		}

		/* Left column: 50% - gutter */
		.col-left {
		  width: 48%;
		  margin-right: 4%;
		}

		/* Right column */
		.col-right {
		  width: 48%;
		}
	</style>
@endsection
