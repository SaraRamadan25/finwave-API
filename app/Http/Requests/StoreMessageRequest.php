<?php

namespace App\Http\Requests;

use App\Models\Chat;
use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        $chatModel = get_class(new Chat());

        return [
            'chat_id'=>"required|exists:{$chatModel},id",
            'message'=>'required|string',
        ];
    }
}
