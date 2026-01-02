<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\Traveler;
use App\Models\StaffMember;

class DashboardController extends Controller
{
    public function admin()
    {
        Booking::syncCompletedBookings();
        $stats = [
            'reservasTotales' => Booking::count(),
            'viajerosTotales' => Booking::sum('num_viajeros'),
            'hotelesTotales'  => Partner::count(),
            'usuariosTotales' => Traveler::count(),  
            'adminsTotales'   => StaffMember::count(),   
        ];

        $totalReservas = Booking::count();

        $zonas = \DB::table('transfer_zonas AS z')
            ->leftJoin('transfer_hoteles AS h', 'h.id_zona', '=', 'z.id_zona')
            ->leftJoin('transfer_reservas AS r', 'r.id_hotel', '=', 'h.id_hotel')
            ->selectRaw('z.descripcion AS zona, COUNT(r.id_reserva) AS num_traslados')
            ->groupBy('z.descripcion')
            ->get()
            ->map(function ($item) use ($totalReservas) {
                $item->porcentaje = $totalReservas > 0 ? round(($item->num_traslados / $totalReservas) * 100, 2) : 0;
                return $item;
            });

        return view('admin.dashboard', compact('stats', 'zonas'));
    }

    public function hotel()
    {
        Booking::syncCompletedBookings();
        $partner = Auth::guard('corporate')->user();
        $stats = ['totalTraslados' => Booking::where('id_hotel', $partner->id_hotel)->count()];
        return view('corporate.dashboard', compact('stats'));
    }

    public function user()
    {
        Booking::syncCompletedBookings();
        $traveler = Auth::guard('web')->user();   
        $stats = ['totalReservas' => Booking::where('tipo_owner', 'user')->where('id_owner', $traveler->id_viajero)->count()];
        return view('user.dashboard', compact('stats'));
    }
}