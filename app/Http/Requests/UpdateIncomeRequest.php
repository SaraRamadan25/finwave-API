<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIncomeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'title'=>'string',
            'description'=>'string',
            'amount_of_money'=>'numeric',
            'user_id'=>'exists:users,id',
        ];
    }
}
