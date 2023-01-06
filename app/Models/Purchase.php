<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\item;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'status'
    ];
    public function customers(){
        return $this->belongsTo(Customer::class);
    }
    public function items(){
        return $this->belongsToMany(Item::class)
        ->withPivot('quantity');
    }
}
