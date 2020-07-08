<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'encrypt_id'    => encrypt($this->id),
            'image'         => ($this->image) ? \Request::root().'/'.$this->image->url : NULL,

            'name'          => $this->name,
            'email'         => $this->email,
            'role'          => $this->getRoleNames()[0],

            // Dates
            'dateForHumans' => $this->created_at->diffForHumans(),
            'timestamp'     => $this->created_at,

            // Visibilty 
            'loading'       => false,
        ];
    }
}
