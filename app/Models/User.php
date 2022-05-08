<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //CUSTOM CASTS
    /**
     * The name attribute
     * @return Attribute
     */
    public function name(): Attribute
    {
        return new Attribute(
            // Get
            fn ($value) => Str::title($value),
            // Set
            fn ($value) => Str::lower($value),
        );
    }
    /**
     * The email attribute
     * @return Attribute
     */
    public function email(): Attribute
    {
        return new Attribute(
            // Get
            fn ($value) => Str::lower($value),
            // Set
            fn ($value) => Str::lower($value),
        );
    }
    /**
     * The password attribute
     * @return Attribute
     */
    public function password(): Attribute
    {
        return new Attribute(
            // Get
            fn ($value) => $value,
            // Set
            fn ($value) => bcrypt($value),
        );
    }
}
