<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Booking;

class PartnerOperationsController extends Controller
{
    public function earningsAudit(Request $request)
    {
        Booking::syncCompletedBookings();
        $user = Auth::guard('corporate')->user();
        
        $all = $request->has('all');
        $month = $all ? null : $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $query = Booking::where('id_hotel', $user->id_hotel);

        if (!$all) {
            $query->whereYear('fecha_reserva', $year)->whereMonth('fecha_reserva', $month);
        }

        $bookings = $query->get();

        $commissionReport = $bookings->map(function ($booking) {
            return [
                'reserva_id'     => $booking->id_reserva,
                'localizador'    => $booking->localizador,
                'fecha_traslado' => $booking->deadline(),
                'precio_total'   => $booking->precio_total,
                'comision_hotel' => $booking->comision_ganada,
            ];
        });

        $totalComision = $commissionReport->sum('comision_hotel');

        return view('corporate.comissions', compact('commissionReport', 'month', 'year', 'totalComision'));
    }
}