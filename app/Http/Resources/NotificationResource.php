<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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

            'id'=>$this->id,
            'content'=>$this->content,
            'route_id'=>(integer)$this?->route_id,
            'type'=>(string)$this?->type,
            'is_read'=>$this->is_read,
            'created_at'=>$this->created_at,
            'user'=> new UserResource($this?->user),
        ];
    }
}
