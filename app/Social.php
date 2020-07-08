<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    protected $guarded = [];

    public static function fetchData($value='')
    {
        $obj = self::whereNOTNULL('id');

            if(isset($value['locale'])) {
                app()->setLocale($value['locale']);
            }
            
            if(isset($value['status'])) {
                $obj->where('status', $value['status']);
            } else {
                $obj->where('status', true);
            }

            if(isset($value['order'])) {
                $obj->orderBy('id', $value['order']);
            } else {
                $obj->orderBy('id', 'DESC');
            }

        $obj = $obj->paginate($value['paginate'] ?? 10);
        return $obj;
    }

    public static function createOrUpdate($id, $value)
    {
        try {

            $row               = (isset($id)) ? self::find($id) : new self;
            $row->provider     = $value['provider'];
            $row->provider_url = $value['provider_url'];
            $row->icon         = $value['icon'];
            $row->status       = $value['status'];
            $row->save();

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
