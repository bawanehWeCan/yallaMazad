<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'details'=>(string)$this->details,
            'price'=>$this->price,
            'number_of_auction'=>$this->number_of_auction,
            'time'=>$this->time,
            'point_one'=>$this->point_one,
            'point_two'=>$this->point_two,
            'point_three'=>$this->point_three,

        ];
    }
}
