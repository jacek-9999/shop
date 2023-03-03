<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ModifyLogService
{
    public function log(int $userId, string $eventType, int $shopId = 0, string $productSku = ''): void
    {
        DB::insert(
            'insert into modify_log (created_at, user, shop, product_sku, event_type) values (?, ?, ?, ?, ?)',
            [date('Y-m-d H:i:s'), $userId, $shopId, $productSku, $eventType],
        );
    }
}
