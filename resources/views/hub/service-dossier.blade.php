@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 space-y-10">
    <div class="bg-slate-900 p-10 rounded-[3rem] text-white flex justify-between items-center shadow-2xl relative overflow-hidden">
        <div class="absolute -right-10 -top-10 h-40 w-40 bg-primary/20 rounded-full blur-3xl"></div>
        <div>
            <h2 class="text-3xl font-black italic uppercase tracking-tight">Expediente <span class="text-primary">Servicio</span></h2>
            <p class="text-slate-500 font-bold uppercase text-[10px] mt-2 tracking-widest">Servicio Registrado ID: #{{ $reserva->id_reserva }}</p>
        </div>
        <div class="text-right">
            <span class="badge bg-primary text-white border-none font-black px-6 py-5 rounded-2xl text-2xl tracking-tighter shadow-lg shadow-orange-500/20">{{ $reserva->localizador }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <div class="bg-white border border-slate-100 rounded-[3rem] p-10 shadow-sm space-y-10">
            <div class="flex items-center gap-3 border-b border-slate-50 pb-4"><i data-lucide="info" class="text-primary w-5 h-5"></i><h3 class="font-black text-slate-800 uppercase tracking-widest text-[10px]">Logística</h3></div>
            <div class="space-y-6">
                <div><p class="text-[9px] font-black text-slate-400 uppercase mb-1">Partner / Hotel</p><p class="text-lg font-black text-slate-800">{{ $reserva->hotel->nombre ?? 'N/A' }}</p></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><p class="text-[9px] font-black text-slate-400 uppercase">Trayecto</p><p class="font-bold text-slate-700">{{ $reserva->tipo_traslado_nombre }}</p></div>
                    <div><p class="text-[9px] font-black text-slate-400 uppercase">Pasajeros</p><p class="font-black text-slate-800 text-lg">{{ $reserva->num_viajeros }} 👥</p></div>
                </div>
            </div>
        </div>

        <div class="bg-orange-50 border border-orange-100 rounded-[3rem] p-10 shadow-inner flex flex-col justify-center text-center">
            <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] mb-2">Liquidación Andrea</p>
            <h3 class="text-5xl font-black text-slate-900 tracking-tighter mb-4">{{ number_format($reserva->precio_total, 2) }}€</h3>
            <div class="badge bg-white text-primary font-black border-none px-6 py-4 rounded-xl shadow-sm italic">Comisión Andrea: +{{ number_format($reserva->comision_ganada, 2) }}€</div>
        </div>
    </div>

    <div class="bg-slate-900 rounded-[3rem] p-10 text-white shadow-2xl">
        <div class="flex items-center gap-3 mb-8 border-b border-white/10 pb-4"><i data-lucide="user" class="text-primary w-5 h-5"></i><h3 class="font-black uppercase tracking-widest text-[10px]">Información del Cliente</h3></div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
            <div><p class="text-[9px] text-slate-500 font-bold uppercase mb-1">Titular</p><p class="font-bold text-lg">{{ $reserva->nombre_contacto }}</p></div>
            <div><p class="text-[9px] text-slate-500 font-bold uppercase mb-1">Teléfono</p><p class="text-primary font-black text-lg">{{ $reserva->telefono }}</p></div>
            <div><p class="text-[9px] text-slate-500 font-bold uppercase mb-1">Origen / Creador</p><p class="font-bold italic">{{ $reserva->creador_nombre ?? 'Reserva Directa' }}</p></div>
        </div>
    </div>

    <div class="flex justify-center"><a href="{{ route('admin.reservations.list') }}" class="btn btn-ghost text-slate-400 font-black uppercase text-[10px] tracking-widest hover:text-primary transition-colors">Volver al Registro General</a></div>
</div>
@endsection