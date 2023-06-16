<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NationalHoliday extends Model
{
    use SoftDeletes;

    protected $table = 'master_national_holidays';
}
