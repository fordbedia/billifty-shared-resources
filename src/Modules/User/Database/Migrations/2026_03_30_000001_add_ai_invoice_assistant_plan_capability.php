<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $plans = DB::table('plans')
            ->whereIn('code', ['free', 'pro', 'premium'])
            ->pluck('id', 'code');

        if ($plans->isEmpty()) {
            return;
        }

        $rows = [];

        $add = function (string $planCode, string $value, ?string $description) use (&$rows, $plans, $now) {
            if (! isset($plans[$planCode])) {
                return;
            }

            $rows[] = [
                'plan_id' => $plans[$planCode],
                'group' => 'features',
                'key' => 'ai_invoice_assistant',
                'label' => 'AI Invoice Assistant',
                'description' => $description,
                'type' => 'bool',
                'value' => $value,
                'meta' => null,
                'model_relationship' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        };

        $add('free', 'false', null);
        $add('pro', 'true', 'AI invoice assistant chat');
        $add('premium', 'true', 'AI invoice assistant chat');

        if ($rows === []) {
            return;
        }

        DB::table('plan_capabilities')->upsert(
            $rows,
            ['plan_id', 'key'],
            ['group', 'label', 'description', 'type', 'value', 'meta', 'model_relationship', 'is_active', 'updated_at']
        );
    }

    public function down(): void
    {
        DB::table('plan_capabilities')
            ->where('key', 'ai_invoice_assistant')
            ->delete();
    }
};
