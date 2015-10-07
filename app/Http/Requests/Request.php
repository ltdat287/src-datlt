<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    //A generic error message, can be overridden in the sub class
    protected $error = 'システムエラーが発生しました。';

    public function forbiddenResponse()
    {
        return redirect('errors.system_error')->with('errors', $this->error);
    }
}
