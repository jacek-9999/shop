<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class ModifyLogTest extends TestCase
{
    public function testModifyLog(): void
    {
        DB::insert(
            'insert into modify_log (created_at, user, shop, product_ean, event_type) values (?, ?, ?, ?, ?)',
            [date('Y-m-d H:i:s'), 9999999, 888888, 1234567890123,'log test'],
        );
    }
}
