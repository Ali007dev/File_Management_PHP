<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Rules\UserGroupRules;

class UpdateUserGroupRequest extends FormRequest
{
    public function rules()
    {
        return UserGroupRules::getRules();
    }

    public function messages(){
        return UserGroupRules::getMessages();
    }
}
