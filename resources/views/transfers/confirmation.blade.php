@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <div class="card bg-white shadow-2xl border border-slate-100 text-center overflow-hidden rounded-[2.5rem]">
        <div class="bg-primary p-12 text-white flex flex-col items-center">
            <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mb-6 shadow-inner">
                <i data-lucide="check" class="w-12 h-12 text-white stroke-[4px]"></i>
            </div>
            <h2 class="text-4xl font-black tracking-tight italic uppercase">¡Reserva <span class="underline decoration-white/30 underline-offset-8">Lista!</span></h2>
        </div>

        <div class="card-body p-10 lg:p-16">
            <p class="text-slate-500 text-lg leading-relaxed mb-10 font-medium">
                Tu solicitud ha sido procesada correctamente. En breves instantes recibirás un correo electrónico con todos los detalles del servicio.
            </p>

            <div class="bg-slate-50 rounded-[2rem] p-8 border border-slate-100 mb-12 shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-2">Localizador de Servicio</p>
                <span class="text-3xl font-black text-slate-800 font-mono tracking-tighter">{{ $localizador }}</span>
            </div>

            <div class="flex flex-col gap-4">
                <a href="{{ route('mis_reservas') }}" class="btn bg-slate-900 hover:bg-slate-800 border-none text-white h-14 rounded-2xl font-black uppercase tracking-widest text-xs">
                    Ver Registro de Servicios <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                </a>
                <a href="{{ route('home') }}" class="btn btn-ghost text-slate-400 font-bold uppercase tracking-widest text-[10px]">Ir a la Web</a>
            </div>
        </div>
        
        <div class="bg-slate-950 p-6">
            <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.4em] italic">Isla Transfers - Servicio Premium 24/7</p>
        </div>
    </div>
</div>
@endsection