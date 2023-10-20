<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'=>'max:200',
            'item_price'=>'numeric',
            'category_id'=>'numeric',
            'user_id'=>'numeric',
            'transaction_id'=>'numeric',
            'quantity'=>'numeric',
            'image.*'=>'file|mimes:jpeg,png,jpg,gif,svg',
        ];
    }
}
