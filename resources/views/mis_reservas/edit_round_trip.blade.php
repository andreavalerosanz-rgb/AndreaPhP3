@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4">
    <div class="card bg-white shadow-2xl border border-slate-100 rounded-[3rem] overflow-hidden">
        
        <div class="bg-slate-900 p-10 lg:p-14 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 bg-primary/20 rounded-2xl flex items-center justify-center text-primary shadow-inner">
                    <i data-lucide="refresh-cw" class="w-8 h-8"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-black italic uppercase tracking-tight">Editar <span class="text-primary">Trayectos</span></h2>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.3em] mt-1">Ref: {{ $reserva->localizador }}</p>
                </div>
            </div>
            <a href="{{ route('mis_reservas') }}" class="btn btn-ghost text-slate-400 hover:text-white border-white/10 uppercase font-black text-[10px] tracking-widest">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Volver al listado
            </a>
        </div>

        <div class="p-10 lg:p-16">
            @if ($errors->any())
                <div class="alert alert-error bg-red-50 border-red-200 text-red-700 rounded-[2rem] p-6 mb-12 flex flex-col items-start gap-2 shadow-sm">
                    <div class="flex items-center gap-2 font-black uppercase text-[10px] tracking-widest text-red-800">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        <span>Campos requeridos pendientes</span>
                    </div>
                    <ul class="list-disc list-inside text-sm font-medium ml-2">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('mis_reservas.update', $reserva->id_reserva) }}" class="space-y-20">
                @csrf
                @method('PUT')
                <input type="hidden" name="reservation_type" value="round_trip">

                {{-- IDA --}}
                <section class="space-y-10">
                    <div class="flex items-center gap-4 border-b border-slate-100 pb-6">
                        <span class="bg-orange-100 text-primary px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Tramo 1</span>
                        <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Vuelo de Ida · Aeropuerto → Hotel</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                        <div class="form-control col-span-1 lg:col-span-2">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-2">Aeropuerto de Origen</label>
                            <input type="text" name="origen_vuelo_entrada" value="{{ old('origen_vuelo_entrada', $reserva->origen_vuelo_entrada) }}" class="input input-bordered bg-slate-50 h-14 rounded-2xl font-bold" />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-2">Nº Vuelo Ida</label>
                            <input type="text" name="numero_vuelo_entrada" value="{{ old('numero_vuelo_entrada', $reserva->numero_vuelo_entrada) }}" class="input input-bordered bg-slate-50 h-14 rounded-2xl font-bold" />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-2">Día de Llegada</label>
                            <input type="date" name="fecha_entrada" value="{{ old('fecha_entrada', $reserva->fecha_entrada) }}" class="input input-bordered bg-slate-50 h-14 rounded-2xl font-bold" />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-2">Hora de Llegada (Vuelo)</label>
                            <input type="time" name="hora_entrada" value="{{ old('hora_entrada', $reserva->hora_entrada) }}" class="input input-bordered bg-slate-50 h-14 rounded-2xl font-bold" />
                        </div>
                        <div class="form-control lg:col-span-1">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-2">Hotel de Destino</label>
                            <select name="id_hotel_destino" id="id_hotel_destino" class="select select-bordered bg-slate-50 h-14 rounded-2xl font-bold">
                                @foreach($hotels as $partner)
                                    <option value="{{ $partner->id_hotel }}" @selected($partner->id_hotel == $reserva->id_hotel)>{{ $partner->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </section>

                {{-- VUELTA --}}
                <section class="space-y-10">
                    <div class="flex items-center gap-4 border-b border-slate-100 pb-6">
                        <span class="bg-slate-100 text-slate-500 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Tramo 2</span>
                        <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Vuelo de Vuelta · Hotel → Aeropuerto</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                        <div class="form-control col-span-1 lg:col-span-2">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-2">Aeropuerto de Destino</label>
                            <input type="text" name="origen_vuelo_salida" value="{{ old('origen_vuelo_salida', $reserva->origen_vuelo_salida) }}" class="input input-bordered bg-slate-50 h-14 rounded-2xl font-bold" />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-2">Nº Vuelo Vuelta</label>
                            <input type="text" name="numero_vuelo_salida" value="{{ old('numero_vuelo_salida', $reserva->numero_vuelo_salida) }}" class="input input-bordered bg-slate-50 h-14 rounded-2xl font-bold" />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-2">Día de Salida</label>
                            <input type="date" name="fecha_vuelo_salida" value="{{ old('fecha_vuelo_salida', $reserva->fecha_vuelo_salida) }}" class="input input-bordered bg-slate-50 h-14 rounded-2xl font-bold" />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-2">Hora del Vuelo</label>
                            <input type="time" name="hora_vuelo_salida" value="{{ old('hora_vuelo_salida', $reserva->hora_vuelo_salida) }}" class="input input-bordered bg-slate-50 h-14 rounded-2xl font-bold" />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-400 text-[10px] uppercase mb-2">Hora Recogida Hotel</label>
                            <input type="time" name="hora_recogida_hotel" value="{{ old('hora_recogida_hotel', $reserva->hora_recogida_hotel) }}" class="input input-bordered bg-slate-50 h-14 rounded-2xl border-primary/20 font-bold" />
                        </div>
                    </div>
                    <input type="hidden" name="id_hotel_recogida" id="id_hotel_recogida" value="{{ $reserva->id_hotel }}">
                </section>

                {{-- VEHICULO Y CONTACTO --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 pt-10">
                    <section class="space-y-8 bg-slate-50/50 p-10 rounded-[2.5rem] border border-slate-100">
                        <div class="flex items-center gap-3 text-primary">
                            <i data-lucide="car" class="w-5 h-5"></i>
                            <h3 class="font-black text-slate-800 uppercase tracking-widest text-[10px]">Asignación de Unidad</h3>
                        </div>
                        <div class="space-y-6">
                            <div class="form-control">
                                <label class="label font-bold text-slate-400 text-[10px] uppercase">Vehículo Asignado</label>
                                <select name="id_vehiculo" class="select select-bordered bg-white h-14 rounded-2xl font-bold">
                                    @foreach($fleet as $unit)
                                        <option value="{{ $unit->id_vehiculo }}" @selected($unit->id_vehiculo == $reserva->id_vehiculo)>
                                            {{ $unit->label }} — {{ number_format($unit->rate, 2) }}€ / tramo
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-control">
                                <label class="label font-bold text-slate-400 text-[10px] uppercase">Número de Pasajeros</label>
                                <input type="number" name="num_viajeros" value="{{ old('num_viajeros', $reserva->num_viajeros) }}" class="input input-bordered bg-white h-14 rounded-2xl font-bold" />
                            </div>
                        </div>
                    </section>

                    <section class="space-y-8 bg-orange-50/30 p-10 rounded-[2.5rem] border border-orange-100/50">
                        <div class="flex items-center gap-3 text-primary">
                            <i data-lucide="user" class="w-5 h-5"></i>
                            <h3 class="font-black text-slate-800 uppercase tracking-widest text-[10px]">Información de Contacto</h3>
                        </div>
                        <div class="space-y-6">
                            <input type="text" name="nombre_contacto" value="{{ old('nombre_contacto', $reserva->nombre_contacto) }}" placeholder="Nombre completo" class="input input-bordered bg-white w-full h-14 rounded-2xl font-bold" />
                            <input type="email" name="email_contacto" value="{{ old('email_contacto', $reserva->email_cliente) }}" placeholder="Correo electrónico" class="input input-bordered bg-white w-full h-14 rounded-2xl font-bold" />
                            <input type="tel" name="telefono" value="{{ old('telefono', $reserva->telefono) }}" placeholder="Teléfono" class="input input-bordered bg-white w-full h-14 rounded-2xl font-bold" />
                        </div>
                    </section>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-center gap-8 pt-10 border-t border-slate-100">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest text-center md:text-left">
                        Al guardar, se recalcularán los precios<br>basados en la tarifa de la unidad seleccionada.
                    </p>
                    <div class="flex gap-4 w-full md:w-auto">
                        <button type="button" onclick="window.history.back()" class="btn btn-ghost flex-1 md:flex-none h-14 rounded-2xl font-bold uppercase tracking-widest text-[10px]">Descartar Cambios</button>
                        <button type="submit" class="btn bg-primary hover:bg-[#ea580c] border-none text-white px-12 h-14 shadow-xl shadow-orange-500/30 rounded-2xl font-black uppercase tracking-widest text-xs flex-1 md:flex-none">
                            Actualizar Reserva <i data-lucide="save" class="w-5 h-5 ml-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const destino = document.getElementById('id_hotel_destino');
        const recogida = document.getElementById('id_hotel_recogida');
        if(destino && recogida) {
            destino.addEventListener('change', () => {
                recogida.value = destino.value;
            });
        }
    });
</script>
@endsection