<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crm_CustomerBasicInformation extends Model
{
    use HasFactory;
    public $table = "crm__customer_basic_informations";
    protected $fillable = [
        "id","c_name_company", "identification_gui_number", "c_sex", "c_birth_opening_date", "c_type", 
        "c_telephone", "c_cellphone", "religion", "c_number", "c_family", "postcode", "city", "city_area", 
        "c_address", "open_account", "farmer_association_member", "farmer_insurance", "health_state", 
        "communicate_state", "response_attitude", "deposit_level", "loan_level", "c_bank", "vip_cyear", 
        "encourage_raise_staff", "ao_staff", "transfer_item", "remark", "visitable_times", "dispositions", 
        "interests", "prefer_invests", "openness_high_business", "openness_low_business", "c_source",
        "created_at", "updated_at",
        
    ];
}
