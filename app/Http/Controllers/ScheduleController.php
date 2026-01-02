<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class ScheduleController extends Controller
{
    public function index()
    {
        Booking::syncCompletedBookings();
        return view('calendar.calendar');
    }

    public function events(Request $request)
    {
        Booking::syncCompletedBookings();
        
        $from = substr($request->query('from'), 0, 10);
        $to = substr($request->query('to'), 0, 10);
        
        $query = Booking::query();

        if (Auth::guard('web')->check()) {
            $query->where('tipo_owner', 'user')->where('id_owner', Auth::guard('web')->user()->id_viajero);
        } elseif (Auth::guard('corporate')->check()) {
            $query->where('id_hotel', Auth::guard('corporate')->user()->id_hotel);
        } elseif (!Auth::guard('admin')->check()) {
            return response()->json([]);
        }

        $query->where(function ($q) use ($from, $to) {
            $q->whereBetween('fecha_entrada', [$from, $to])
              ->orWhereBetween('fecha_vuelo_salida', [$from, $to]);
        });

        $events = $query->get()->map(function ($booking) {
            return [
                'id'    => $booking->id_reserva,
                'title' => $booking->service_type_label . " - " . ($booking->partner->nombre ?? 'Servicio'),
                'start' => $booking->deadline(),
                'tipo'  => $booking->id_tipo_reserva,
            ];
        });

        return response()->json($events);
    }

    public function show($id)
    {
        Booking::syncCompletedBookings();
        
        $reserva = Booking::with(['partner', 'fleetUnit', 'owner', 'region'])->findOrFail($id);
        
        return view('calendar.detalle', compact('reserva'));
    }
}