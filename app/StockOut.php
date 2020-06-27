<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    //
    protected $table = "stock_out";

    protected $fillable = ['date', 'product_id', 'quantity', 'price', 'customer_id', 'is_total', 'buy_price'];

    public function getDateAttribute($value){
        return date('d-m-Y', strtotime($value));
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
