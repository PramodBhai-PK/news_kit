<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'bio' => $this->bio,
            'avatar' => $this->avatar_url ?? null,
            'press_card' => $this->press_id_code ? [
                'code' => $this->press_id_code,
                'designation' => $this->designation,
                'blood_group' => $this->blood_group,
                'bureau_location' => $this->bureau_location,
                'status' => $this->id_card_status,
                'issued_at' => $this->id_card_issued_at?->format('Y-m-d'),
                'expires_at' => $this->id_card_expires_at?->format('Y-m-d'),
                'is_valid' => $this->isPressCardValid(),
                'verify_url' => route('portal.press.verify', $this->press_id_code),
            ] : null,
        ];
    }
}
