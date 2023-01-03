<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kinship extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'kin_id1','relationship1',
        'kin_id2','relationship2',
        'kin_id3','relationship3',
        'kin_id4','relationship4',
        'kin_id5','relationship5',
        'kin_id6','relationship6',
        'kin_id7','relationship7',
    ];
}
