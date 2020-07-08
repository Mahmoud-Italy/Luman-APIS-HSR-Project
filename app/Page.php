<?php

namespace App;

use DB;
use App\Image;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Page extends Model
{
    use Translatable;
    public $translatedAttributes = ['title', 'body'];

    protected $guarded = [];

    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }


    public function fetchData($value='')
    {
        $obj = self::whereNOTNULL('id');

            if(isset($value['locale'])) {
                app()->setLocale($value['locale']);
            }

            if(isset($value['search'])) {
                $obj->whereTranslationLike('title', '%'.$value['search'].'%');
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

        $obj = $obj->pagiante($value['pagiante'] ?? 10);
        return $obj;
    }

    public function createOrUpdate($id, $value, $file)
    {
        try {

            // begin Transaction between tables
            DB::beginTransaction();

                // find Or New
                $row               = (isset($id)) ? self::find($id) : new self;

                    foreach (['en', 'ar'] as $locale) {
                        $row->translateOrNew($locale)->title = $value[$locale]['title'];
                        $row->translateOrNew($locale)->body = $value[$locale]['body'];
                    }
                    
                $row->status         = boolval($value['status']);
                $row->save();
                $row->touch(); // touch updated_at


                // upload Image if exist
                if(isset($file)) {
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
