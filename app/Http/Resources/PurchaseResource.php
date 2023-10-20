<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'item_price'=>$this->item_price,
            'category' => $this->category->name,
            'image'=>json_decode($this->image),
            'quantity'=>$this->quantity,
        ];
    }
}
