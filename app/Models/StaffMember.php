<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class StaffMember extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'transfer_adminAndrea';
    protected $primaryKey = 'id_admin';
    public $timestamps = false;

    public function getAuthIdentifierName()
    {
        return 'email_admin';
    }

    protected $fillable = [
        'nombre',
        'email_admin',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($value)
    {
        if ($value !== null && $value !== '') {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}