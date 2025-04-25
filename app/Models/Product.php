<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = ['id' , 'product_id' , 'category_id', 'product' , 'payout' , 'etb' , 'created_at' , 'updated_at'];
}
