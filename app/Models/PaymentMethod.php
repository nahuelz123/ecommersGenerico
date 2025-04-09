<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function shippingMethods()
    {
        return $this->belongsToMany(ShippingMethod::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
