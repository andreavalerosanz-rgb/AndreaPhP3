@extends('layouts.app')

@section('content')
<div class="space-y-24">
    {{-- HERO SECTION --}}
    <div class="hero min-h-[500px] rounded-[3rem] bg-slate-900 relative overflow-hidden shadow-2xl">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950 to-transparent opacity-80"></div>
        <div class="hero-content flex-col lg:flex-row p-10 lg:p-20 relative z-10 text-white gap-10">
            <div class="max-w-xl">
                <div class="inline-flex items-center gap-2 bg-slate-800/80 text-primary border border-primary/20 backdrop-blur-sm font-bold px-4 py-2 mb-6 rounded-full tracking-widest text-[10px]">
                    <i data-lucide="shield-check" class="w-3 h-3"></i> PUNTO OFICIAL ISLA TRANSFERS
                </div>
                
                <h1 class="text-5xl lg:text-7xl font-black italic leading-[1.1]">Tu destino, <span class="text-primary underline decoration-4 decoration-primary/30 underline-offset-8">bajo control.</span></h1>
                <p class="py-8 text-slate-400 text-lg">Reserva el traslado entre aeropuerto y hotel en segundos. Sin colas, sin esperas, solo confort.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('transfer.select-type') }}" 
                       class="btn bg-[#f97316] hover:bg-[#ea580c] border-none btn-lg text-white shadow-xl shadow-orange-500/40 px-10 rounded-2xl">
                        Reservar Traslado <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                    </a>
                </div>
            </div>
            <div class="hidden lg:block w-full max-w-lg">
                <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&q=80&w=1200" 
                     class="rounded-[2rem] shadow-2xl border-4 border-white/5 rotate-2 hover:rotate-0 transition-transform duration-500" alt="Traslado de lujo">
            </div>
        </div>
    </div>

    {{-- BENEFITS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @php
            $benefits = [
                ['icon' => 'zap', 'title' => 'Rápido', 'text' => 'Reserva en menos de 2 minutos'],
                ['icon' => 'shield', 'title' => 'Seguro', 'text' => 'Vehículos y conductores verificados'],
                ['icon' => 'banknote', 'title' => 'Precio Fijo', 'text' => 'Sin recargos sorpresa al llegar'],
                ['icon' => 'map-pinned', 'title' => 'Toda la Isla', 'text' => 'Cobertura total en todas las zonas']
            ];
        @endphp
        @foreach($benefits as $b)
        <div class="card bg-white p-8 border border-slate-100 shadow-sm hover:shadow-xl transition-all border-b-4 border-b-primary/20">
            <i data-lucide="{{ $b['icon'] }}" class="w-10 h-10 text-primary mb-4"></i>
            <h3 class="text-xl font-black text-slate-800">{{ $b['title'] }}</h3>
            <p class="text-slate-500 text-sm mt-2 leading-relaxed">{{ $b['text'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- FLEET SECTION --}}
    <section id="fleet" class="space-y-12 pb-20">
        <div class="text-center">
            <h2 class="text-4xl font-black text-slate-800 tracking-tight">Vehículos Premium</h2>
            <div class="w-20 h-1.5 bg-primary mx-auto mt-4 rounded-full"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($fleet as $v) {{-- Cambio de $vehiculos a $fleet --}}
            @php 
                // Usamos $metaFleet y el accessor ->label del nuevo modelo FleetUnit
                $meta = $metaFleet[$v->label] ?? [
                    'image' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=800',
                    'tagline' => 'Servicio de traslado profesional.',
                    'capacidad' => 'Consultar',
                    'maletas' => 'Estándar'
                ];
            @endphp
            <div class="card bg-white border border-slate-100 group shadow-sm hover:shadow-2xl transition-all rounded-[2.5rem] overflow-hidden">
                <figure class="h-64 bg-slate-50 relative overflow-hidden">
                    {{-- Usamos el accessor ->rate del modelo FleetUnit --}}
                    <div class="absolute top-4 right-4 badge bg-[#f97316] border-none text-white font-bold z-10 p-4 shadow-lg">{{ number_format($v->rate, 0) }}€</div>
                    
                    <img src="{{ $meta['image'] }}" 
                         alt="{{ $v->label }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                </figure>
                <div class="card-body p-10">
                    <h3 class="card-title text-2xl font-black text-slate-800">{{ $v->label }}</h3>
                    <p class="text-slate-500 text-sm mt-2 leading-relaxed">{{ $meta['tagline'] }}</p>
                    
                    <div class="flex gap-6 mt-8 pt-6 border-t border-slate-50">
                        <div class="flex items-center gap-2 text-[10px] font-black uppercase text-slate-400">
                            <i data-lucide="users" class="w-4 h-4 text-primary"></i> {{ $meta['capacidad'] }}
                        </div>
                        <div class="flex items-center gap-2 text-[10px] font-black uppercase text-slate-400">
                            <i data-lucide="briefcase" class="w-4 h-4 text-primary"></i> {{ $meta['maletas'] }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</div>
@endsection