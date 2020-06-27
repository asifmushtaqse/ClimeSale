<?php

namespace App;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = ["name","price"];


    public function stock(){
        return $this->hasOne(Stock::class);
    }

    public function stockIn(){
        return $this->hasMany(StockIn::class);
    }

    public function stockOut(){
        return $this->hasMany(StockOut::class);
    }

    public function priceAdjustment(){
        return $this->hasOne(PriceAdjustment::class);
    }

    public function price(){
        if($this->priceAdjustment){
            return $this->priceAdjustment->price;
        }else{
            return $this->price;
        }
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

}
