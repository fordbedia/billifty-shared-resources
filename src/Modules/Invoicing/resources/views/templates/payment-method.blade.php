@php
use SimpleSoftwareIO\QrCode\Facades\QrCode;

	$paymentInformation = data_get($invoice, 'businessProfile.paymentInformation')
		?? data_get($invoice, 'businessProfile.payment_information');

	$paymentMethod = strtolower((string) ($paymentInformation->payment_method ?? ''));
	$isStripe = in_array($paymentMethod, ['stripe'], true);

	$paymentToken = data_get($invoice, 'paymentLink.token');
	$payUrl = $paymentToken
		? rtrim(config('app.frontend_url', config('app.url')), '/') . '/app/pay/' . $paymentToken
		: null;

	$qrCodeSvg = $isStripe && $payUrl
		? QrCode::size(50)->format('svg')->generate($payUrl)
		: null;

	$qrCodeSrc = $qrCodeSvg
		? 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg)
		: null;

@endphp

@if ($qrCodeSrc && $invoice->businessProfile?->payment_information?->payment_method === 'stripe')
	<img
	  src="{{ $qrCodeSrc }}"
	  alt="Invoice payment QR code"
	/>
	Click <a target="_blank" href="{{$payUrl}}">Here</a>
@endif
