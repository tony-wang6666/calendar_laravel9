<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crm_InsuranceInformation extends Model
{
    use HasFactory;
    public $table = "crm__insurance_informations";
    protected $fillable = [
        "id","c_id", "ii_date", "ii_insured", "ii_insurer", "ii_company", "ii_insurance_date", "ii_type", 
        "ii_cost", "ii_commission", "ii_car_number", 
        "created_at", "updated_at",
    ];
}
