<?php

namespace App\Models\Leave;

use Illuminate\Database\Eloquent\Model;

class PaidLeaveStatus extends Model
{
    protected $table = 'paid_leave_status';

    public $timestamps = false;

    protected $casts = [
        'at' => 'datetime',
    ];
}
