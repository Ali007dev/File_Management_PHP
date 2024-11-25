<?php
namespace App\Models\Rules;
class GroupRules extends BaseRules
{
    // Define the rules specific to the model
    protected function defineRules(): array
    {
        return [
            'name'=>['string']
        ];
    }

    // Define custom messages specific to the model
    protected function defineMessages(): array
    {
        return [
        ];
    }
}

