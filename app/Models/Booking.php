<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'transfer_reservasAndrea';
    protected $primaryKey = 'id_reserva';
    public $timestamps = false; 

    protected $fillable = [
        'localizador', 'id_tipo_reserva', 'email_cliente', 'id_owner', 'tipo_owner',
        'created_by_type', 'created_by_id', 'fecha_reserva', 'fecha_modificacion',
        'id_hotel', 'id_destino', 'fecha_entrada', 'hora_entrada', 'numero_vuelo_entrada',
        'origen_vuelo_entrada', 'fecha_vuelo_salida', 'hora_vuelo_salida',
        'numero_vuelo_salida', 'origen_vuelo_salida', 'hora_recogida_hotel',
        'num_viajeros', 'id_vehiculo', 'precio_total', 'comision_ganada',
        'comision_liquidada', 'estado',
    ];

    // --- RELACIONES ---

    public function partner() {
        return $this->belongsTo(Partner::class, 'id_hotel', 'id_hotel');
    }

    public function owner() {
        return $this->belongsTo(Traveler::class, 'id_owner', 'id_viajero');
    }

    public function fleetUnit() {
        return $this->belongsTo(FleetUnit::class, 'id_vehiculo', 'id_vehiculo');
    }

    public function region() {
        return $this->hasOneThrough(Region::class, Partner::class, 'id_hotel', 'id_zona', 'id_hotel', 'id_zona');
    }

    // --- MÉTODOS DE LÓGICA ---

    /**
     * Calcula la fecha límite del servicio (entrada o salida).
     */
    public function deadline()
    {
        if (in_array($this->id_tipo_reserva, [1, 3])) {
            return $this->fecha_entrada ? Carbon::parse($this->fecha_entrada) : null;
        }
        if ($this->id_tipo_reserva == 2) {
            return $this->fecha_vuelo_salida ? Carbon::parse($this->fecha_vuelo_salida) : null;
        }
        return null;
    }

    /**
     * Determina si la reserva puede ser editada o anulada según el rol y el tiempo.
     * El método que faltaba y causaba el error.
     */
    public function isModifiableBy(string $role): bool
    {
        // Si está anulada, nadie puede tocarla
        if ($this->estado === 'anulada') {
            return false;
        }

        $serviceDate = $this->deadline();

        // Si no hay fecha o ya pasó (finalizada), no se toca
        if (!$serviceDate || $serviceDate->isPast()) {
            return false;
        }

        // El admin siempre puede mientras no haya pasado la fecha
        if ($role === 'admin') {
            return true;
        }

        // Hotel y Viajero: mínimo 48 horas antes
        return now()->diffInHours($serviceDate, false) > 48;
    }

    /**
     * Label para el tipo de servicio (Accessor para la vista)
     */
    public function getServiceTypeLabelAttribute()
    {
        return match((int)$this->id_tipo_reserva) {
            1 => 'Airport → Hotel',
            2 => 'Hotel → Airport',
            3 => 'Round Trip',
            default => 'Unknown'
        };
    }

    // --- PROCESOS ---

    public static function syncCompletedBookings(): void
    {
        DB::statement("
            UPDATE transfer_reservasAndrea
            SET estado = 'finalizada', comision_liquidada = comision_ganada, fecha_modificacion = NOW()
            WHERE estado = 'confirmada'
            AND (
                (id_tipo_reserva IN (1,3) AND DATE_ADD(TIMESTAMP(fecha_entrada, hora_entrada), INTERVAL -1 HOUR) < NOW())
                OR
                (id_tipo_reserva = 2 AND DATE_ADD(TIMESTAMP(fecha_vuelo_salida, hora_recogida_hotel), INTERVAL -1 HOUR) < NOW())
            )
        ");
    }
}