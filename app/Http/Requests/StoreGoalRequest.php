<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGoalRequest extends FormRequest
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
            'title'=>'required|string',
            'description'=>'required|string',
            'amount_of_money'=>'required|numeric',
            'money_limit'=>'required|numeric',
            'user_id'=>'required|exists:users,id',
        ];
    }
}
