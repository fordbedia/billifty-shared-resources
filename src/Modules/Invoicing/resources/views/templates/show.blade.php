{{-- /src/Modules/Invoicing/resources/views/templates/show.blade.php --}}
@php
	// === your boilerplate ===
	use BilliftySDK\SharedResources\Modules\Billing\Support\PlanPermission;
	use Illuminate\Support\Facades\Storage;

	$scheme = $colorScheme->colors;

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

	$fontFamily = data_get($theme ?? null, 'fontFamily', 'DejaVu Sans, Arial, sans-serif');
	$bp = $invoice->businessProfile ?? null;
	$cl = $invoice->client ?? null;
	$items = $invoice->items ?? collect();
	$itemCount = $items instanceof \Illuminate\Support\Collection ? $items->count() : (is_countable($items) ? count($items) : 0);
	$firstItem = $items instanceof \Illuminate\Support\Collection ? $items->first() : (is_array($items) ? reset($items) : null);
	$currency = $invoice->currency ?? 'USD';
	$totalDue = $invoice->amount_due_cents ?? $invoice->total_cents ?? 0;
	$subtotalCents = $invoice->subtotal_cents ?? 0;
	$taxCents = $invoice->tax_cents ?? 0;
	$discountCents = $invoice->discount_cents ?? 0;
	$shippingCents = $invoice->shipping_cents ?? 0;
	$bpAddress = $bp ? $addr($bp) : null;
	$clAddress = $cl ? $addr($cl) : null;
	$businessName = $bp?->name ?? 'Your Business';
	$clientName = $cl?->company ?: ($cl?->name ?? 'Client');
	$isShippingTaxable = (int) $invoice->shipping_tax_rate > 0;
	$taxLabel = $isShippingTaxable ? 'Tax (includes shipping)' : 'Tax';
	$hasDiscount = (int) $discountCents > 0;
	$hasShipping = (int) $shippingCents > 0;
	$hasLineDiscount = ($invoice->discount_mode ?? null) === 'per-line';
	$statusRaw = $invoice->status instanceof \BackedEnum ? $invoice->status->value : ($invoice->status ?? 'issued');
	$statusLabel = match ($statusRaw) {
		'issued', 'sent' => 'Pending Payment',
		'partially' => 'Partially Paid',
		default => ucwords(str_replace('_', ' ', (string) $statusRaw)),
	};
	$statusClass = match ($statusRaw) {
		'paid' => 'is-paid',
		'void' => 'is-void',
		'draft' => 'is-draft',
		default => 'is-pending',
	};
	$dueBaseDate = $invoice->issued_on ? \Carbon\Carbon::parse($invoice->issued_on)->startOfDay() : \Carbon\Carbon::today();
	$dueDate = $invoice->due_on ? \Carbon\Carbon::parse($invoice->due_on)->startOfDay() : null;
	$daysRemaining = $dueDate ? (int) round($dueBaseDate->diffInDays($dueDate, false)) : null;
	$dueLabel = match (true) {
		$daysRemaining === null => null,
		$daysRemaining > 1 => "Due in {$daysRemaining} days",
		$daysRemaining === 1 => 'Due tomorrow',
		$daysRemaining === 0 => 'Due today',
		$daysRemaining === -1 => 'Past due by 1 day',
		default => 'Past due by '.abs($daysRemaining).' days',
	};
	$paymentTerms = match (true) {
		$daysRemaining === null => null,
		$daysRemaining <= 0 => 'Due on receipt',
		default => "Net {$daysRemaining}",
	};
	$paymentInformations = collect(
		data_get($invoice, 'businessProfile.paymentInformations')
		?? data_get($invoice, 'businessProfile.payment_informations')
		?? []
	);
	$paymentMethodValue = function($paymentInfo) {
		$paymentMethodRaw = data_get($paymentInfo, 'payment_method');

		return $paymentMethodRaw instanceof \BackedEnum
			? $paymentMethodRaw->value
			: (string) $paymentMethodRaw;
	};
	$paymentMethodKey = function($paymentInfo) use ($paymentMethodValue) {
		$value = $paymentMethodValue($paymentInfo);

		return str_replace([' ', '-'], '_', strtolower(trim($value)));
	};
	$singlePaymentInformation = data_get($invoice, 'businessProfile.paymentInformation')
		?? data_get($invoice, 'businessProfile.payment_information');

	if ($singlePaymentInformation) {
		$paymentInformations = $paymentInformations
			->prepend($singlePaymentInformation)
			->unique(fn ($paymentInfo) => data_get($paymentInfo, 'id') ?? spl_object_id((object) $paymentInfo))
			->values();
	}
	$pi = $paymentInformations->first(fn ($paymentInfo) => $paymentMethodKey($paymentInfo) === 'bank_transfer');
	$currentPaymentMethodKey = $pi ? $paymentMethodKey($pi) : null;
	$isBankTransfer = $currentPaymentMethodKey === 'bank_transfer';
	$paymentToken = data_get($invoice, 'paymentLink.token');
	$payUrl = $paymentToken
		? rtrim(config('app.frontend_url', config('app.url')), '/') . '/app/pay/' . $paymentToken
		: null;
	$onlinePaymentMethodKeys = ['stripe', 'paypal', 'cash_app'];
	$hasOnlinePaymentMethod = $paymentInformations->contains(
		fn ($paymentInfo) => in_array($paymentMethodKey($paymentInfo), $onlinePaymentMethodKeys, true)
	);
	$bankTransferRows = function($paymentInfo) {
		if (!$paymentInfo) {
			return [];
		}

		$fields = [
			'bank_name' => 'Bank Name',
			'account_name' => 'Account Name',
			'account_number' => 'Account Number',
			'routing_number' => 'Routing Number',
			'iban' => 'IBAN',
			'swift_code' => 'Swift',
		];

		$rows = [];

		foreach ($fields as $field => $label) {
			$value = data_get($paymentInfo, $field);

			if ($value !== null && $value !== '') {
				$rows[$label] = $value;
			}
		}

		return $rows;
	};
	$bankTransferDetails = $bankTransferRows($pi);
	$hasBankTransferDetails = $isBankTransfer && count($bankTransferDetails) > 0;

	// Decide which visual template to render (DB-driven or fallback)
	$template = $invoice->template->view ?? 'modern.v1.aurora';
	$template = preg_replace('/^templates\./', '', $template); // just in case
	  // Auto detect image if being loaded from HTML preview or generated by the Playwright PDF container.
	  $logoSrc = null;
	  $logoPath = ltrim((string) ($bp?->logo_path ?? ''), '/');

	  if ($logoPath !== '') {
		  $context = $renderContext ?? 'html';
		  $logoDisk = $bp?->logo_disk ?: config('filesystems.default', 'public');
		  $logoDiskDriver = config("filesystems.disks.{$logoDisk}.driver");
		  $diskUrl = Storage::disk($logoDisk)->url($logoPath);
		  $publicLogoUrl = preg_match('#^https?://#i', $diskUrl) ? $diskUrl : url($diskUrl);

		  if ($context === 'pdf' && $logoDisk === 'public' && $logoDiskDriver === 'local') {
			  // The PDF is rendered in a separate Playwright container, so file:// paths from
			  // the backend container are not readable there. Use nginx's internal Docker URL.
			  $assetBaseUrl = rtrim((string) config(
				  'services.playwright_pdf.asset_base_url',
				  env('PLAYWRIGHT_PDF_ASSET_BASE_URL', 'http://nginx:8081')
			  ), '/');

			  $logoSrc = $assetBaseUrl !== ''
				  ? $assetBaseUrl.'/storage/'.$logoPath
				  : $publicLogoUrl;
		  } else {
			  $logoSrc = $publicLogoUrl;
		  }
	  }

	$paymentInfo = function(object $pi, string $baseClass = 'paymentinfo') use ($bankTransferRows, $paymentMethodKey) {
		if ($paymentMethodKey($pi) !== 'bank_transfer') {
			return '';
		}

        $html = "<ul class='".e($baseClass)."'>";

		foreach ($bankTransferRows($pi) as $label => $value) {
			$html .= "<li><span class='label'>".e($label).": </span><span class='value'>".e($value)."</span></li>";
		}

		$html .= "</ul>";

		return $html;
	};

	$getUserModel = function() use ($invoice) {
		return \BilliftySDK\SharedResources\Modules\User\Models\User::find($invoice->user_id);
	};

	$watermark = function() use ($getUserModel) {
		$user = $getUserModel();
		$hasWatermark = PlanPermission::attempt($user)->can('no pdf watermark');
		if ($hasWatermark) return;
		$html = '<div class="watermark">Powered by <strong>Billifty.com</strong></div>';

		return $html;
	};
	$fmtRate = function($value) {
		$num = (float) ($value ?? 0);

		if ($num > 0 && $num < 1) {
		  $num *= 100;
		}

		return rtrim(rtrim(number_format($num, 2), '0'), '.').'%';
	  };
	$discountLabel = 'Discount'.((float)($invoice->discount_rate ?? 0) > 0 ? ' ('.$fmtRate($invoice->discount_rate).')' : '');
	$fmtQuantity = function($item) {
		$value = data_get($item, 'quantity', 0);

		if (is_numeric($value)) {
			$quantity = rtrim(rtrim(number_format((float) $value, 4, '.', ''), '0'), '.');
		} else {
			$quantity = trim((string) $value);
		}

		$unit = trim((string) data_get($item, 'unit', ''));

		return trim(($quantity === '' ? '0' : $quantity).($unit !== '' ? ' '.$unit : ''));
	};
	$fmtItemUnitPrice = fn($item) => $fmtMoney(data_get($item, 'unit_price_cents', 0), $currency);
	$fmtItemTaxRate = fn($item) => $fmtRate(data_get($item, 'tax_rate', 0));
	$fmtItemLineDiscount = fn($item) => $fmtPercent(data_get($item, 'line_discount_rate', 0));
	$fmtItemLineTotal = fn($item) => $fmtMoney(data_get($item, 'line_total_cents', 0), $currency);

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
		.watermark {
			margin-top: 17px;
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

		.watermark {
			text-align: center;
			font-size: 18px;
		}

		/**------------------------- PDF Safe ------------------------*/
		/* Section Wrapper */
		.pdf-section {
			background: #f4f4f4;
			padding: 16px 22px;
			clear: both;
			overflow: visible; /* SAFE */
			display: block;
		}

		/* Title */
		.pdf-section .section-title {
			font-size: 14px;
			margin: 0 0 10px 0;
		}

		/* The Table Layout (SUPER SAFE) */
		.pdf-table {
			width: 100%;
			border-collapse: collapse;
		}

		.pdf-col {
			vertical-align: top;
			padding: 0;
		}

		/* Column widths */
		.left-col {
			width: 40%;
			padding-right: 12px;
		}

		.right-col {
			width: 60%;
			padding-left: 12px;
		}

		/* Each Box/Card */
		.pdf-box {
			background: #fff;
			padding: 20px;
			box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
			margin-top: 4px;
			border-radius: 6px; /* optional */
		}
	</style>
@endsection
