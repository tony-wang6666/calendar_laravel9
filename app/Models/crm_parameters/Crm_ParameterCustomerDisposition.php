<?php

namespace App\Models\crm_parameters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crm_ParameterCustomerDisposition extends Model
{
    use HasFactory;
    public $table = "crm__parameter_customer_dispositions";
    protected $fillable = [
        "id","p_item", "p_order", "p_on_off", 
        "created_at", "updated_at",
    ];
}
