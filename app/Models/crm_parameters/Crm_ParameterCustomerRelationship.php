<?php

namespace App\Models\crm_parameters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crm_ParameterCustomerRelationship extends Model
{
    use HasFactory;
    public $table = "crm__parameter_customer_relationships";
    protected $fillable = [
        "id","p_item", "p_order", "p_on_off", 
        "created_at", "updated_at",
    ];
}
