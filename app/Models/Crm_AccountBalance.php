<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crm_AccountBalance extends Model
{
    use HasFactory;
    public $table = "crm__account_balances";
    protected $fillable = [
        "id","c_id", "ab_date", "ab_bank_number", "ab_class", "ab_acount", "ab_balances", "ab_deposit_money_average", 
        "ab_time_deposit_average", "ab_credit_first", "ab_last_year_interest_recover_money", "ab_deposit_money",
        "ab_credit_money",
        "created_at", "updated_at",
    ];
}
