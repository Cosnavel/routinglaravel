<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    //
    protected $fillable = ['name', 'birth', 'death', 'gender', 'parent'];

    protected $dates = [
        'birth', 'death'
    ];





    public function children()
    {
        return $this->hasMany('App\Family', 'parent')->limit(2);
    }
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
    public function parent()
    {
        return $this->belongsTo('App\Family', 'parent');
    }
    public function parentRecursive()
    {
        return $this->parent()->with('parentRecursive');
    }
}
