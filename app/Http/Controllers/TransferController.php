<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use App\Models\Booking;
use App\Models\FleetUnit;
use App\Models\Partner;
use App\Models\Traveler;

class TransferController extends Controller
{
    public function showTypeSelection()
    {
        return view('transfers.type');
    }

    public function postTypeSelection(Request $request)
    {
        $request->validate([
            'reservation_type' => 'required|in:airport_to_hotel,hotel_to_airport,round_trip',
        ]);

        return redirect()->route('transfer.reserve.form', [
            'type' => $request->reservation_type
        ]);
    }

    public function showReservationForm($type)
    {
        if (!in_array($type, ['airport_to_hotel', 'hotel_to_airport', 'round_trip'])) {
            return redirect()->route('transfer.select-type')->with('error', 'Tipo de reserva no válido.');
        }

        $user = Auth::user();
        $isAdmin = Auth::guard('admin')->check();

        $minDate = $isAdmin ? Carbon::today()->format('Y-m-d') : Carbon::now()->addHours(48)->format('Y-m-d');

        $activePartner = null;

        if (Auth::guard('corporate')->check()) {
            $partnerId = Auth::guard('corporate')->user()->id_hotel;
            $activePartner = Partner::where('activo', 1)
                ->where('id_hotel', $partnerId)
                ->first();

            $partners = $activePartner ? collect([$activePartner]) : collect();
        } else {
            $partners = Partner::where('activo', 1)->orderBy('nombre')->get();
        }

        $fleet = FleetUnit::where('activo', 1) 
            ->orderBy('Descripción', 'asc')
            ->get();

        $travelers = (Auth::guard('admin')->check() || Auth::guard('corporate')->check()) 
            ? Traveler::orderBy('nombre')->get() 
            : collect();

        $viewMap = [
            'airport_to_hotel' => 'transfers.airport-to-hotel',
            'hotel_to_airport' => 'transfers.hotel-to-airport',
            'round_trip'       => 'transfers.round-trip',
        ];

        return view($viewMap[$type], compact(
            'user',
            'minDate',
            'partners',
            'fleet',
            'travelers',
            'activePartner'
        ));
    }

    public function confirmReservation(Request $request)
    {
        $isAdmin = Auth::guard('admin')->check();
        $minDate = $isAdmin ? Carbon::today()->format('Y-m-d') : Carbon::now()->addHours(48)->format('Y-m-d');

        $rules = [
            'reservation_type' => 'required|in:airport_to_hotel,hotel_to_airport,round_trip',
            'pax'              => 'required|integer|min:1',
            'email_contacto'   => 'required|email',
            'nombre_contacto'  => 'required|string',
            'telefono'         => 'required|string',
            'id_vehiculo'      => 'required|integer',
        ];

        if ($isAdmin || Auth::guard('corporate')->check()) {
            $rules['id_viajero'] = 'required|exists:transfer_viajeros,id_viajero';
        }

        if ($request->reservation_type === 'round_trip' && empty($request->id_hotel_recogida) && !empty($request->id_hotel_destino)) {
            $request->merge(['id_hotel_recogida' => $request->id_hotel_destino]);
        }

        if ($request->reservation_type === 'airport_to_hotel') {
            $rules += [
                'aeropuerto_origen' => 'required|string',
                'fecha_llegada'     => "required|date|after_or_equal:$minDate",
                'hora_llegada'      => 'required',
                'num_vuelo'         => 'required|string',
                'id_hotel_destino'  => 'required|integer',
            ];
        }

        if ($request->reservation_type === 'hotel_to_airport') {
            $rules += [
                'origen_vuelo_salida' => 'required|string',
                'fecha_vuelo_salida'  => "required|date|after_or_equal:$minDate",
                'hora_vuelo_salida'   => 'required',
                'num_vuelo_salida'    => 'required|string',
                'id_hotel_recogida'   => 'required|integer',
                'hora_recogida'       => 'required',
            ];
        }

        if ($request->reservation_type === 'round_trip') {
            $rules += [
                'origen_vuelo_entrada' => 'required|string',
                'fecha_llegada'        => "required|date|after_or_equal:$minDate",
                'hora_llegada'         => 'required',
                'num_vuelo_ida'        => 'required|string',
                'id_hotel_destino'     => 'required|integer',
                'origen_vuelo_salida'  => 'required|string',
                'fecha_vuelo_salida'   => "required|date|after_or_equal:$minDate",
                'hora_vuelo_salida'    => 'required',
                'num_vuelo_salida'     => 'required|string',
                'hora_recogida_vuelta' => 'required',
                'id_hotel_recogida'    => 'required|integer',
            ];
        }

        $request->validate($rules);
        $localizador = $this->createBookingRecord($request);

        return view('transfers.confirmation', compact('localizador'));
    }

    private function createBookingRecord(Request $request)
    {
        $type = $request->reservation_type;
        $now  = Carbon::now();

        $idOwner = Auth::guard('web')->check() 
            ? Auth::guard('web')->user()->id_viajero 
            : (int) $request->id_viajero;

        if (!$idOwner) {
            throw ValidationException::withMessages(['id_viajero' => 'Debe seleccionar un viajero.']);
        }

        if (Auth::guard('admin')->check()) {
            $createdByType = 'admin';
            $createdById   = Auth::guard('admin')->user()->id_admin;
        } elseif (Auth::guard('corporate')->check()) {
            $createdByType = 'hotel';
            $createdById   = Auth::guard('corporate')->user()->id_hotel;
        } else {
            $createdByType = 'user';
            $createdById   = $idOwner;
        }

        $idDestino = $request->id_hotel_destino ?? $request->id_hotel_recogida;
        $idPartner = $createdByType === 'hotel' ? $createdById : $idDestino;

        $unit = FleetUnit::findOrFail($request->id_vehiculo);
        $finalRate = $unit->rate * ($type === 'round_trip' ? 2 : 1);

        $partner = Partner::findOrFail($idDestino);
        $commissionEarned = round($finalRate * (($partner->Comision ?? 0) / 100), 2);

        $data = [
            'localizador' => strtoupper(uniqid('BK-')),
            'id_tipo_reserva' => ['airport_to_hotel' => 1, 'hotel_to_airport' => 2, 'round_trip' => 3][$type],
            'email_cliente' => $request->email_contacto,
            'id_owner'   => $idOwner,
            'tipo_owner' => 'user',
            'created_by_type' => $createdByType,
            'created_by_id'   => $createdById,
            'fecha_reserva'       => $now,
            'fecha_modificacion' => $now,
            'id_hotel'   => $idPartner,
            'id_destino' => $idDestino,
            'num_viajeros' => $request->pax,
            'id_vehiculo'  => $request->id_vehiculo,
            'precio_total'       => $finalRate,
            'comision_ganada'    => $commissionEarned,
            'comision_liquidada' => 0,
        ];

        if ($type === 'airport_to_hotel' || $type === 'round_trip') {
            $data['origen_vuelo_entrada'] = $request->origen_vuelo_entrada ?? $request->aeropuerto_origen;
            $data['fecha_entrada']        = $request->fecha_llegada;
            $data['hora_entrada']         = $request->hora_llegada;
            $data['numero_vuelo_entrada'] = $request->num_vuelo ?? $request->num_vuelo_ida;
        }

        if ($type === 'hotel_to_airport' || $type === 'round_trip') {
            $data['origen_vuelo_salida'] = $request->origen_vuelo_salida;
            $data['fecha_vuelo_salida']  = $request->fecha_vuelo_salida;
            $data['hora_vuelo_salida']   = $request->hora_vuelo_salida;
            $data['numero_vuelo_salida'] = $request->num_vuelo_salida ?? null;
            $data['hora_recogida_hotel'] = $request->hora_recogida ?? $request->hora_recogida_vuelta;
        }

        Booking::create($data);
        return $data['localizador'];
    }
}