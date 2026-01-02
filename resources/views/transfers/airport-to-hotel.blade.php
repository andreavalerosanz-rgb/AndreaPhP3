@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10">
    <div class="card bg-white shadow-2xl border border-slate-100 rounded-[2.5rem] overflow-hidden">
        
        <div class="bg-slate-900 p-8 text-white flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-black italic tracking-tight text-primary">Aeropuerto → <span class="text-white">Destino</span></h2>
                <p class="text-slate-400 text-xs mt-2 font-bold uppercase tracking-widest">Reserva tu servicio de llegada</p>
            </div>
            <i data-lucide="plane-landing" class="w-12 h-12 text-primary/20"></i>
        </div>

        <div class="p-8 lg:p-12">
            @if ($errors->any())
                <div class="alert alert-error bg-red-50 border-red-200 text-red-700 text-sm py-4 rounded-2xl mb-8 flex flex-col items-start shadow-sm">
                    <div class="flex items-center gap-2 font-bold text-red-800">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        <span>Revisar campos obligatorios</span>
                    </div>
                    <ul class="list-disc list-inside ml-7 font-medium">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('transfer.reserve.confirm') }}" class="space-y-12">
                @csrf
                <input type="hidden" name="reservation_type" value="airport_to_hotel">

                <section class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4 text-primary">
                        <i data-lucide="plane" class="w-5 h-5"></i>
                        <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Logística de Vuelo</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="form-control col-span-1 lg:col-span-2">
                            <label class="label font-bold text-slate-600 text-[10px] uppercase">Aeropuerto Origen</label>
                            <input type="text" name="aeropuerto_origen" value="{{ old('aeropuerto_origen') }}" class="input input-bordered bg-slate-50 font-bold" required />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-600 text-[10px] uppercase">Nº Vuelo</label>
                            <input type="text" name="num_vuelo" value="{{ old('num_vuelo') }}" class="input input-bordered bg-slate-50 font-bold" required />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-600 text-[10px] uppercase">Día Llegada</label>
                            <input type="date" name="fecha_llegada" min="{{ $minDate }}" value="{{ old('fecha_llegada') }}" class="input input-bordered bg-slate-50 font-bold" required />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-600 text-[10px] uppercase">Hora Llegada</label>
                            <input type="time" name="hora_llegada" value="{{ old('hora_llegada') }}" class="input input-bordered bg-slate-50 font-bold" required />
                        </div>
                    </div>
                </section>

                <section class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4 text-primary">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                        <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Destino y Flota</h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="form-control w-full">
                            <label class="label font-bold text-slate-600 text-[10px] uppercase">Hotel de Destino</label>
                            @if(Auth::guard('corporate')->check())
                                <input type="text" class="input input-bordered bg-orange-50/30 font-bold" value="{{ $partners->first()->nombre }}" readonly>
                                <input type="hidden" name="id_hotel_destino" value="{{ $partners->first()->id_hotel }}">
                            @else
                                <select name="id_hotel_destino" class="select select-bordered bg-slate-50 font-bold" required>
                                    <option value="">-- Elige el hotel --</option>
                                    @foreach($partners as $partner)
                                        <option value="{{ $partner->id_hotel }}" @selected(old('id_hotel_destino') == $partner->id_hotel)>{{ $partner->nombre }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="form-control col-span-2">
                                <label class="label font-bold text-slate-600 text-[10px] uppercase">Vehículo</label>
                                <select name="id_vehiculo" class="select select-bordered bg-slate-50 font-bold" required>
                                    @foreach($fleet as $unit)
                                        <option value="{{ $unit->id_vehiculo }}" @selected(old('id_vehiculo') == $unit->id_vehiculo)>
                                            {{ $unit->label }} — {{ number_format($unit->rate, 2) }}€
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-control">
                                <label class="label font-bold text-slate-600 text-[10px] uppercase">Pasajeros</label>
                                <input type="number" name="pax" min="1" value="{{ old('pax', 1) }}" class="input input-bordered bg-slate-50 font-bold" required />
                            </div>
                        </div>
                    </div>
                </section>

                <section class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4 text-primary">
                        <i data-lucide="user-cog" class="w-5 h-5"></i>
                        <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Titular de la Reserva</h3>
                    </div>
                    
                    @if(Auth::guard('corporate')->check() || Auth::guard('admin')->check())
                        <div class="form-control w-full mb-4">
                            <label class="label font-bold text-slate-600 text-[10px] uppercase italic">Asignar a cliente registrado</label>
                            <select name="id_viajero" class="select select-bordered bg-orange-50/50" required>
                                <option value="">-- Buscar cliente --</option>
                                @foreach($travelers as $traveler)
                                    <option value="{{ $traveler->id_viajero }}" @selected(old('id_viajero') == $traveler->id_viajero)>
                                        {{ $traveler->nombre }} ({{ $traveler->email_viajero }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @php
                            $u = Auth::guard('web')->user();
                            $nombre = $u ? $u->nombre : old('nombre_contacto');
                            $email = $u ? $u->email_viajero : old('email_contacto');
                        @endphp
                        <input type="text" name="nombre_contacto" value="{{ $nombre }}" placeholder="Nombre" class="input input-bordered bg-slate-50 font-bold" required />
                        <input type="email" name="email_contacto" value="{{ $email }}" placeholder="Email" class="input input-bordered bg-slate-50 font-bold" required />
                        <input type="tel" name="telefono" value="{{ old('telefono') }}" placeholder="Teléfono" class="input input-bordered bg-slate-50 font-bold" required />
                    </div>
                </section>

                <div class="flex justify-end gap-4 pt-10 border-t border-slate-100">
                    <a href="{{ route('dashboard') }}" class="btn btn-ghost font-bold text-slate-400 uppercase tracking-widest text-[10px]">Descartar</a>
                    <button type="submit" class="btn bg-primary hover:bg-[#ea580c] border-none text-white px-12 h-14 shadow-xl shadow-orange-500/30 rounded-2xl font-black uppercase tracking-widest text-xs">
                        Confirmar Llegada <i data-lucide="check-circle" class="w-5 h-5 ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection