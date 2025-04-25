<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $fillable = ['id' , 'first_name' , 'last_name' , 'mobile_no' , 'email' , 'dob' , 'company' , 'occupation' , 'occupation' , 'itr_amount' , 'pincode' , 'credit_score' , 'category' , 'category_id' , 'response', 'address', 'gender', 'created_at' , 'updated_at'];
}
