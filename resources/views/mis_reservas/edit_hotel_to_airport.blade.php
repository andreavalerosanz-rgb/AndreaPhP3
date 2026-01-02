@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:py-12">
    <div class="card bg-white shadow-2xl border border-slate-100 rounded-[3rem] lg:rounded-[3.5rem] overflow-hidden">
        
        <div class="bg-slate-900 p-8 lg:p-12 text-white flex flex-col sm:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-primary/20 rounded-2xl flex items-center justify-center text-primary">
                    <i data-lucide="plane-takeoff" class="w-7 h-7"></i>
                </div>
                <div>
                    <h2 class="text-2xl lg:text-3xl font-black italic uppercase tracking-tight">Editar <span class="text-primary">Salida</span></h2>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">Ref: {{ $reserva->localizador }}</p>
                </div>
            </div>
            <a href="{{ route('mis_reservas') }}" class="btn btn-ghost btn-sm text-slate-400 hover:text-white border-white/10 uppercase font-black text-[10px] tracking-widest">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Volver
            </a>
        </div>

        <div class="p-6 md:p-10 lg:p-14">
            <form method="POST" action="{{ route('mis_reservas.update', $reserva->id_reserva) }}" class="space-y-16">
                @csrf @method('PUT')
                <input type="hidden" name="reservation_type" value="hotel_to_airport">

                <section class="space-y-8">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4 text-primary">
                        <i data-lucide="plane" class="w-5 h-5 text-primary"></i>
                        <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Detalles del Vuelo de Salida</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                        <div class="form-control md:col-span-6 lg:col-span-5">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-1">Aeropuerto de Destino</label>
                            <input type="text" name="origen_vuelo_salida" value="{{ old('origen_vuelo_salida', $reserva->origen_vuelo_salida) }}" class="input input-bordered bg-slate-50 h-12 rounded-xl w-full font-bold" required />
                        </div>
                        <div class="form-control md:col-span-2 lg:col-span-2">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-1">Nº Vuelo</label>
                            <input type="text" name="numero_vuelo_salida" value="{{ old('numero_vuelo_salida', $reserva->numero_vuelo_salida) }}" class="input input-bordered bg-slate-50 h-12 rounded-xl w-full font-bold" required />
                        </div>
                        <div class="form-control md:col-span-4 lg:col-span-5">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-1">Fecha y Hora Vuelo</label>
                            <div class="flex flex-wrap sm:flex-nowrap gap-2">
                                <input type="date" name="fecha_vuelo_salida" value="{{ old('fecha_vuelo_salida', $reserva->fecha_vuelo_salida) }}" class="input input-bordered bg-slate-50 h-12 rounded-xl flex-grow font-bold" required />
                                <input type="time" name="hora_vuelo_salida" value="{{ old('hora_vuelo_salida', $reserva->hora_vuelo_salida) }}" class="input input-bordered bg-slate-50 h-12 rounded-xl w-full sm:w-28 font-bold" required />
                            </div>
                        </div>
                    </div>
                </section>

                <section class="space-y-8">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4 text-primary">
                        <i data-lucide="map-pin" class="w-5 h-5 text-primary"></i>
                        <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Punto y Hora de Recogida</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-end">
                        <div class="form-control">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-1">Hotel de Recogida</label>
                            <select name="id_hotel_recogida" class="select select-bordered bg-slate-50 h-12 rounded-xl w-full font-bold" required>
                                @foreach($hotels as $partner)
                                    <option value="{{ $partner->id_hotel }}" @selected($partner->id_hotel == $reserva->id_hotel)>{{ $partner->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-1">Hora de Recogida en Hotel</label>
                            <input type="time" name="hora_recogida_hotel" value="{{ old('hora_recogida_hotel', $reserva->hora_recogida_hotel) }}" class="input input-bordered bg-slate-50 h-12 rounded-xl border-primary/30 w-full md:w-48 font-bold" required />
                        </div>
                    </div>
                </section>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 pt-10">
                    <section class="space-y-8 bg-slate-50/50 p-8 rounded-[2.5rem] border border-slate-100">
                        <div class="form-control">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-1">Vehículo de la Flota</label>
                            <select name="id_vehiculo" class="select select-bordered bg-white h-12 rounded-xl w-full font-bold" required>
                                @foreach($fleet as $unit)
                                    <option value="{{ $unit->id_vehiculo }}" @selected($unit->id_vehiculo == $reserva->id_vehiculo)>{{ $unit->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-1">Nº Pasajeros (Pax)</label>
                            <input type="number" name="num_viajeros" value="{{ old('num_viajeros', $reserva->num_viajeros) }}" class="input input-bordered bg-white h-12 rounded-xl w-full font-bold" required />
                        </div>
                    </section>

                    <section class="bg-orange-50/30 p-8 rounded-[2.5rem] border border-orange-100/50 space-y-6">
                        <input type="text" name="nombre_contacto" value="{{ old('nombre_contacto', $reserva->nombre_contacto) }}" placeholder="Nombre Titular" class="input input-bordered bg-white h-12 rounded-xl w-full font-bold" required />
                        <input type="email" name="email_contacto" value="{{ old('email_contacto', $reserva->email_cliente) }}" placeholder="Email de Contacto" class="input input-bordered bg-white h-12 rounded-xl w-full font-bold" required />
                        <input type="tel" name="telefono" value="{{ old('telefono', $reserva->telefono) }}" placeholder="Teléfono" class="input input-bordered bg-white h-12 rounded-xl w-full font-bold" required />
                    </section>
                </div>

                <div class="flex justify-between items-center pt-10 border-t border-slate-100">
                    <button type="button" onclick="window.history.back()" class="btn btn-ghost font-bold text-slate-400 uppercase tracking-widest text-[10px]">Descartar Cambios</button>
                    <button type="submit" class="btn bg-primary hover:bg-[#ea580c] border-none text-white px-12 h-14 shadow-xl shadow-orange-500/30 rounded-2xl font-black uppercase tracking-widest text-xs">
                        Guardar Expediente <i data-lucide="save" class="w-5 h-5 ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection