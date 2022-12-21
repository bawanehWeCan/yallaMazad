<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementResource extends JsonResource
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
            'name'=>$this->name,
            'content'=>$this->content,
            'start_price'=>$this->start_price,
            'start_date'=>(string)$this->start_date,
            'end_date'=>$this->end_date,
            'status'=>$this->status,
            'buy_now_price'=>$this->buy_now_price,
            'views'=>$this->views,
            'number_of_bids'=>$this->number_of_bids,
            'image'=>(string)$this?->image,
            'user'=>UserResource::make($this->user),
            'category'=>CategoryResource::make($this?->category),
            'images'=> ImageResource::collection($this?->images),
        ];
    }
}
