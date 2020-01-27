<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interest extends Model
{
    //
    use SoftDeletes;

    public function articles()
    {

        return $this->belongsToMany('App\Article');
    }
}
