<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetUnit extends Model
{
    use HasFactory;

    protected $table = 'transfer_vehiculos'; 
    protected $primaryKey = 'id_vehiculo';  
    public $timestamps = false; 

    protected $fillable = [
        'Descripción', 
        'email_conductor', 
        'password', 
        'activo', 
        'precio'
    ];

    protected $appends = ['label', 'rate'];

    public function getLabelAttribute() {
        return $this->attributes['Descripción'] ?? 'Vehículo';
    }

    public function getRateAttribute() {
        return (float) ($this->attributes['precio'] ?? 50.00);
    }

    public function setLabelAttribute($value) {
        $this->attributes['Descripción'] = $value;
    }

    public function setRateAttribute($value) {
        $this->attributes['precio'] = $value;
    }
}