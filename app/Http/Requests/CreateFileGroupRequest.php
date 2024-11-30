<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Rules\FileGroupRules;

class CreateFileGroupRequest extends FormRequest
{
    public function rules()
    {
        return FileGroupRules::required()
            ->getRules();
    }

    public function messages(){
        return FileGroupRules::getMessages();
    }
}
