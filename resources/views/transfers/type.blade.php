@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <div class="card bg-white shadow-2xl border border-slate-100 rounded-[2.5rem] overflow-hidden">
        <div class="bg-slate-900 p-8 text-white flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black italic">Nueva <span class="text-primary">Reserva</span></h2>
                <p class="text-slate-400 text-sm mt-1 font-medium">Selecciona la modalidad de tu traslado.</p>
            </div>
            <i data-lucide="map-pinned" class="w-12 h-12 text-primary/40"></i>
        </div>

        <div class="card-body p-8 lg:p-12">
            <form method="POST" action="{{ route('transfer.select-type.post') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    
                    <label class="relative group cursor-pointer">
                        <input type="radio" name="reservation_type" value="airport_to_hotel" class="peer absolute opacity-0" checked required>
                        <div class="flex flex-col items-center p-8 border-2 border-slate-100 rounded-3xl bg-slate-50 transition-all group-hover:bg-orange-50 peer-checked:border-primary peer-checked:bg-orange-50/50 peer-checked:shadow-lg">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-slate-400 peer-checked:text-primary shadow-sm mb-4 transition-colors">
                                <i data-lucide="plane-landing" class="w-8 h-8"></i>
                            </div>
                            <span class="font-black text-slate-800 text-sm uppercase tracking-wider text-center">Llegada</span>
                            <p class="text-[10px] text-slate-400 font-bold mt-1 text-center">Aeropuerto → Hotel</p>
                        </div>
                    </label>

                    <label class="relative group cursor-pointer">
                        <input type="radio" name="reservation_type" value="hotel_to_airport" class="peer absolute opacity-0" required>
                        <div class="flex flex-col items-center p-8 border-2 border-slate-100 rounded-3xl bg-slate-50 transition-all group-hover:bg-orange-50 peer-checked:border-primary peer-checked:bg-orange-50/50 peer-checked:shadow-lg">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-slate-400 peer-checked:text-primary shadow-sm mb-4 transition-colors">
                                <i data-lucide="plane-takeoff" class="w-8 h-8"></i>
                            </div>
                            <span class="font-black text-slate-800 text-sm uppercase tracking-wider text-center">Salida</span>
                            <p class="text-[10px] text-slate-400 font-bold mt-1 text-center">Hotel → Aeropuerto</p>
                        </div>
                    </label>

                    <label class="relative group cursor-pointer">
                        <input type="radio" name="reservation_type" value="round_trip" class="peer absolute opacity-0" required>
                        <div class="flex flex-col items-center p-8 border-2 border-slate-100 rounded-3xl bg-slate-50 transition-all group-hover:bg-orange-50 peer-checked:border-primary peer-checked:bg-orange-50/50 peer-checked:shadow-lg">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-slate-400 peer-checked:text-primary shadow-sm mb-4 transition-colors">
                                <i data-lucide="refresh-cw" class="w-8 h-8"></i>
                            </div>
                            <span class="font-black text-slate-800 text-sm uppercase tracking-wider text-center">Pack Total</span>
                            <p class="text-[10px] text-slate-400 font-bold mt-1 text-center">Ida y Vuelta</p>
                        </div>
                    </label>
                </div>

                <div class="flex justify-end gap-4 border-t border-slate-100 pt-8">
                    <a href="{{ route('dashboard') }}" class="btn btn-ghost text-slate-400">Cancelar</a>
                    <button type="submit" class="btn bg-primary hover:bg-[#ea580c] border-none text-white px-10 shadow-xl shadow-orange-500/30 h-14 rounded-2xl font-black uppercase tracking-widest text-xs">
                        Configurar Traslado <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection