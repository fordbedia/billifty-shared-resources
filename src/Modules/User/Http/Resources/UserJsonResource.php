<?php

namespace BilliftySDK\SharedResources\Modules\User\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserJsonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
			'id' => $this->id,
			'fname' => $this->fname,
			'lname' => $this->lname,
			'plan_id' => $this->plan_id,
			'name' => $this->name,
			'email' => $this->email,
			'email_verified_at' => $this->email_verified_at,
			'avatar' => $this->avatar,
			'plan' => $this->plan,
			'capabilities' => $this->plan->capabilities,
			'plan_capabilities' => $this->plan_capabilities,
			'subscription' => $this->subscription,
			'provider' => $this->provider,
			'provider_id' => $this->provider_id,
			'stripe_customer_id' => $this->stripe_customer_id,
			'is_test' => $this->is_test,
			'image_url' => $this->image_url ?? null,
			'email_verified_date' => $this->email_verified_at->format('F j, Y'),
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
    }
}
