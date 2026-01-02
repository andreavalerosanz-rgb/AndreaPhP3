<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\Traveler;

class HubManagerController extends Controller
{
    public function listServices(Request $request)
    {
        Booking::syncCompletedBookings();
        $query = Booking::with(['partner', 'owner', 'fleetUnit']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_entrada', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_entrada', '<=', $request->fecha_hasta);
        }

        $reservas = $query->orderBy('fecha_reserva', 'DESC')->paginate(15);
        return view('hub.services-list', compact('reservas'));
    }

    public function revenueReport()
    {
        $month = request('month', now()->month);
        $year = request('year', now()->year);

        $commissionReport = Partner::all()->map(function ($partner) use ($month, $year) {
            $bookings = Booking::where('id_hotel', $partner->id_hotel)
                ->whereMonth('fecha_reserva', $month)
                ->whereYear('fecha_reserva', $year)
                ->get();

            return [
                'nombre_hotel'   => $partner->nombre,
                'total_ingresos' => $bookings->sum('precio_total'),
                'total_comision' => $bookings->sum('comision_ganada'),
            ];
        });

        return view('hub.revenue-report', compact('commissionReport', 'month', 'year'));
    }

    public function viewServiceFile($id)
    {
        $reserva = Booking::with(['partner', 'fleetUnit', 'owner'])->findOrFail($id);
        return view('hub.service-dossier', compact('reserva'));
    }
}