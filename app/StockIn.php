<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    //
    protected $table = "stock_in";

    protected $fillable = ['date', 'product_id', 'quantity', 'price'];

    public function getDateAttribute($value){
        return date('d-m-Y', strtotime($value));
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

}
