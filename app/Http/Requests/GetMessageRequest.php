<?php

namespace App\Http\Requests;

use App\Models\Chat;
use Illuminate\Foundation\Http\FormRequest;

class GetMessageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $chatModel = get_class(new Chat());

        return [
            'chat_id' => "required|exists:{$chatModel},id",
            'page' => 'required|numeric',
            'page_size' => 'nullable|numeric',
        ];
    }
}
