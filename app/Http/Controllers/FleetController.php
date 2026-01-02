<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FleetUnit;

class FleetController extends Controller
{
    public function index()
    {

        $units = FleetUnit::orderBy('Descripción', 'asc')->get();
        return view('admin.fleet.index', compact('units'));
    }

    public function create()
    {
        return view('admin.fleet.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label'           => 'required|string|max:255',
            'driver_identity' => 'required|email|max:255|unique:transfer_vehiculos,email_conductor',
            'access_key'      => 'required|string|max:255',
            'rate'            => 'required|numeric|min:0',
        ]);

        FleetUnit::create([
            'Descripción'     => $request->label,
            'email_conductor' => $request->driver_identity,
            'password'        => $request->access_key,
            'precio'          => $request->rate,
            'activo'          => 1
        ]);

        return redirect()->route('admin.fleet.index')->with('success', 'Unidad registrada correctamente.');
    }

    public function edit($id)
    {
        $unit = FleetUnit::findOrFail($id);
        return view('admin.fleet.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $unit = FleetUnit::findOrFail($id);

        $request->validate([
            'label'           => 'required|string|max:255',
            'driver_identity' => 'required|email|max:255|unique:transfer_vehiculos,email_conductor,' . $unit->id_vehiculo . ',id_vehiculo',
            'access_key'      => 'required|string|max:255',
            'rate'            => 'required|numeric|min:0',
        ]);

        $unit->update([
            'Descripción'     => $request->label,
            'email_conductor' => $request->driver_identity,
            'password'        => $request->access_key,
            'precio'          => $request->rate,
        ]);

        return redirect()->route('admin.fleet.index')->with('success', 'Unidad actualizada correctamente.');
    }

    public function deactivate($id)
    {
        $unit = FleetUnit::findOrFail($id);
        $unit->activo = 0;
        $unit->save();
        return back()->with('success', 'Unidad inhabilitada.');
    }

    public function activate($id)
    {
        $unit = FleetUnit::findOrFail($id);
        $unit->activo = 1; 
        $unit->save();
        return back()->with('success', 'Unidad habilitada.');
    }
}