<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Partner extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'transfer_hotelesAndrea';
    protected $primaryKey = 'id_hotel';
    public $timestamps = false;

    protected $fillable = ['nombre', 'id_zona', 'email_hotel', 'Comision', 'password', 'activo'];
    protected $hidden = ['password'];

    public function region() {
        return $this->belongsTo(Region::class, 'id_zona', 'id_zona');
    }
}