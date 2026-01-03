<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'transfer_zonasAndrea'; 
    protected $primaryKey = 'id_zona';
    public $timestamps = false;

    protected $fillable = ['descripcion'];

    public function partners()
    {
        return $this->hasMany(Partner::class, 'id_zona', 'id_zona');
    }
}