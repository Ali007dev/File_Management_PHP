<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Rules\FileRules;

class CreateFileRequest extends FormRequest
{
    public function rules()
    {
        return FileRules::required()
            ->getRules();
    }

    public function messages(){
        return FileRules::getMessages();
    }
}
