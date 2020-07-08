<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inbox extends Model
{
    protected $guarded = [];

    public function fetchData($value='')
    {
        $obj = self::whereNOTNULL('id');


            if(isset($value['search'])) {
                $obj->where(function($q){
                    $q->where('name', 'like','%'.$value['search'].'%')
                    $q->orWhere('email', 'like', '%'.$value['search'].'%')
                    $q->orWhere('mobile', $value['search'])
                    $q->orWhere('id', $value['search']);
                });
            }


            if(isset($value['order'])) {
                $obj->orderBy('id', $value['order']);
            } else {
                $obj->orderBy('id', 'DESC');
            }

        $obj = $obj->pagiante($value['pagiante'] ?? 10);
        return $obj;
    }


    public function createOrUpdate($value)
    {
        try {

            $row          = new self;
            $row->name    = $value['name'] ?? NULL;
            $row->email   = $value['email'] ?? NULL;
            $row->mobile  = $value['mobiel'] ?? NULL; 
            $row->subject = $value['subject'] ?? NULL;
            $row->body    = $value['body'] ?? NULL;
            $row->save();

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
