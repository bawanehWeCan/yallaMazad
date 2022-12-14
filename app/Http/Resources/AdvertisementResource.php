<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;
use App\Models\Favorite;

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

        $fav = false;
        if(Auth::user()){
        $favorite = Favorite::where('user_id',Auth::user()->id)->where('advertisement_id',$this->id)->first();
            if($favorite){
                $fav = true ;
            }
    }
        return [

            'id'=>$this->id,
            'is_favorite'=>$fav,
            'name'=>$this->name,
            'content'=>$this->content,
            'start_price'=>$this->start_price,
            'start_date'=>(string)$this->start_date,
            'end_date'=>$this->end_date,
            'status'=>$this->status,
            'buy_now_price'=>$this->buy_now_price,
            'views'=>$this->views,
            'number_of_bids'=>$this->number_of_bids,
            'price_one'=>$this?->price_one,
            'price_two'=>$this?->price_two,
            'price_three'=>$this?->price_three,
            'image'=>(string)$this?->image,
            'user'=>UserResource::make($this->user),
            'category'=>CategoryResource::make($this?->category),
            'images'=> ImageResource::collection($this?->images),
        ];
    }
}
