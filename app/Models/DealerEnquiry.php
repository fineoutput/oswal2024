<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DealerEnquiry extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dealer_enquirys';

    protected $fillable = [
        'name', 'age', 'qualification', 'city', 'state', 'district', 'firmname',
        'firmaddress', 'businessname', 'businessexperience', 'businesstype', 
        'mobile', 'annualturnover', 'type', 'vehicle', 'vehicle_count', 
        'manpower', 'capacity', 'file', 'agencyName', 'details', 'businessbrief',
    ];
         

}
