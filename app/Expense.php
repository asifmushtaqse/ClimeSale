<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //
    protected $fillable = ['type', 'date', 'price'];

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = strtolower($value);
    }

    public function getTypeAttribute($value)
    {
        return ucfirst($value);
    }
}
