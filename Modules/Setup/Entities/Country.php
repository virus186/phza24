<?php

namespace Modules\Setup\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\INTShipping\Entities\Continent;

class Country extends Model
{
    protected $table = 'countries';
    protected $guarded = ['id'];

    public function continent(){
        return $this->belongsTo(Continent::class,'continent_id','id');
    }
    public function states(){
        return $this->hasMany(State::class,'country_id','id')->orderBy('name');
    }

    public function cities(){
        return $this->hasManyThrough(City::class, State::class);
    }
}
