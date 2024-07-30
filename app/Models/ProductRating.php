<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductRating extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_ratings';

    protected $fillable = [
        'device_id',
        'user_id',
        'category_id',
        'product_id',
        'rating',
        'description',
        'description_hi',
        'ip',
        'date',
        'updated_date',
    ];

    public $timestamps = false; // Since we're not using Laravel's created_at and updated_at columns

    // Define the relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Define the relationship to Category
    public function category()
    {
        return $this->belongsTo(EcomCategory::class, 'category_id');
    }

    // Define the relationship to Product
    public function product()
    {
        return $this->belongsTo(EcomProduct::class, 'product_id');
    }
}
