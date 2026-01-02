@extends('layouts.app')

@section('content')
<div class="space-y-10">
    
    <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-8 lg:p-12 shadow-2xl">
        <div class="absolute -right-10 -top-10 h-64 w-64 rounded-full bg-primary/10 blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <a href="{{ route('profile.edit') }}" class="avatar placeholder group hover:scale-105 transition-transform duration-300">
                    <div class="bg-primary text-white rounded-3xl w-20 h-20 shadow-lg shadow-orange-500/30 ring-4 ring-transparent group-hover:ring-primary/20">
                        <span class="text-3xl font-black">{{ substr(Auth::guard('web')->user()->nombre, 0, 1) }}</span>
                    </div>
                </a>
                
                <div>
                    <a href="{{ route('profile.edit') }}" class="group">
                        <h1 class="text-3xl lg:text-4xl font-black text-white italic transition-all group-hover:translate-x-1">
                            ¡Hola, <span class="text-primary underline decoration-primary/30 decoration-2 underline-offset-4 group-hover:decoration-primary">{{ Auth::guard('web')->user()->nombre }}</span>!
                        </h1>
                    </a>
                    <p class="text-slate-400 font-medium mt-1">Gestiona tus trayectos de forma rápida y segura.</p>
                </div>
            </div>

            <div class="flex items-center gap-2 bg-slate-800/50 backdrop-blur-md border border-white/5 px-4 py-2 rounded-2xl">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
                </span>
                <span class="text-[10px] font-black text-white uppercase tracking-widest">Cuenta Particular Activa</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stat bg-white border border-slate-100 shadow-sm rounded-[2rem] p-8 group hover:border-primary/20 transition-all">
            <div class="stat-figure text-primary">
                <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                    <i data-lucide="clipboard-list" class="w-7 h-7"></i>
                </div>
            </div>
            <div class="stat-title text-slate-500 font-bold uppercase text-[10px] tracking-widest">Mis Traslados</div>
            <div class="stat-value text-slate-800 text-4xl font-black mt-1">{{ $stats['totalReservas'] ?? 0 }}</div>
            <div class="stat-desc mt-2 font-medium text-slate-400 italic">Reservas totales realizadas</div>
        </div>

        <div class="md:col-span-2 bg-white border border-slate-100 shadow-sm rounded-[2rem] p-8 flex items-center justify-between overflow-hidden relative">
            <div class="relative z-10">
                <h3 class="text-xl font-black text-slate-800">¿Necesitas un nuevo traslado?</h3>
                <p class="text-slate-500 text-sm mt-1">Reserva ahora y recibe tu localizador en el email.</p>
                <a href="{{ route('transfer.select-type') }}" class="btn btn-primary btn-sm mt-6 text-white px-6 rounded-xl shadow-lg shadow-orange-500/20 border-none bg-primary">
                    Nueva Reserva <i data-lucide="plus" class="w-4 h-4 ml-1"></i>
                </a>
            </div>
            <i data-lucide="plane" class="w-32 h-32 text-slate-50 absolute -right-4 -bottom-4 -rotate-12"></i>
        </div>
    </div>

    <div class="space-y-6">
        <h2 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Accesos Directos</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <a href="{{ route('transfer.select-type') }}" class="group bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all text-center">
                <div class="w-14 h-14 bg-orange-50 text-primary rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="map-pin"></i>
                </div>
                <span class="block font-black text-slate-800 text-sm">Nuevo Traslado</span>
            </a>

            <a href="{{ route('mis_reservas') }}" class="group bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all text-center">
                <div class="w-14 h-14 bg-orange-50 text-primary rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="calendar-days"></i>
                </div>
                <span class="block font-black text-slate-800 text-sm">Mis Reservas</span>
            </a>

            <a href="{{ route('calendar.index') }}" class="group bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all text-center">
                <div class="w-14 h-14 bg-orange-50 text-primary rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="calendar"></i>
                </div>
                <span class="block font-black text-slate-800 text-sm">Ver Calendario</span>
            </a>

            <a href="{{ route('profile.edit') }}" class="group bg-white border border-slate-100 p-6 rounded-[2rem] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all text-center">
                <div class="w-14 h-14 bg-orange-50 text-primary rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="settings-2"></i>
                </div>
                <span class="block font-black text-slate-800 text-sm">Mi Perfil</span>
            </a>
        </div>
    </div>
</div>
@endsection