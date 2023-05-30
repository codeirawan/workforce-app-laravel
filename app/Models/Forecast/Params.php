<?php

namespace App\Models\Forecast;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Params extends Model
{
    use SoftDeletes;
    protected $table = 'params';
}
