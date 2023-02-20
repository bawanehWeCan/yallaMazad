<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            'title'=>$this->title,
            'content'=>$this->content,
            'advertisment'=>new AdvertisementResource($this?->advertisement),
            'receiver'=>new UserResource($this?->user),
            'sender'=>new UserResource($this?->sender),


        ];
    }
}
