<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Tests\Services;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\PaymentLinkRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Services\InvoicePaymentLinkServices;
use BilliftySDK\SharedResources\SDK\Application\Ports\Transactional;
use BilliftySDK\SharedResources\TestCase\Migrations\BaseTest;
use BilliftySDK\SharedResources\TestCase\Scenario\CreateInvoice;
use Carbon\Carbon;

class InvoicePaymentLinkServicesTest extends BaseTest
{
    protected $scenario;

    public function setUp(): void
    {
        parent::setUp();

        $this->scenario = (new CreateInvoice())();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    /** @test */
    public function it_creates_a_payment_link_for_an_invoice_inside_a_transaction(): void
    {
        $invoice = $this->scenario['invoice'];
        $payload = ['expires_at' => Carbon::parse('2026-06-03 12:00:00')];
        $repo = new FakePaymentLinkRepository();
        $db = new FakeTransactional();

        (new InvoicePaymentLinkServices($repo, $db))->createForInvoice($invoice, $payload);

        $this->assertSame(1, $db->runs);
        $this->assertSame($invoice->id, $repo->savedInvoice?->id);
        $this->assertSame($payload, $repo->savedPayload);
    }

    /** @test */
    public function it_generates_an_expiry_one_week_from_now(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-27 09:15:00'));

        $expiresAt = (new InvoicePaymentLinkServices(new FakePaymentLinkRepository(), new FakeTransactional()))
            ->generateExpireAt();

        $this->assertSame('2026-06-03 09:15:00', $expiresAt->toDateTimeString());
    }
}

class FakePaymentLinkRepository implements PaymentLinkRepository
{
    public ?Invoices $savedInvoice = null;

    public array $savedPayload = [];

    public function saveForToken(Invoices $invoices, array $payload): void
    {
        $this->savedInvoice = $invoices;
        $this->savedPayload = $payload;
    }
}

class FakeTransactional implements Transactional
{
    public int $runs = 0;

    public function run(callable $fn)
    {
        $this->runs++;

        return $fn();
    }
}
