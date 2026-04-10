<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'phone', 'nik', 'photo',
        'gender', 'birth_date', 'address', 'kelas_jurusan',
        'plain_password', 'password', 'role', 'password_changed',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function isProfileComplete(): bool
    {
        return !empty($this->username) && !empty($this->phone) && !empty($this->nik)
            && !empty($this->gender) && !empty($this->birth_date)
            && !empty($this->address) && !empty($this->kelas_jurusan);
    }
}
