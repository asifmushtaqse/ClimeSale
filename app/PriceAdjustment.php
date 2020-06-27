<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceAdjustment extends Model
{
    protected $fillable = ['product_id', 'price', 'quantity'];

    public function getPriceAttribute($value)
    {
        if($value){
            return $value;
        }else{
            return $this->product->price;
        }
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
