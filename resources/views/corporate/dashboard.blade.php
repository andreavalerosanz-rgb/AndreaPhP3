@extends('layouts.app')

@section('content')
<div class="space-y-10 pb-20">
    
    <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-10 lg:p-14 shadow-2xl">
        <div class="absolute -right-10 -top-10 h-64 w-64 rounded-full bg-primary/10 blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-6 text-white">
                <div class="avatar placeholder">
                    <div class="bg-primary text-white rounded-3xl w-20 h-20 shadow-lg shadow-orange-500/30">
                        <span class="text-3xl font-black italic">
                            {{ substr(Auth::guard('corporate')->user()->nombre, 0, 1) }}
                        </span>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl lg:text-4xl font-black italic uppercase tracking-tight">
                        ¡Hola, <span class="text-primary">{{ Auth::guard('corporate')->user()->nombre }}</span>!
                    </h2>
                    <p class="text-slate-400 font-medium mt-1 uppercase tracking-widest text-[10px]">Panel de Control Corporativo</p>
                </div>
            </div>
            <div class="badge bg-slate-800 border border-white/10 text-primary font-black px-6 py-4 rounded-xl text-[10px] uppercase tracking-widest">
                Partner Hotelero
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Reservas --}}
        <div class="bg-white border border-slate-100 p-10 rounded-[2.5rem] shadow-sm group hover:border-primary/20 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Reservas Totales</p>
                    <h3 class="text-4xl font-black text-slate-800 mt-2">{{ $stats['totalTraslados'] ?? 0 }}</h3>
                </div>
                <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                    <i data-lucide="clipboard-check" class="w-7 h-7"></i>
                </div>
            </div>
        </div>

        {{-- Comisión --}}
        <div class="bg-white border border-slate-100 p-10 rounded-[2.5rem] shadow-sm group hover:border-primary/20 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tu Comisión</p>
                    <h3 class="text-4xl font-black text-slate-800 mt-2">{{ Auth::guard('corporate')->user()->Comision }}%</h3>
                </div>
                <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-500 group-hover:scale-110 transition-transform">
                    <i data-lucide="percent" class="w-7 h-7"></i>
                </div>
            </div>
        </div>

        {{-- Zona Operativa - CORREGIDO --}}
        <div class="bg-white border border-slate-100 p-10 rounded-[2.5rem] shadow-sm group hover:border-primary/20 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Zona Operativa</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-2 truncate max-w-[200px]">
                        {{-- Se cambia 'zona' por 'region' para coincidir con el modelo --}}
                        {{ Auth::guard('corporate')->user()->region->descripcion ?? 'Sin zona' }}
                    </h3>
                </div>
                <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                    <i data-lucide="map-pin" class="w-7 h-7"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Resto del archivo se mantiene igual... --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <div class="bg-slate-50 border border-slate-100 rounded-[3rem] p-12 shadow-inner">
            <div class="flex items-center gap-4 mb-10 border-b border-slate-200 pb-6">
                <i data-lucide="plus-circle" class="text-primary w-6 h-6"></i>
                <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Nueva Reserva para Huésped</h3>
            </div>
            
            <div class="grid gap-4">
                @foreach([
                    ['val' => 'airport_to_hotel', 'label' => 'Llegada', 'desc' => 'Aeropuerto → Hotel'],
                    ['val' => 'hotel_to_airport', 'label' => 'Salida', 'desc' => 'Hotel → Aeropuerto'],
                    ['val' => 'round_trip', 'label' => 'Ida y Vuelta', 'desc' => 'Servicio Completo']
                ] as $btn)
                <form method="POST" action="{{ route('transfer.select-type.post') }}">
                    @csrf 
                    <input type="hidden" name="reservation_type" value="{{ $btn['val'] }}">
                    <button class="w-full flex items-center justify-between p-6 bg-white hover:bg-primary hover:text-white rounded-2xl shadow-sm transition-all group border border-slate-100">
                        <div class="text-start">
                            <span class="block font-black italic uppercase tracking-tight text-sm">{{ $btn['label'] }}</span>
                            <span class="block text-[9px] font-bold uppercase tracking-widest text-slate-400 group-hover:text-white/70">{{ $btn['desc'] }}</span>
                        </div>
                        <i data-lucide="chevron-right" class="w-5 h-5 opacity-30 group-hover:opacity-100 transition-all"></i>
                    </button>
                </form>
                @endforeach
            </div>
        </div>

        <div class="bg-white border border-slate-100 rounded-[3rem] p-12 shadow-xl">
            <div class="flex items-center gap-4 mb-10 border-b border-slate-100 pb-6">
                <i data-lucide="zap" class="text-primary w-6 h-6"></i>
                <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Accesos Directos</h3>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <a href="{{ route('mis_reservas') }}" class="flex flex-col items-center justify-center p-8 bg-slate-50 rounded-3xl border border-transparent hover:border-primary/30 transition-all group">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-primary shadow-sm mb-4 group-hover:scale-110 transition-transform">
                        <i data-lucide="calendar"></i>
                    </div>
                    <span class="font-black text-slate-800 uppercase text-[10px] tracking-widest">Mis Reservas</span>
                </a>
                
                <a href="{{ route('corporate.comissions') }}" class="flex flex-col items-center justify-center p-8 bg-orange-50/50 rounded-3xl border border-transparent hover:border-primary/30 transition-all group">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-primary shadow-sm mb-4 group-hover:scale-110 transition-transform">
                        <i data-lucide="wallet"></i>
                    </div>
                    <span class="font-black text-slate-800 uppercase text-[10px] tracking-widest">Comisiones</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection