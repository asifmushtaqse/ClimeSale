<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $fillable = ['name', 'phone'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function stockOut(){
        return $this->hasMany(StockOut::class);
    }
}
