<?php

namespace App\Http\Resources;

use App\Http\Resources\UserPositionResource;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\UserPosition;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'user_position' => new UserPositionResource(UserPosition::find($this->user_position_id)),
        ];
    }
}
