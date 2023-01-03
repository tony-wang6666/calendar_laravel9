<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarLunarCalendar extends Model
{
    use HasFactory;
    // protected $table = '';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'clc_year', 'clc_month', 'calendar_data'
    ];
}
