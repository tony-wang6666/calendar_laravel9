<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crm_ChangeCustomer extends Model
{
    use HasFactory;
    public $table = "crm__change_customers";
    protected $fillable = [
        "id","c_id", "cc_date", "cc_col1", "cc_class", "cc_acount", "cc_day_balance", "cc_last_day_balance", 
        "cc_col2", "cc_settlement_money",
        "created_at", "updated_at",
    ];
}
