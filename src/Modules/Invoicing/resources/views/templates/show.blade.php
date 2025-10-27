{{-- /src/Modules/Invoicing/resources/views/templates/show.blade.php --}}
@php
  // === your boilerplate ===
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

  $fmtMoney = function ($cents, $currency = 'USD') {
      $val = ($cents ?? 0) / 100;
      try {
          $fmt = new \NumberFormatter(\Locale::getDefault() ?: 'en_US', \NumberFormatter::CURRENCY);
          return $fmt->formatCurrency($val, $currency);
      } catch (\Throwable $e) {
          return number_format($val, 2) . ' ' . $currency;
      }
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

  // Decide which visual template to render (DB-driven or fallback)
  $template = $invoice->template->view ?? 'modern.v1.aurora';
  $template = preg_replace('/^templates\./', '', $template); // just in case

@endphp


@extends('invoicing::templates.main')

@section('content')
<div class="container">
	@include("invoicing::templates.$template")
</div>
@endsection

@section('globalcss')
<style>
.col-6 {
		float: left;
    width: 45%;
}
.left {float: left;}
.right {float: right;}
.pb-4, .py-4 {
    padding-bottom: 1.5rem !important;
}
.pt-4, .py-4 {
    padding-top: 1.5rem !important;
}
.row {display: block; clear: both;width: 100%;}
/*.container, .container-lg, .container-md, .container-sm, .container-xl {*/
/*	max-width: 1140px;*/
/*}*/
.clearfix::after {
  content: "";
  display: table;
  clear: both;
}
.row { width: 100%; }
.row::after { content: ""; display: table; clear: both; }
h1, h2, h3 {margin: 0;}
.to-right {float: right;}
.to-left {float: left;}
.text-right {
	text-align: right;
}
.text-left {text-align: left;}

</style>
@endsection
