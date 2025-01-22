<?php

namespace App\Listeners;

use App\Events\TransactionEvent;
use Illuminate\Support\Facades\DB;

class TransactionListener
{
    public function handle(TransactionEvent $event)
    {
        switch ($event->type) {
            case 'start':
                DB::beginTransaction();
                break;
            case 'commit':
                DB::commit();
                break;
            case 'rollback':
                DB::rollBack();
                break;
        }
    }
}
