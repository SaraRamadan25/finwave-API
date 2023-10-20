<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvestmentRequest extends FormRequest
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
            'price'=>'required|numeric',
            'image.*'=>'required|file|mimes:jpeg,png,jpg,gif,svg',
            'videos.*'=>'nullable|file|mimes:mp4,mov,ogg,qt,mkv',
            'description'=>'required',
            'user_id'=>'required|exists:users,id',
        ];
    }
}
