<?php

namespace BilliftySDK\SharedResources\Modules\User\Support;

use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\Modules\User\Models\User;

class PlanCapabilities
{
    public function __construct(
        public readonly Plan $plan,
        public readonly ?int $maxBusinessProfiles,
        public readonly ?int $maxClients,
        public readonly ?int $maxInvoicesPerMonth,
        public readonly bool $pdfWatermark,
        public readonly bool $emailWatermark,
        public readonly bool $allowsOnlinePayments,
        public readonly bool $allowsAutomatedReminders,
    ) {}

    public static function fromPlan(Plan $plan): self
    {
        return new self(
            plan: $plan,
            maxBusinessProfiles: $plan->max_business_profiles,
            maxClients: $plan->max_clients,
            maxInvoicesPerMonth: $plan->max_invoices_per_month,
            pdfWatermark: (bool) $plan->pdf_watermark,
            emailWatermark: (bool) $plan->email_watermark,
            allowsOnlinePayments: (bool) $plan->allows_online_payments,
            allowsAutomatedReminders: (bool) $plan->allows_automated_reminders,
        );
    }

    public function canCreateBusinessProfile(int $currentCount): bool
    {
        return is_null($this->maxBusinessProfiles)
            || $currentCount < $this->maxBusinessProfiles;
    }

    public function canCreateInvoiceThisMonth(int $currentCountThisMonth): bool
    {
        return is_null($this->maxInvoicesPerMonth)
            || $currentCountThisMonth < $this->maxInvoicesPerMonth;
    }

    public function toArrayForUser(User $user, array $context = []): array
    {
        $currentBusinessProfiles = $context['business_profiles_count'] ?? 0;
        $currentInvoicesThisMonth = $context['invoices_this_month'] ?? 0;

        $allowed = [
            'create_business_profile' =>
                $this->canCreateBusinessProfile($currentBusinessProfiles),
            'create_invoice' =>
                $this->canCreateInvoiceThisMonth($currentInvoicesThisMonth),
            'online_payments' => $this->allowsOnlinePayments,
            'automated_reminders' => $this->allowsAutomatedReminders,
        ];

        // Derived not_allowed from allowed
        $notAllowed = [];
        foreach ($allowed as $key => $value) {
            $notAllowed[$key] = !$value;
        }

        return [
            'plan' => [
                'id'   => $this->plan->id,
                'code' => $this->plan->code,
                'name' => $this->plan->name,
            ],
            'limits' => [
                'max_business_profiles'    => $this->maxBusinessProfiles,
                'max_clients'              => $this->maxClients,
                'max_invoices_per_month'   => $this->maxInvoicesPerMonth,
                'current_business_profiles' => $currentBusinessProfiles,
                'current_invoices_this_month' => $currentInvoicesThisMonth,
            ],
            'flags' => [
                'pdf_watermark'   => $this->pdfWatermark,
                'email_watermark' => $this->emailWatermark,
            ],
            'allowed'    => $allowed,
            'not_allowed'=> $notAllowed,
        ];
    }
}