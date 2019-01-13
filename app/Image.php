<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
 //   use SoftDeletes;

    protected $fillable = ['original_name','name','note_id'];


    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
