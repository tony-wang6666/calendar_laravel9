<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarDateRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'member_id','case_level','case_title','case_content','case_begin','case_end','created_at', 'updated_at',
    ];
}
