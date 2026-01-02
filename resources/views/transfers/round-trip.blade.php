@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10">
    <div class="card bg-white shadow-2xl border border-slate-100 rounded-[2.5rem] overflow-hidden">
        
        <div class="bg-slate-900 p-8 text-white flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-black italic tracking-tight text-primary uppercase">Pack <span class="text-white">Ida y Vuelta</span></h2>
                <p class="text-slate-400 text-[10px] mt-2 font-bold uppercase tracking-[0.2em]">Trayecto completo Aeropuerto ↔ Hotel</p>
            </div>
            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center">
                <i data-lucide="refresh-cw" class="w-10 h-10 text-primary animate-spin-slow"></i>
            </div>
        </div>

        <div class="p-8 lg:p-12">
            @if ($errors->any())
                <div class="alert alert-error bg-red-50 border-red-200 text-red-700 text-sm py-4 rounded-2xl mb-10 flex flex-col items-start shadow-sm">
                    <div class="flex items-center gap-2 font-bold text-red-800">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        <span>Errores detectados en el formulario</span>
                    </div>
                    <ul class="list-disc list-inside ml-7 font-medium">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('transfer.reserve.confirm') }}" class="space-y-16">
                @csrf
                <input type="hidden" name="reservation_type" value="round_trip">

                {{-- TRAMO IDA --}}
                <section class="space-y-8">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4 text-primary">
                        <i data-lucide="plane-landing" class="w-6 h-6"></i>
                        <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs italic">Tramo 1: Llegada</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="form-control lg:col-span-2">
                            <label class="label font-bold text-slate-600 text-[10px] uppercase">Aeropuerto Origen</label>
                            <input type="text" name="origen_vuelo_entrada" value="{{ old('origen_vuelo_entrada') }}" class="input input-bordered bg-slate-50 font-bold" required />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-600 text-[10px] uppercase">Nº Vuelo Ida</label>
                            <input type="text" name="num_vuelo_ida" value="{{ old('num_vuelo_ida') }}" class="input input-bordered bg-slate-50 font-bold" required />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-600 text-[10px] uppercase">Día Llegada</label>
                            <input type="date" id="fecha_llegada" name="fecha_llegada" min="{{ $minDate }}" value="{{ old('fecha_llegada') }}" class="input input-bordered bg-slate-50 font-bold" required />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-600 text-[10px] uppercase">Hora Llegada</label>
                            <input type="time" name="hora_llegada" value="{{ old('hora_llegada') }}" class="input input-bordered bg-slate-50 font-bold" required />
                        </div>
                        <div class="form-control lg:col-span-4">
                            <label class="label font-bold text-slate-600 text-[10px] uppercase">Hotel de Estancia</label>
                            @if(Auth::guard('corporate')->check())
                                <input type="text" class="input input-bordered bg-orange-50/30 font-bold" value="{{ $partners->first()->nombre }}" readonly>
                                <input type="hidden" name="id_hotel_destino" id="id_hotel_destino" value="{{ $partners->first()->id_hotel }}">
                            @else
                                <select name="id_hotel_destino" id="id_hotel_destino" class="select select-bordered bg-slate-50 font-bold" required>
                                    <option value="">-- Elige el hotel --</option>
                                    @foreach($partners as $partner)
                                        <option value="{{ $partner->id_hotel }}" @selected(old('id_hotel_destino') == $partner->id_hotel)>{{ $partner->nombre }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                </section>

                {{-- TRAMO VUELTA --}}
                <section class="space-y-8">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4 text-primary">
                        <i data-lucide="plane-takeoff" class="w-6 h-6"></i>
                        <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs italic">Tramo 2: Regreso</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="form-control lg:col-span-2">
                            <label class="label font-bold text-slate-600 text-[10px] uppercase">Aeropuerto Destino</label>
                            <input type="text" name="origen_vuelo_salida" value="{{ old('origen_vuelo_salida') }}" class="input input-bordered bg-slate-50 font-bold" required />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-600 text-[10px] uppercase">Nº Vuelo Vuelta</label>
                            <input type="text" name="num_vuelo_salida" value="{{ old('num_vuelo_salida') }}" class="input input-bordered bg-slate-50 font-bold" required />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-600 text-[10px] uppercase">Día Salida</label>
                            <input type="date" id="fecha_vuelo_salida" name="fecha_vuelo_salida" min="{{ $minDate }}" value="{{ old('fecha_vuelo_salida') }}" class="input input-bordered bg-slate-50 font-bold" required />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-600 text-[10px] uppercase">Hora Vuelo</label>
                            <input type="time" name="hora_vuelo_salida" value="{{ old('hora_vuelo_salida') }}" class="input input-bordered bg-slate-50 font-bold" required />
                        </div>
                        <div class="form-control col-span-1 md:col-span-2">
                            <label class="label font-bold text-slate-600 text-[10px] uppercase">Hora Recogida Hotel</label>
                            <input type="time" name="hora_recogida_vuelta" value="{{ old('hora_recogida_vuelta') }}" class="input input-bordered bg-slate-50 font-bold" required />
                            <p class="text-[9px] text-slate-400 mt-1 font-bold italic uppercase">Recomendado: 3 horas antes del despegue</p>
                        </div>
                        <input type="hidden" name="id_hotel_recogida" id="id_hotel_recogida" value="{{ old('id_hotel_recogida') }}">
                    </div>
                </section>

                <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 pt-10 border-t border-slate-100">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 text-primary">
                            <i data-lucide="car" class="w-5 h-5"></i>
                            <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs italic">Transporte</h3>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="form-control col-span-2">
                                <select name="id_vehiculo" class="select select-bordered bg-slate-50 font-bold" required>
                                    @foreach($fleet as $unit)
                                        <option value="{{ $unit->id_vehiculo }}" @selected(old('id_vehiculo') == $unit->id_vehiculo)>
                                            {{ $unit->label }} — {{ number_format($unit->rate, 2) }}€ / tramo
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-control">
                                <input type="number" name="pax" min="1" value="{{ old('pax', 1) }}" class="input input-bordered bg-slate-50 font-bold" required />
                                <label class="label"><span class="label-text-alt font-black text-slate-400 text-[9px] uppercase">Pax</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center gap-3 text-primary">
                            <i data-lucide="user-cog" class="w-5 h-5"></i>
                            <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs italic">Contacto</h3>
                        </div>
                        @if(Auth::guard('corporate')->check() || Auth::guard('admin')->check())
                            <div class="form-control w-full">
                                <select name="id_viajero" class="select select-bordered bg-orange-50/50" required>
                                    <option value="">-- Asignar Cliente --</option>
                                    @foreach($travelers as $traveler)
                                        <option value="{{ $traveler->id_viajero }}" @selected(old('id_viajero') == $traveler->id_viajero)>
                                            {{ $traveler->nombre }} ({{ $traveler->email_viajero }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php
                                $u = Auth::guard('web')->user();
                                $nombre = $u ? $u->nombre : old('nombre_contacto');
                                $email = $u ? $u->email_viajero : old('email_contacto');
                            @endphp
                            <input type="text" name="nombre_contacto" value="{{ $nombre }}" placeholder="Nombre" class="input input-bordered bg-slate-50 font-bold" required />
                            <input type="tel" name="telefono" value="{{ old('telefono') }}" placeholder="Teléfono" class="input input-bordered bg-slate-50 font-bold" required />
                            <input type="email" name="email_contacto" value="{{ $email }}" placeholder="Email" class="input input-bordered bg-slate-50 font-bold md:col-span-2" required />
                        </div>
                    </div>
                </section>

                <div class="flex justify-end gap-4 pt-10 border-t border-slate-100">
                    <a href="{{ route('dashboard') }}" class="btn btn-ghost font-bold text-slate-400 uppercase tracking-widest text-[10px]">Cancelar</a>
                    <button type="submit" class="btn bg-primary hover:bg-[#ea580c] border-none text-white px-12 h-14 shadow-xl shadow-orange-500/30 rounded-2xl font-black uppercase tracking-widest text-xs">
                        Confirmar Reserva Completa <i data-lucide="check-circle" class="w-5 h-5 ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fIda = document.getElementById('fecha_llegada');
    const fVuelta = document.getElementById('fecha_vuelo_salida');
    const hDestino = document.getElementById('id_hotel_destino');
    const hRecogida = document.getElementById('id_hotel_recogida');

    if(fIda && fVuelta) {
        fIda.addEventListener('change', () => { 
            fVuelta.min = fIda.value; 
            if(fVuelta.value && fVuelta.value < fIda.value) fVuelta.value = fIda.value;
        });
    }
    
    if(hDestino && hRecogida) {
        hDestino.addEventListener('change', () => { hRecogida.value = hDestino.value; });
    }
});
</script>
@endsection