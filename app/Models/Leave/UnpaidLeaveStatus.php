<?php

namespace App\Models\Leave;

use Illuminate\Database\Eloquent\Model;

class UnpaidLeaveStatus extends Model
{
    protected $table = 'unpaid_leave_status';

    public $timestamps = false;

    protected $casts = [
        'at' => 'datetime',
    ];
}
