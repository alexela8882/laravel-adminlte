<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function company () {
        return $this->belongsTo('App\Company');
    }

    public function theme () {
        return $this->hasOne('App\Theme');
    }

    public function files_from () {
        return $this->hasMany('App\File', 'from', 'id');
    }

    public function files_to () {
        return $this->hasMany('App\File', 'to', 'id');
    }

    public function file_setting () {
        return $this->hasMany('App\FileSetting', 'user_id', 'id');
    }
}
