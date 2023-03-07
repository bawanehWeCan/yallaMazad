<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'id'        => $this->id,
            'name'      => $this->name,
            'confirm'   => $this->confirm,
            'email'     => $this->email,
            'image'     => (string)$this->image,
            'phone'     => (string)$this->phone,
            'device_token'=> $this->device_token,
            'badges'     => BadgeResource::collection( $this->badges ),

        ];
    }
}
