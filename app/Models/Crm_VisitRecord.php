<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crm_VisitRecord extends Model
{
    use HasFactory;
    public $table = "crm__visit_records";
    protected $fillable = [
        "id","c_id", "visit_date", "visit_report_date", "visit_type", "visit_title", "visit_content", 
        "visit_follow", "visit_follow_phrase", "customer_analysis", "customer_analysis_name", 
        "supervisor_suggest", "supervisor_suggest_phrase", "supervisor_suggest_name", "creator_name",
        "created_at", "updated_at",
    ];
}
