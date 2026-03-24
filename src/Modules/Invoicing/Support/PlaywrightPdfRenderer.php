<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Support;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class PlaywrightPdfRenderer
{
    public function render(string $html, array $options = []): string
    {
        $baseUrl = rtrim(
            (string) config('services.playwright_pdf.url', env('PLAYWRIGHT_PDF_SERVICE_URL', 'http://playwright:3000')),
            '/'
        );
        $endpoint = $baseUrl . '/render-pdf';

        $timeout = (int) config('services.playwright_pdf.timeout', env('PLAYWRIGHT_PDF_TIMEOUT', 60));

        $response = Http::timeout($timeout)
            ->acceptJson()
            ->post($endpoint, [
                'html' => $html,
                'options' => $options,
            ]);

        if (!$response->successful()) {
            throw new RuntimeException(
                sprintf('Playwright PDF service error (%d): %s', $response->status(), $response->body())
            );
        }

        $binary = $response->body();
        if ($binary === '') {
            throw new RuntimeException('Playwright PDF service returned an empty payload.');
        }

        return $binary;
    }
}
