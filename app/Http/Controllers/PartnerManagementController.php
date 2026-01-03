<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\Region;

class PartnerManagementController extends Controller
{
    public function index()
    {
        $hoteles = Partner::with('region')->get();
        $zonas = Region::all();
        return view('hub.partner-hub', compact('hoteles', 'zonas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'email_hotel' => 'required|email|unique:transfer_hotelesAndrea,email_hotel',
            'id_zona'     => 'required|exists:transfer_zonasAndrea,id_zona',
            'password'    => 'required|min:6|confirmed',
            'Comision'    => 'required|numeric|min:0|max:100',
        ]);

        Partner::create([
            'nombre'      => $request->nombre,
            'email_hotel' => $request->email_hotel,
            'id_zona'     => $request->id_zona,
            'password'    => bcrypt($request->password),
            'Comision'    => $request->Comision,
            'activo'      => 1
        ]);

        return redirect()->route('admin.hoteles.index')->with('success', 'Partner registrado.');
    }

    public function disable($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->update(['activo' => 0]);
        return back()->with('success', 'Partner inhabilitado.');
    }

    public function enable($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->update(['activo' => 1]);
        return back()->with('success', 'Partner habilitado.');
    }
}