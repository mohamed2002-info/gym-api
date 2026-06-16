<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'first_name'           => $this->first_name,
            'last_name'            => $this->last_name,
            'email'                => $this->email,
            'phone'                => $this->phone,
            'join_date'            => $this->join_date?->toDateString(),
            'expiry_date'          => $this->expiry_date?->toDateString(),
            'status'               => $this->status, // accessor (active / expired)
            'subscription_type_id' => $this->subscription_type_id,
            // Relation chargée : type d'abonnement du membre
            'subscription_type'    => new SubscriptionTypeResource($this->whenLoaded('subscriptionType')),
            'created_at'           => $this->created_at,
        ];
    }
}
