<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use MongoDB\Laravel\Auth\User as Authenticatable;  // Make sure this is correct

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'theme',
        'lang',
        'photo',
        'role_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime'
        ];
    }

    public function getFullNameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getPhotoUrlAttribute() {
        return $this->photo ? asset('assets/img/photos/users/' . $this->photo) : asset('assets/img/photos/users/default.png');
    }

    public function getPhotoPathAttribute() {
        return $this->photo ? public_path('assets/img/photos/users/' . $this->photo) : null;
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($roleName) {
        return $this->role && $this->role->name === $roleName;
    }

}
