<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarThingRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'calendar_datetime_record_id','thing_id','thing_number','thing_state',
        'created_at', 'updated_at',
    ];
}
