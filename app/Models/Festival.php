<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Festival extends Model
{
    use HasFactory;
    // public $timestamps = false;
    // protected $primaryKey = 'id';
    protected $fillable = [
        'festival_date','festival_name','isHoliday','holidayCategory','festival_description','created_at', 'updated_at',
    ];
}
