<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $percentage = $this->target_amount > 0 ? ($this->saved_amount / $this->target_amount) * 100 : 0;

        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'target_amount'=>$this->target_amount,
            'saved_amount'=>$this->saved_amount,
            'percentage' => $percentage,
            'user'=>$this->user->name,
        ];
    }
}
