<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'=>'required|max:200',
            'item_price'=>'required|numeric',
            'category_id'=>'required|numeric',
            'user_id'=>'required|numeric',
            'transaction_id'=>'required|numeric',
            'quantity'=>'required|numeric',
            'image' => 'file|mimes:jpg,jpeg,png',
        ];
    }
}
