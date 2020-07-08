<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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

            // Parent
            'parent_id'     => $this->parent_id,
            'childs'        => ($this->childs) ? self::collection($this->childs) : NULL,

            // Data
             'slug'         => $this['slug'],
             'name'         => $this['name'],

            // Translations
            'en' => [
                'slug'      => $this->translate('en')['slug'],
                'name'      => $this->translate('en')['name'],
            ],
            'ar' => [
                'slug'      => $this->translate('ar')['slug'],
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
