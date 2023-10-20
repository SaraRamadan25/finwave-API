<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'total_spending' => $this->total_spending,
            'total_savings' => $this->total_savings,
            'current_balance' => $this->current_balance,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'user' => $this->user->name,
            'transactions' => TransactionResource::collection($this->transactions),
        ];
    }
}
