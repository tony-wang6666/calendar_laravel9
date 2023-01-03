<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'calendar_datetime_record_id','file_name','file_type'
    ];
}
