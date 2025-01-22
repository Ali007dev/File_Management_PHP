<?php
namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionEvent
{
    use Dispatchable, SerializesModels;

    public $method;
    public $parameters;
    public $type; // 'start', 'commit', 'rollback'

    public function __construct($type, $method = null, $parameters = [])
    {
        $this->type = $type;
        $this->method = $method;
        $this->parameters = $parameters;
    }
}
