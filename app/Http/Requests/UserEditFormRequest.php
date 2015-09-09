<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Helpers\MemberHelper;

class UserEditFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $valid = [
            'name'               => 'required|min:1|max:16',
            'kana'               => 'required|min:1|max:16',
            'email'              => 'required|vp_email|confirmed|max:255',
            'email_confirmation' => 'required',
            'telephone_no'       => 'required|vp_telephone|min:10|max:13',
            'birthday'           => 'required|date_format:' . VP_TIME_FORMAT . '|vp_date|min:10|max:10',
            'note'               => 'required|min:1|max:300',
            'password'           => 'required|between:8,32',
            'use_role'           => 'required|employee_to_boss:boss_id',
            'boss_id'            => 'boss_with_employee:use_role',
        ];

        if (MemberHelper::getCurrentUserRole() == 'employee') {
            unset($valid['email']);
            unset($valid['email_confirmation']);
            unset($valid['note']);
            unset($valid['use_role']);
        }

        if (MemberHelper::getCurrentUserRole() == 'boss') {
            unset($valid['use_role']);
            unset($valid['boss_id']);
        }
        
        return $valid;
    }
}
