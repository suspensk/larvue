<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
 * FOR FIELD ALIAS https://github.com/jarektkaczyk/eloquence/wiki/Mappable
 * use Sofa\Eloquence\Eloquence; // base trait
use Sofa\Eloquence\Mappable; // extension trait*/


class Note extends Model
{
    use SoftDeletes;
    /* use Eloquence, Mappable; FOR FIELD ALIAS email - in model, text in DB

    protected $fillable = ['name', 'email'];

    protected $maps = [
        'email' => 'text',
    ];*/

    protected $fillable = ['text'];

//    public function category()
//    {
//        return $this->hasOne(Category::class);
//    }
//
//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }

//    public function tags()
//    {
//        return $this->hasMany(Tag::class);
//    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

   /* public function images()
    {
        return $this->hasMany(Image::class);
    }*/
}
