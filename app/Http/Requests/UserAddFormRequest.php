<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Session;

class UserAddFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Session::has('page') && Session::get('page') == 'page_input') {

            return true;
        } else {
            $this->error = '入力画面を経由せずに直接参照されました。';

            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
