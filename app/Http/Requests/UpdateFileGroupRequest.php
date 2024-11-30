<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Rules\FileGroupRules;

class UpdateFileGroupRequest extends FormRequest
{
    public function rules()
    {
        return FileGroupRules::getRules();
    }

    public function messages(){
        return FileGroupRules::getMessages();
    }
}
