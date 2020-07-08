<?php

namespace App;

use Storage;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $guarded = [];

    public function imageable() {
        return $this->morphTo();
    }

    public static function uploadFile($id, $file, $modelName)
    {
        // remove old image if exists
        // if(isset($id)) {
        //     self::where('imageable_id', $id)->where('imageable_type', $modelName)->delete();
        // }
       

        // $row = new self;

        //     $path            = 'uploads/';
        //     $destinationPath = public_path().'/'.$path;
        //     $fileName        = date('Y-m-d-h-i-s').'-md.jpg';
        //     $fileOriginal    = date('Y-m-d-h-i-s').'.jpg';
        //     $base64_str      = substr($file, strpos($file, ",")+1);
            
        //     // if base 64 or file
        //     if(base64_encode(base64_decode($base64_str, true)) === $base64_str) {
        //         $image = base64_decode($base64_str);
                
        //         $img = resizeImage::make($image);
        //         $img->resize(160, 70, function ($constraint) {
        //             $constraint->aspectRatio();
        //         })->save($fileName);

        //         Storage::disk('public')->put($fileName, $image);
        //     } else {        
                
        //         //InterventionImage::make($file)->save($destinationPath . $fileOriginal);
        //         //InterventionImage::make($file)->resize(320, 220)->save($destinationPath . $fileName);
        //    }

        // $row->url = $path.$fileName;
        // return $row;
    }
}
