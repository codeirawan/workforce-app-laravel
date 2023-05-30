<?php

namespace App\Models\Forecast;

use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    protected $fillable = [
        'forecast_id', 'start_date', 'end_date', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun', 'sum', 'avg',
    ];
}
