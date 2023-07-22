<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class ShiftSkill extends Model
{
    protected $table = 'shift_skills';

    protected $fillable = [
        'skill_id',
        'shift_id',
    ];

}