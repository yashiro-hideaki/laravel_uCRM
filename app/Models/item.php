<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;

class item extends Model
{
    use HasFactory;
    //createでデータベースに保存する時に必要な記述（readouble:eloquentの準備）
    protected $fillable = [
        'name',
        'memo',
        'price',
        'is_selling'
    ];
    public function purchases(){
        return $this->belongsToMany(Purchase::class)
        ->withPivot('quantity');
    }
}
