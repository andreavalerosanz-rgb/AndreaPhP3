@extends('layouts.app')

@section('content')
@php
    $tipoLabels = [1 => "Llegada", 2 => "Salida", 3 => "Ida y Vuelta"];
    $tipoIcon = [1 => "plane-landing", 2 => "plane-takeoff", 3 => "refresh-cw"];
    $tipoColor = [1 => "text-green-500 bg-green-50", 2 => "text-blue-500 bg-blue-50", 3 => "text-primary bg-orange-50"];
    $esIdaVuelta = $reserva->id_tipo_reserva == 3;
@endphp

<div class="max-w-5xl mx-auto space-y-10 pb-20">
    <div class="bg-slate-900 rounded-[3rem] p-10 lg:p-14 text-white shadow-2xl relative overflow-hidden text-center md:text-left">
        <div class="absolute -right-10 -top-10 h-40 w-40 bg-primary/20 rounded-full blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 {{ $tipoColor[$reserva->id_tipo_reserva] }} rounded-3xl flex items-center justify-center shadow-lg">
                    <i data-lucide="{{ $tipoIcon[$reserva->id_tipo_reserva] }}" class="w-10 h-10"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-black italic uppercase tracking-tight italic">Servicio <span class="text-primary underline underline-offset-8">#{{ $reserva->id_reserva }}</span></h2>
                    <p class="text-slate-500 font-bold uppercase text-[10px] mt-2 tracking-widest italic">Tipo: {{ $tipoLabels[$reserva->id_tipo_reserva] }}</p>
                </div>
            </div>
            <div class="flex flex-col items-center md:items-end gap-2">
                <span class="text-[10px] font-black uppercase text-slate-500 tracking-[0.3em]">Referencia</span>
                <span class="badge bg-slate-800 border border-white/5 text-primary font-black px-8 py-6 rounded-2xl text-2xl tracking-tighter shadow-xl">{{ $reserva->localizador }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        {{-- LOGÍSTICA DE TRAYECTOS --}}
        <div class="md:col-span-8 space-y-8">
            <div class="grid grid-cols-1 {{ $esIdaVuelta ? 'sm:grid-cols-2' : '' }} gap-8">
                {{-- IDA --}}
                @if($reserva->id_tipo_reserva != 2)
                <div class="bg-white border border-slate-100 p-8 rounded-[2.5rem] shadow-sm relative overflow-hidden">
                    <div class="absolute right-0 top-0 p-4 opacity-5"><i data-lucide="plane-landing" class="w-20 h-20 text-green-600"></i></div>
                    <h4 class="font-black text-slate-800 uppercase tracking-widest text-[10px] mb-6 border-b border-slate-50 pb-4">Vuelo de Llegada</h4>
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between"><span class="text-slate-400 font-bold uppercase text-[9px]">Fecha</span> <span class="font-black text-slate-800">{{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d-m-Y') }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-400 font-bold uppercase text-[9px]">Hora Vuelo</span> <span class="font-black text-green-600">{{ substr($reserva->hora_entrada,0,5) }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-400 font-bold uppercase text-[9px]">Nº Vuelo</span> <span class="font-bold text-slate-700">{{ $reserva->numero_vuelo_entrada }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-400 font-bold uppercase text-[9px]">Origen</span> <span class="font-bold text-slate-700 truncate max-w-[120px]">{{ $reserva->origen_vuelo_entrada }}</span></div>
                    </div>
                </div>
                @endif

                {{-- VUELTA --}}
                @if($reserva->id_tipo_reserva != 1)
                <div class="bg-white border border-slate-100 p-8 rounded-[2.5rem] shadow-sm relative overflow-hidden">
                    <div class="absolute right-0 top-0 p-4 opacity-5"><i data-lucide="plane-takeoff" class="w-20 h-20 text-blue-600"></i></div>
                    <h4 class="font-black text-slate-800 uppercase tracking-widest text-[10px] mb-6 border-b border-slate-50 pb-4">Vuelo de Salida</h4>
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between"><span class="text-slate-400 font-bold uppercase text-[9px]">Fecha</span> <span class="font-black text-slate-800">{{ \Carbon\Carbon::parse($reserva->fecha_vuelo_salida)->format('d-m-Y') }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-400 font-bold uppercase text-[9px]">Recogida Hotel</span> <span class="font-black text-blue-600">{{ substr($reserva->hora_vuelo_salida,0,5) }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-400 font-bold uppercase text-[9px]">Nº Vuelo</span> <span class="font-bold text-slate-700">{{ $reserva->numero_vuelo_salida }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-400 font-bold uppercase text-[9px]">Destino</span> <span class="font-bold text-slate-700 truncate max-w-[120px]">{{ $reserva->origen_vuelo_salida }}</span></div>
                    </div>
                </div>
                @endif
            </div>

            {{-- INFORMACIÓN DEL PASAJERO --}}
            <div class="bg-slate-50 border border-slate-100 p-10 rounded-[2.5rem] shadow-inner space-y-6">
                <div class="flex items-center gap-3"><i data-lucide="users" class="text-primary w-5 h-5"></i><h4 class="font-black text-slate-800 uppercase tracking-widest text-[10px]">Titular y Pasajeros</h4></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <div><p class="text-[9px] text-slate-400 font-bold uppercase mb-1">Email Cliente</p><p class="font-black text-slate-800 italic underline decoration-primary/20 decoration-2">{{ $reserva->email_cliente }}</p></div>
                    <div class="flex items-center gap-4"><div class="px-6 py-2 bg-white rounded-2xl border border-slate-200 shadow-sm"><p class="text-[9px] text-slate-400 font-bold uppercase">Viajeros</p><p class="text-xl font-black text-primary">{{ $reserva->num_viajeros }} 👤</p></div></div>
                </div>
            </div>
        </div>

        {{-- COLUMNA CONTROL --}}
        <div class="md:col-span-4 space-y-8">
            <div class="bg-white border border-slate-100 p-10 rounded-[2.5rem] shadow-xl text-center flex flex-col justify-center">
                <i data-lucide="hotel" class="w-10 h-10 text-slate-200 mx-auto mb-4"></i>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Destino Final</p>
                <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter italic">{{ $hotel?->nombre ?? 'Sin hotel' }}</h3>
                <div class="badge bg-slate-50 text-slate-500 border-none font-bold text-[9px] uppercase px-4 py-3 rounded-lg mt-4">{{ $vehiculo?->descripcion ?? 'Vehículo Estándar' }}</div>
            </div>

            <div class="flex flex-col gap-4">
                <a href="{{ request()->query('from') === 'mis_reservas' ? route('mis_reservas') : route('calendar.index') }}" class="btn bg-slate-900 border-none text-white h-14 rounded-2xl font-black uppercase text-xs shadow-xl shadow-slate-900/20">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Volver a la Agenda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection