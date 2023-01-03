<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarGroupRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'calendar_datetime_record_id','calendar_member_id',
        'created_at', 'updated_at',
    ];
}
