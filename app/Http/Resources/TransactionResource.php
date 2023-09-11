<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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

            'id'=>$this->transaction_id,
            'order_number'=>$this->order_number,
            'order_amount'=>number_format($this->order_amount, 2, '.', ''),
            'order_currency'=>$this->order_currency,
            'order_description'=>$this->order_description,
            'type'=>$this->type,
            'status'=>$this->status,
            'reason'=>(string)$this?->reason,
            'card'=>$this->card,
            'date'=>$this->date,
            'hash'=>$this->hash,
            'card_expiration_date'=>$this->card_expiration_date,
            'customer_name'=>$this->customer_name,
            'customer_email'=>$this->customer_email,
            'customer_country'=>$this->customer_country,
            'customer_state'=>$this->customer_state,
            'customer_city'=>$this->customer_city,
            'customer_address'=>$this->customer_address,
            'customer_ip'=>$this->customer_ip,
            'exchange_rate_base'=>(string)$this?->exchange_rate_base,
            'exchange_rate'=>(string)$this?->exchange_rate,
            'exchange_currency'=>(string)$this?->exchange_currency,
            'exchange_amount'=>(string)$this?->exchange_amount,
            'merchantId'=>(string)$this?->merchantId,
            'rrn'=>(string)$this?->rrn,
            'approval_code'=>(string)$this?->approval_code,
            'card_token'=>(string)$this?->card_token,
            'recurring_init_trans_id'=>(string)$this?->recurring_init_trans_id,
            'recurring_token'=>(string)$this?->recurring_token,
            'card_token'=>(string)$this?->card_token,
            'schedule_id'=>(string)$this?->schedule_id,


        ];
    }
}
