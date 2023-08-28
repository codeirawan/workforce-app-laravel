<?php

namespace App\Models\Schedule;

use Illuminate\Database\Eloquent\Model;

class ScheduleShift extends Model
{
    protected $table = 'schedule_shift';

    public function users()
    {
        return $this->belongsToMany(User::class, 'schedule_shift', 'shift_id', 'agent_id')
            ->withTimestamps();
    }

    protected $fillable = [
        'schedule_id',
        'shift_id',
        'agent_id',
        'date',
        'start_time',
        'end_time',
    ];
}