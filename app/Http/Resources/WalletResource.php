<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'wallet_balance' => $this->cash_balance,
            'spent' => $this->resource->transactions()->where('type', 'expense')->sum('amount'),
            'left' => $this->cash_balance - $this->resource->transactions()->where('type', 'expense')->sum('amount'),
        ];
    }
}
