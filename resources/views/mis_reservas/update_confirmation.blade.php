@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <div class="card bg-white shadow-2xl border border-slate-100 text-center overflow-hidden rounded-[2.5rem]">
        <div class="bg-primary p-12 text-white flex flex-col items-center">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-6">
                <i data-lucide="save" class="w-10 h-10 text-white"></i>
            </div>
            <h2 class="text-3xl font-black tracking-tight italic uppercase">Datos <span class="underline underline-offset-8">Actualizados</span></h2>
        </div>

        <div class="card-body p-10 lg:p-16">
            <p class="text-slate-500 text-lg leading-relaxed mb-10">
                Los cambios en tu reserva <span class="font-black text-slate-800">#{{ $reserva->localizador }}</span> han sido guardados correctamente.
            </p>

            <div class="flex flex-col gap-4">
                <a href="{{ route('mis_reservas') }}" class="btn bg-slate-900 border-none text-white h-14 rounded-2xl font-black uppercase tracking-widest text-xs">
                    Volver al Listado <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection