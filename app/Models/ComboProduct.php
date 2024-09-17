<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComboProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'combo_products';

    protected $primaryKey = 'id';

    protected $fillable = [
        'main_product',
        'main_type',
        'combo_product',
        'combo_type',
        'ip',
        'date',
        'added_by',
        'is_active',
    ];

    // public function category()
    // {
    //     return $this->belongsTo(EcomCategory::class, 'category_id');
    // }

    public function mainproduct()
    {
        return $this->belongsTo(EcomProduct::class, 'main_product' , 'id');
    }

    public function comboproduct()
    {
        return $this->belongsTo(EcomProduct::class, 'combo_product' , 'id');
    }

    public function maintype()
    {
        return $this->belongsTo(Type::class, 'main_type' , 'id');
    }

    public function combotype()
    {
        return $this->belongsTo(Type::class, 'combo_type' , 'id');
    }


    public function vendormaintype()
    {
        return $this->belongsTo(Type::class, 'main_type' , 'id');
    }

    public function vendorcombotype()
    {
        return $this->belongsTo(Type::class, 'combo_type' , 'id');
    }


    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }
}
