<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Traits\HasUuid;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasUuid, HasApiTokens, Notifiable, LogsActivity;

    protected static $logName = 'user';
    protected static $logAttributes = ['name', 'email'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 
        'name', 
        'email', 
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function setPasswordAttribute($value){
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}
