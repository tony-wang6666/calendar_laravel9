<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crm_Contribution extends Model
{
    use HasFactory;
    public $table = "crm__contributions";
    protected $fillable = [
        "id", "c_id", "c_date", "c_current_deposits", "c_time_deposits", "c_loan", 
        "c_transfer", "c_insurance", "c_score",
        "created_at", "updated_at",
    ];
}
