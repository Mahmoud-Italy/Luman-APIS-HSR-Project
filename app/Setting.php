<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];


    public static function fetchData($value='')
    {
        $obj = self::whereNOTNULL('id');


        $obj = $obj->paginate($value['paginate'] ?? 10);
        return $obj;
    }

    public static function createOrUpdate($id, $value)
    {
        try {

            $row              = (isset($id)) ? self::find($id) : new self;
            $row->status      = $value['status'];
            $row->save();

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
