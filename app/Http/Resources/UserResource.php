<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'img'           => $this->img,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'address'       => $this->address,
            'donor_info'    => new DonorResource($this->donor)
        ];
    }
}
