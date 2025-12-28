<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Tests\Models;

use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use BilliftySDK\SharedResources\TestCase\BaseTest;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscriptionTest extends BaseTest
{
    /** @test */
    public function it_appends_unit_amount_dollars(): void
    {
        $sub = new UserSubscription([
            'unit_amount' => 999,
        ]);

        // accessor via attribute name (snake_case)
        $this->assertSame(9.99, $sub->unit_amount_dollars);
    }

    /** @test */
    public function unit_amount_dollars_is_null_when_unit_amount_is_null(): void
    {
        $sub = new UserSubscription([
            'unit_amount' => null,
        ]);

        $this->assertNull($sub->unit_amount_dollars);
    }

    /** @test */
    public function it_casts_raw_payload_to_array(): void
    {
        $sub = new UserSubscription();

        // simulate what Eloquent would do after hydration
        $sub->raw_payload = ['hello' => 'world'];

        $this->assertIsArray($sub->raw_payload);
        $this->assertSame('world', $sub->raw_payload['hello']);
    }

    /** @test */
    public function it_defines_user_relationship(): void
    {
        $sub = new UserSubscription();

        $rel = $sub->user();
        $this->assertInstanceOf(BelongsTo::class, $rel);
        $this->assertSame((new User)->getTable(), $rel->getRelated()->getTable());
    }

    /** @test */
    public function it_defines_plan_relationship(): void
    {
        $sub = new UserSubscription();

        $rel = $sub->plan();
        $this->assertInstanceOf(BelongsTo::class, $rel);
        $this->assertSame((new Plan)->getTable(), $rel->getRelated()->getTable());
    }
}
