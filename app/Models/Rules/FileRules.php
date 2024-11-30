<?php
namespace App\Models\Rules;
class FileRules extends BaseRules
{
    // Define the rules specific to the model
    protected function defineRules(): array
    {
        return [
           // 'status' => ['string'],
            'file' =>['file'],
            'group_id' => ['integer', 'exists:groups,id'],


        ];
    }

    // Define custom messages specific to the model
    protected function defineMessages(): array
    {
        return [

        ];
    }
}

