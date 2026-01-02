<?php

namespace App\Http\Controllers;

use App\Models\FleetUnit;
use Illuminate\Support\Facades\Auth;

class ShowcaseController extends Controller
{
    public function index()
    {
        $isAdmin = Auth::guard('admin')->check();
        $isCorporate = Auth::guard('corporate')->check();
        $isTraveler = Auth::guard('web')->check();
        $fleet = FleetUnit::all();

        $metaFleet = [
            'Sedán Demo' => [
                'image' => 'https://images.unsplash.com/photo-1550355291-bbee04a92027?auto=format&fit=crop&q=80&w=800',
                'tagline' => 'Cómodo y elegante para traslados diarios.',
                'capacidad' => 'Hasta 3 pasajeros', 'maletas' => '2 maletas grandes',
            ],
            'Minivan VIP Deluxe' => [
                'image' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=800',
                'tagline' => 'Perfecta para familias y grupos pequeños.',
                'capacidad' => 'Hasta 7 pasajeros', 'maletas' => '4–5 maletas',
            ]
        ];

        return view('home', compact('isAdmin', 'isCorporate', 'isTraveler', 'fleet', 'metaFleet'));
    }
}