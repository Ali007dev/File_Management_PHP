<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Rules\FileLogRules;

class CreateFileLogRequest extends FormRequest
{
    public function rules()
    {
        return FileLogRules::required()
            ->getRules();
    }

    public function messages(){
        return FileLogRules::getMessages();
    }
}
