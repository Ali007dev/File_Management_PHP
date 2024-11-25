<?php
namespace App\Traits;

use App\Models\Scopes\NewestFirstScope;

Trait HasNewestFirstScope {
    protected static function bootHasNewestFirstScope()
    {
        static::addGlobalScope(new NewestFirstScope);
    }
}

