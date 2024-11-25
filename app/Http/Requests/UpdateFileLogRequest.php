<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Rules\FileLogRules;

class UpdateFileLogRequest extends FormRequest
{
    public function rules()
    {
        return FileLogRules::getRules();
    }

    public function messages(){
        return FileLogRules::getMessages();
    }
}