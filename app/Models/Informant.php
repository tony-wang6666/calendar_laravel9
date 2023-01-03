<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informant extends Model
{
    use HasFactory;
    // public $timestamps = false;
    protected $fillable = [
        'informant_name','created_at', 'updated_at',
    ];
}
