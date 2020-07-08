<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Country extends JsonResource
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

            // Data
             'name'         => $this['name'],

            // Translations
            'en' => [
                'name'      => $this->translate('en')['name'],
            ],
            'ar' => [
                'name'      => $this->translate('ar')['name'],
            ],

            // Dates
            'dateForHumans' => $this->created_at->diffForHumans(),
            'timestamp'     => $this->created_at,

            // Status & Visibilty 
            'position'      => $this->position,
            'status'        => boolval($this->status),
            'loading'       => false,
        ];
    }
}
