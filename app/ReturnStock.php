<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturnStock extends Model
{
    protected $fillable = ['date', 'product_id', 'quantity', 'price', 'customer_id', 'is_total'];

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
