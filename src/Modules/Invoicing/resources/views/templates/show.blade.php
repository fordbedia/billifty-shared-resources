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

  $scheme   = $schemeMap[$schemeName ?? 'Ocean Blue'] ?? 'ocean';
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
<div class="container py-4">
  <div class="row">
    <div class="col-md-6">
      <div class="card p-3 shadow-sm">
        {{-- include the visual template; it inherits ALL vars above --}}
				@include("invoicing::templates.$template")
      </div>
    </div>
  </div>
</div>
@endsection
