<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarDatetimeRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'member_id','repeat_group','relevant_members','relevant_phone','case_level','informant','informant_type','thing_remark',
        'case_title','case_content','case_location','case_remarks','case_begin','case_end','case_all_day','calendar_source',
        'notification_record','created_at', 'updated_at',
    ];
}
