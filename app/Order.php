<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public static function fetchData($value='')
    {
        $obj = self::whereNOTNULL('id');

            if(isset($value['locale'])) {
                app()->setLocale($value['locale']);
            }

            if(isset($value['search'])) {
                $obj->where('id', $value['search']);
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

            // begin Transaction between tables
            DB::beginTransaction();

                // find Or New
                $row              = (isset($id)) ? self::find($id) : new self;
                $row->parent_id   = $value['parent_id'];
                $row->status      = $value['status'];
                $row->save();


                //$cart = Cart::where()->update();

            DB::commit();
            // End Commit of Transaction

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
