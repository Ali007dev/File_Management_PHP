<?php

namespace App\Models\Rules;

class UserRules extends BaseRules
{
    // Define the rules specific to the model
    protected function defineRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'number' => 'string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ];
    }

    // Define custom messages specific to the model
    protected function defineMessages(): array
    {
        return [];
    }
}
