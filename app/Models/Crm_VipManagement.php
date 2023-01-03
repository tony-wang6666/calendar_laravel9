<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crm_VipManagement extends Model
{
    use HasFactory;
    public $table = "crm__vip_managements";
    protected $fillable = [
        "id","cyear", "c_id", "cyear_level", 
        "created_at", "updated_at",
    ];
}
