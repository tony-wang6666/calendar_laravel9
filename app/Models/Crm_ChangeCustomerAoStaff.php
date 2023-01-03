<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crm_ChangeCustomerAoStaff extends Model
{
    use HasFactory;
    public $table = "crm__change_customer_ao_staffs";
    protected $fillable = [
        "c_id","ccas_date", "ccas_old_ao", "ccas_old_name", "ccas_new_ao", "ccas_new_name", 
        "created_at", "updated_at",
    ];
}
