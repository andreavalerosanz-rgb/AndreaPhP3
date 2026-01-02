<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EstadisticasController extends Controller
{
    public function resumenZonas()
    {
        // Total de reservas
        $total = DB::table('transfer_reservas')->count();

        // Agregado por zonas
        $data = DB::table('transfer_zonas AS z')
            ->leftJoin('transfer_hoteles AS h', 'h.id_zona', '=', 'z.id_zona')
            ->leftJoin('transfer_reservas AS r', 'r.id_hotel', '=', 'h.id_hotel')
            ->selectRaw('
                z.descripcion AS zona,
                COUNT(r.id_reserva) AS num_traslados
            ')
            ->groupBy('z.descripcion')
            ->get();

        // Añadir porcentajes
        $resultado = $data->map(function ($item) use ($total) {
            $item->porcentaje = $total > 0 
                ? round(($item->num_traslados / $total) * 100, 2)
                : 0;
            return $item;
        });

        return response()->json([
            'total_traslados' => $total,
            'resumen_por_zona' => $resultado
        ]);
    }
}
