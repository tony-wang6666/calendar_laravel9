<?php

namespace App\Models\crm_parameters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crm_ParameterCustomerReligion extends Model
{
    use HasFactory;
    public $table = "crm__parameter_customer_religions";
    protected $fillable = [
        "id","p_item", "p_order", "p_on_off", 
        "created_at", "updated_at",
    ];
}
