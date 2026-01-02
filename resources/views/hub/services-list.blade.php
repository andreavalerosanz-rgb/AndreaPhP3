@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="bg-slate-900 p-10 rounded-[2.5rem] text-white flex flex-col md:flex-row justify-between items-center gap-6 shadow-2xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center text-primary"><i data-lucide="list"></i></div>
            <h2 class="text-2xl font-black italic uppercase">Listado <span class="text-primary text-white underline decoration-primary/40 underline-offset-4">Global</span></h2>
        </div>
        <div class="flex gap-4">
            <span class="badge bg-blue-500/20 text-blue-400 border-none px-4 py-3 rounded-xl text-[10px] font-black uppercase">Confirmadas</span>
            <span class="badge bg-green-500/20 text-green-400 border-none px-4 py-3 rounded-xl text-[10px] font-black uppercase">Finalizadas</span>
            <span class="badge bg-red-500/20 text-red-400 border-none px-4 py-3 rounded-xl text-[10px] font-black uppercase">Anuladas</span>
        </div>
    </div>

    <div class="card bg-white border border-slate-100 p-8 rounded-[2.5rem] shadow-sm">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-end">
            <div class="form-control">
                <label class="label font-bold text-[10px] text-slate-400 uppercase">Estado</label>
                <select name="estado" class="select select-bordered bg-slate-50 rounded-2xl">
                    <option value="">Todos los estados</option>
                    <option value="confirmada" @selected(request('estado') == 'confirmada')>Confirmada</option>
                    <option value="finalizada" @selected(request('estado') == 'finalizada')>Finalizada</option>
                    <option value="anulada" @selected(request('estado') == 'anulada')>Anulada</option>
                </select>
            </div>
            <div class="form-control">
                <label class="label font-bold text-[10px] text-slate-400 uppercase">Desde</label>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="input input-bordered bg-slate-50 rounded-2xl" />
            </div>
            <div class="form-control">
                <label class="label font-bold text-[10px] text-slate-400 uppercase">Hasta</label>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="input input-bordered bg-slate-50 rounded-2xl" />
            </div>
            <button class="btn bg-primary hover:bg-primary-focus border-none text-white h-12 rounded-2xl font-black uppercase tracking-widest text-[10px]">Filtrar Resultados <i data-lucide="filter" class="w-4 h-4 ml-1"></i></button>
        </form>
    </div>

    <div class="card bg-white border border-slate-100 rounded-[2.5rem] overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-slate-50 text-slate-500 font-black text-[10px] uppercase">
                    <tr>
                        <th class="py-6 px-8">Localizador</th>
                        <th>Tipo / Hotel</th>
                        <th>Fecha Traslado</th>
                        <th class="text-end">Comisión</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($reservas as $reserva)
                    <tr class="hover:bg-slate-50/50 transition-all border-l-4 {{ $reserva->estado == 'confirmada' ? 'border-l-blue-500' : ($reserva->estado == 'finalizada' ? 'border-l-green-500' : 'border-l-red-500') }}">
                        <td class="py-6 px-8"><span class="loc-code font-mono font-black text-slate-800 bg-slate-100 px-3 py-1 rounded-lg">{{ $reserva->localizador }}</span></td>
                        <td>
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-700">{{ $reserva->tipo_traslado_nombre }}</span>
                                <span class="text-[10px] font-black text-primary uppercase tracking-widest">{{ $reserva->hotel->nombre ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td><div class="flex items-center gap-2 font-bold text-slate-600 italic text-xs"><i data-lucide="calendar" class="w-4 h-4 text-slate-300"></i> {{ $reserva->fecha_entrada ?? $reserva->fecha_vuelo_salida }}</div></td>
                        <td class="text-end font-black text-primary">+{{ number_format($reserva->comision_ganada, 2) }}€</td>
                        <td class="text-center">
                            <span class="badge {{ $reserva->estado == 'confirmada' ? 'bg-blue-50 text-blue-600' : ($reserva->estado == 'finalizada' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600') }} border-none font-black text-[9px] uppercase px-4 py-2 rounded-xl">{{ $reserva->estado }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.reserva.detalle', $reserva->id_reserva) }}" class="btn btn-sm btn-ghost hover:bg-slate-900 hover:text-white rounded-xl font-black text-[9px] uppercase tracking-widest">Ver Detalle</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection