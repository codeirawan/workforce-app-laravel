<?php

namespace App\Models\Forecast;

use Illuminate\Database\Eloquent\Model;

class Adjust extends Model
{
    protected $table = 'adjustment';

    protected $fillable = [
        'forecast_id',
        'mon',
        'tue',
        'wed',
        'thu',
        'fri',
        'sat',
        'sun',
    ];
}