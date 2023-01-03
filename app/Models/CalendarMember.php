<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarMember extends Model
{
    use HasFactory;
    protected $fillable = [
        'account','password','name','cm_ao_staff','cm_manager','cm_state','cm_authority','notification_token',
        'created_at','updated_at'
    ];
}
