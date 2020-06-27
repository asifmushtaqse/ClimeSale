<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleManStock extends Model
{
    //
    protected $fillable = ["product_id", 'quantity', 'date'];
    protected $appends = ['total_quantity'];


    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function getDateAttribute($value){
        return date('d-m-Y', strtotime($value));
    }


    public function getTotalQuantityAttribute(){
        $quantities = explode("+", $this->quantity);
        $totalQuantity = 0;
        foreach($quantities as $quantity){
            $totalQuantity += $quantity;
        }
        return $totalQuantity;
    }

}
