<?php

namespace App\Models\Forecast;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawData extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'raw_data';
    protected $guarded = [];

    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }
}
