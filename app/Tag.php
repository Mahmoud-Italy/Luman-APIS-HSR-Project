<?php

namespace App;

use App\Project;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Tag extends Model
{
    use Translatable;
    public $translatedAttributes = ['name'];

    protected $guarded = [];

    public function projects() {
        return $this->morphedByMany(Project::class, 'taggable');
    }

    public function scopeGetRow($tag)
    {
        return self::whereTranslation('name', $tag)->first();
    }

    public function fetchData($value='')
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

        $obj = $obj->pagiante($value['pagiante'] ?? 10);
        return $obj;
    }

    public function createOrUpdate($id, $value)
    {
        try {

            $row           = (isset($id)) ? self::find($id) : new self;

                foreach (['en', 'ar'] as $locale) {
                    $row->translateOrNew($locale)->name = $value[$locale]['name'];
                }
                
            $row->status   = boolval($value['status']);
            $row->save();

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
