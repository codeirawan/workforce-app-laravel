<?php

namespace App;

use App\Models\Master\Shift;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;


class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, LaratrustUserTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nik',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('users.active', 1);
        });
    }

    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'schedule_shift', 'agent_id', 'shift_id')
            ->withTimestamps();
    }

    public function hasAssignedShiftDuring($startTime, $endTime, $date)
    {
        return $this->shifts()->where(function ($query) use ($startTime, $endTime, $date) {
            $query->where('schedule_shift.date', '=', $date) // Check for the same date
                ->where(function ($query) use ($startTime, $endTime) {
                    // Check if the start time or end time of the shift is between the provided time range
                    $query->whereBetween('schedule_shift.start_time', [$startTime, $endTime])
                        ->orWhereBetween('schedule_shift.end_time', [$startTime, $endTime]);
                })
                ->orWhere(function ($query) use ($startTime, $endTime, $date) {
                    // Check if the shift spans over the entire provided time range
                    $query->where('schedule_shift.date', '=', $date)
                    ->where('schedule_shift.start_time', '<=', $startTime)
                        ->where('schedule_shift.end_time', '>=', $endTime);
                });
        })->exists();
    }

}