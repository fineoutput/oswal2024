<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerEnquiry extends Model
{
    use HasFactory;

    protected $table = 'dealer_enquirys';

    protected $fillable = [
        'name', 'email', 'phone', 'pincode', 'area', 'city',
        'state', 'message', 'age', 'qualification', 'district', 'mobile',
        'invest', 'manpower', 'ref_name', 'ref_city_name', 'firmname', 'firmaddress',
        'businessname', 'businessexperience', 'businesstype', 'annualturnover', 'currbussberif', 'infra','vehicle', 'ans', 'file', 'date', 'ip', 'type',
    ];

}
