<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    //
    protected $fillable = ['name', 'birth', 'death', 'gender', 'parent'];

    protected $dates = ['birth', 'death'];

    public function child()
    {
        return $this->hasMany('App\Family', 'parent');
    }

    public function childRecursive()
    {
        return $this->child()->with('childRecursive')->withChildCount();
    }

    public function scopeWithChildCount($query)
    {
        return $query->withCount('child');
    }
}
