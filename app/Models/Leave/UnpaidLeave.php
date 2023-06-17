<?php

namespace App\Models\Leave;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnpaidLeave extends Model
{
    use softDeletes;

    protected $table = 'unpaid_leave';
}
