<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetChatRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'is_private'=>'nullable|boolean'
        ];
    }
}
