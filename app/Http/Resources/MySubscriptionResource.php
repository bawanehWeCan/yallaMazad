<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class MySubscriptionResource extends JsonResource
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
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
             'status'=>
             (( \Carbon\Carbon::now()->between($this->start_date,$this->end_date)) ? __('Active') : __('Out od Date')),
             'user'=>UserResource::make($this->user),
            'plan'=>PlanResource::make($this->plan),
        ];
    }
}
