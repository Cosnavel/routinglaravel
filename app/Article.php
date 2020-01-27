<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //
    protected $fillable = ['title', 'text'];

    public function interests()
    {
        return $this->belongsToMany('App\Interest');
    }
}
