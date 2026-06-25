<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
		$plans = Plan::with('capabilities')
			->orderBy('sort_order')
			->orderBy('id')
			->get()
			->map(fn (Plan $plan) => $this->planToArray($plan))
			->values();

		return response()->json($plans);
    }

	private function planToArray(Plan $plan): array
	{
		return [
			'code'          => $plan->code,
			'actions'       => $this->actions($plan),
			'name'          => $plan->name,
			'description'   => $plan->description,
			'monthly'       => (float) $plan->price_monthly,
			'yearly'        => $plan->price_yearly === null ? null : (float) $plan->price_yearly,
			'limits'        => $this->limits($plan),
			'features'      => $this->features($plan),
			'billing'       => $this->billingCycles($plan),
			'features_text' => $this->featuresText($plan),
		];
	}

	private function limits(Plan $plan): array
	{
		return [
			'business_profiles'  => $plan->capabilityInt('max_business_profiles', null),
			'clients'            => $plan->capabilityInt('max_clients', null),
			'invoices_per_month' => $plan->capabilityInt('max_invoices_per_month', null),
		];
	}

	private function features(Plan $plan): array
	{
		$caps = $plan->capabilitiesArray();

		return [
			'pdf_export'      => true,
			'pdf_watermark'   => (bool) ($caps['pdf_watermark'] ?? true),
			'email_watermark' => (bool) ($caps['email_watermark'] ?? true),
			'custom_prefix'   => (bool) ($caps['custom_prefix'] ?? false),
			'custom_branding' => (bool) ($caps['custom_branding'] ?? false),
			'templates'       => (string) ($caps['templates_tier'] ?? 'basic'),
			'logo_upload'     => (bool) ($caps['logo_upload'] ?? false),
			'automated_reminders' => $caps['automated_reminders'] ?? 'none',
			'online_payments' => (bool) ($caps['online_payments'] ?? false),
			'multi_currency'  => (bool) ($caps['multi_currency'] ?? false),
			'ai_invoice_assistant' => (bool) ($caps['ai_invoice_assistant'] ?? false),
			'email_branding'  => $caps['email_branding'] ?? 'billifty_footer',
			'support'         => $caps['support_level'] ?? 'email',
			'analytics'       => $caps['analytics_tier'] ?? null,
			'raw'             => $caps,
		];
	}

	private function actions(Plan $plan): array
	{
		$marketing = $plan->capabilities
			->where('group', 'marketing')
			->mapWithKeys(fn ($cap) => [$cap->key => $cap->cast_value]);

		return [
			'text1'      => $marketing['cta_text1'] ?? null,
			'btn'        => $marketing['cta_btn'] ?? null,
			'upper_text' => $marketing['cta_upper_text'] ?? null,
			'card_label' => $marketing['cta_card_label'] ?? null,
		];
	}

	private function featuresText(Plan $plan): array
	{
		$additional = match ($plan->code) {
			'pro'     => ['All Free features'],
			'premium' => ['All Pro features'],
			default   => [],
		};

		$bullets = $plan->capabilities
			->where('group', 'features')
			->filter(fn ($cap) => (string) ($cap->description ?? '') !== '')
			->sortBy(fn ($cap) => (int) (($cap->meta['sort'] ?? 9999)))
			->map(fn ($cap) => (string) $cap->description)
			->values()
			->all();

		return array_values(array_unique(array_merge($additional, $bullets)));
	}

	private function billingCycles(Plan $plan): array
	{
		$monthly = (float) $plan->price_monthly;
		$yearly = $plan->price_yearly === null ? null : (float) $plan->price_yearly;

		return [
			'monthly' => [
				'price'    => $monthly,
				'interval' => 'monthly',
			],
			'yearly' => [
				'price'             => $yearly ?? 0,
				'interval'          => 'yearly',
				'discount_applied'  => $yearly !== null && $yearly > 0,
				'discount_percent'  => 20,
			],
		];
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

	public function lookUpPlan(Request $request)
	{
		$plans = Plan::with('capabilities')
			->orderBy('sort_order')
			->orderBy('id')
			->get()
			->map(fn (Plan $plan) => $plan->code === trim($request->plan) ? $this->planToArray($plan) : null)
			->filter()
			->values()
			->flatMap(fn ($p) => [...$p]);

		return response()->json($plans);
	}
}
