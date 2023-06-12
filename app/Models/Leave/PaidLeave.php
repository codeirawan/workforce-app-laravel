<?php

namespace App\Models\Leave;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaidLeave extends Model
{
    use softDeletes;

    protected $table = 'paid_leave';
}
