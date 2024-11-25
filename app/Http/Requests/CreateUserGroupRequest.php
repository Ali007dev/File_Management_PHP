<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Rules\UserGroupRules;

class CreateUserGroupRequest extends FormRequest
{
    public function rules()
    {
        return UserGroupRules::required()
            ->getRules();
    }

    public function messages(){
        return UserGroupRules::getMessages();
    }
}
