<?php

namespace App;

use DB;
use App\Tag;
use App\Image;
use App\Category;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Project extends Model
{
    use Translatable;
    public $translatedAttributes = ['slug', 'title', 'body'];

    protected $guarded = [];

    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'cat_id');
    } 

    public function tags() {
        return $this->morphMany(DB::table('taggable'), 'taggable');
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
                        $row->translateOrNew($locale)->slug = $value[$locale]['slug'];
                        $row->translateOrNew($locale)->title = $value[$locale]['title'];
                        $row->translateOrNew($locale)->body = $value[$locale]['body'];
                    }

                $row->donation_option1       = $value['donation_option1'] ?? NULL;
                $row->donation_option2       = $value['donation_option2'] ?? NULL;
                $row->donation_option3       = $value['donation_option3'] ?? NULL;
                $row->donation_option4       = $value['donation_option4'] ?? NULL;
                $row->goal                   = $value['goal'] ?? NULL:
                $row->goal_amount            = $value['goal_amount'] ?? NULL;
                $row->status                 = boolval($value['status']);
                $row->completed              = boolval($value['completed']);
                $row->auto_complete_on_goal  = boolval($value['auto_complete_on_goal']);
                $row->allow_custome_amounts  = boolval($value['allow_custome_amounts']);
                $row->save();
                $row->touch(); // touch updated_at

                // upload Image if exist
                if(isset($file)) {
                    $img = Image::uploadFile($id, $file, __NAMESPACE__.'\\'.class_basename(new self));
                    $row->image()->save($img);
                }

                // Add Tags if exists
                if(isset($value['tags'])) {
                    $tagids = [];
                    foreach(explode(',',$value['tags']) as $tag) {
                        $tagids[] = Tag::getRow($tag)->id;
                    }
                    // morphTo Taggables
                    $row->tags()->delete(); // Delete taggables row
                    foreach ($tagids as $tagid) {
                        $row->tags()->create(['tag_id'=>$tagid]);
                    } 
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
