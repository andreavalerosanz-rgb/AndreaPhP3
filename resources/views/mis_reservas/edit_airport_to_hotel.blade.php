@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <div class="card bg-white shadow-2xl border border-slate-100 rounded-[2.5rem] overflow-hidden">
        
        <div class="bg-slate-900 p-10 text-white flex justify-between items-center">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-primary/20 rounded-2xl flex items-center justify-center text-primary"><i data-lucide="plane-landing" class="w-7 h-7"></i></div>
                <div>
                    <h2 class="text-2xl font-black italic uppercase">Modificar <span class="text-primary">Llegada</span></h2>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">Ref: {{ $reserva->localizador }}</p>
                </div>
            </div>
            <a href="{{ route('mis_reservas') }}" class="btn btn-ghost btn-sm text-slate-400 uppercase font-black text-[10px]">Volver</a>
        </div>

        <div class="p-10 lg:p-14">
            <form method="POST" action="{{ route('mis_reservas.update', $reserva->id_reserva) }}" class="space-y-12">
                @csrf @method('PUT')
                
                <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="form-control">
                        <label class="label font-black text-slate-400 text-[10px] uppercase mb-1">Origen Vuelo</label>
                        <input type="text" name="origen_vuelo_entrada" value="{{ old('origen_vuelo_entrada', $reserva->origen_vuelo_entrada) }}" class="input input-bordered bg-slate-50 font-bold" required />
                    </div>
                    <div class="form-control">
                        <label class="label font-black text-slate-400 text-[10px] uppercase mb-1">Día y Hora</label>
                        <div class="flex gap-2">
                            <input type="date" name="fecha_entrada" value="{{ old('fecha_entrada', $reserva->fecha_entrada) }}" class="input input-bordered bg-slate-50 font-bold flex-1" required />
                            <input type="time" name="hora_entrada" value="{{ old('hora_entrada', $reserva->hora_entrada) }}" class="input input-bordered bg-slate-50 font-bold w-32" required />
                        </div>
                    </div>
                    <div class="form-control">
                        <label class="label font-black text-slate-400 text-[10px] uppercase mb-1">Nº Vuelo</label>
                        <input type="text" name="numero_vuelo_entrada" value="{{ old('numero_vuelo_entrada', $reserva->numero_vuelo_entrada) }}" class="input input-bordered bg-slate-50 font-bold" required />
                    </div>
                    <div class="form-control">
                        <label class="label font-black text-slate-400 text-[10px] uppercase mb-1">Hotel Destino</label>
                        <select name="id_hotel_destino" class="select select-bordered bg-slate-50 font-bold" required>
                            @foreach($hotels as $partner)
                                <option value="{{ $partner->id_hotel }}" @selected($partner->id_hotel == $reserva->id_hotel)>{{ $partner->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </section>

                <div class="bg-orange-50/30 p-8 rounded-[2rem] border border-orange-100 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="form-control">
                        <label class="label font-black text-slate-400 text-[10px] uppercase">Vehículo</label>
                        <select name="id_vehiculo" class="select select-bordered font-bold" required>
                            @foreach($fleet as $v)
                                <option value="{{ $v->id_vehiculo }}" @selected($v->id_vehiculo == $reserva->id_vehiculo)>{{ $v->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label font-black text-slate-400 text-[10px] uppercase">Pax</label>
                        <input type="number" name="num_viajeros" value="{{ old('num_viajeros', $reserva->num_viajeros) }}" class="input input-bordered font-bold" required />
                    </div>
                    <div class="form-control">
                        <label class="label font-black text-slate-400 text-[10px] uppercase">Email Contacto</label>
                        <input type="email" name="email_contacto" value="{{ old('email_contacto', $reserva->email_cliente) }}" class="input input-bordered font-bold" required />
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-10 border-t border-slate-100">
                    <button type="submit" class="btn bg-primary hover:bg-[#ea580c] text-white px-10 rounded-2xl font-black uppercase tracking-widest text-xs h-14 border-none">
                        Guardar Cambios <i data-lucide="save" class="w-4 h-4 ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection