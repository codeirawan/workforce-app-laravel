<?php

namespace App\Models\Forecast;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawData extends Model
{
    use SoftDeletes;

    protected $table = 'raw_data';

    protected $fillable = [
        'date', 'start_time', 'end_time', 'volume', 'city_id', 'project_id', 'skill_id', 'batch_id',
    ];
}
