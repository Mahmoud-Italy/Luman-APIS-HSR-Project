<?php

namespace App;

use DB;
use App\Image;
use App\CategoryTranslation;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Category extends Model implements TranslatableContract
{
    use Translatable;
    public $translatedAttributes = ['slug', 'name'];

    protected $guarded = [];

    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function childs() {
        return $this->hasMany(__NAMESPACE__.'\\'.class_basename(new self), 'parent_id'); 
    }


    public static function fetchData($value='')
    {
        $obj = self::whereNOTNULL('id');

            if(isset($value['locale'])) {
                app()->setLocale($value['locale']);
            }

            if(isset($value['search'])) {
                $obj->whereTranslationLike('name', '%'.$value['search'].'%');
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

    public static function createOrUpdate($id, $value, $file)
    {
        try {

            // begin Transaction between tables
            DB::beginTransaction();

                // find Or New
                $row              = (isset($id)) ? self::find($id) : new self;
                
                    foreach (['en', 'ar'] as $locale) {
                        $row->translateOrNew($locale)->slug = $value[$locale]['slug'];
                        $row->translateOrNew($locale)->name = $value[$locale]['name'];
                    }

                $row->parent_id   = $value['parent_id'];
                $row->status      = $value['status'];
                $row->save();
                $row->touch(); // touch updated_at


                // upload Image if exist
                if(!is_null($file)) {
                    $img = Image::uploadFile($id, $file, __NAMESPACE__.'\\'.class_basename(new self));
                    $row->image()->save($img);
                }

            DB::commit();
            // End Commit of Transaction

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

}
