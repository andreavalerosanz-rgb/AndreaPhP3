@extends('layouts.app')

@section('content')
<div class="space-y-10 pb-20">
    
    <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-10 lg:p-14 shadow-2xl text-white">
        <div class="absolute -left-10 -top-10 h-64 w-64 rounded-full bg-primary/10 blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 bg-primary/20 rounded-2xl flex items-center justify-center text-primary shadow-lg shadow-orange-500/20">
                    <i data-lucide="piggy-bank" class="w-10 h-10"></i>
                </div>
                <div>
                    <h1 class="text-3xl lg:text-4xl font-black italic italic">Mis <span class="text-primary text-white underline decoration-primary/40 decoration-4 underline-offset-4">Comisiones</span></h1>
                    <p class="text-slate-400 font-medium mt-1 uppercase tracking-widest text-[10px]">Resumen acumulado por servicios prestados</p>
                </div>
            </div>
            <div class="bg-slate-800/80 backdrop-blur-md px-8 py-4 rounded-[2rem] border border-white/5 flex flex-col items-center">
                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Total del Periodo</span>
                <div class="text-3xl font-black text-primary">{{ number_format($totalComision, 2) }}€</div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-100 p-8 rounded-[2.5rem] shadow-sm max-w-4xl mx-auto">
        <form method="GET" action="{{ route('corporate.comissions') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div class="form-control">
                <label class="label font-bold text-[10px] text-slate-400 uppercase tracking-widest">Mes</label>
                <select name="month" class="select select-bordered bg-slate-50 rounded-2xl h-12">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                            {{ ucfirst(\Carbon\Carbon::createFromDate($year, $i, 1)->locale('es')->monthName) }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="form-control">
                <label class="label font-bold text-[10px] text-slate-400 uppercase tracking-widest">Año</label>
                <select name="year" class="select select-bordered bg-slate-50 rounded-2xl h-12">
                    @for ($i = now()->year - 2; $i <= now()->year + 1; $i++)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>Año {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <button type="submit" class="btn bg-primary hover:bg-[#ea580c] border-none text-white h-12 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-orange-500/20">
                Filtrar Resultados <i data-lucide="filter" class="w-4 h-4 ml-1"></i>
            </button>

            <a href="{{ route('corporate.comissions', ['all' => 1]) }}" class="btn btn-ghost text-slate-400 font-bold uppercase tracking-widest text-[10px] hover:text-slate-800">
                Ver histórico
            </a>
        </form>
    </div>

    <div class="bg-white border border-slate-100 rounded-[3rem] overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-slate-50 text-slate-500 font-black text-[10px] uppercase">
                    <tr>
                        <th class="py-6 px-10">Referencia de Reserva</th>
                        <th>Fecha de Servicio</th>
                        <th class="text-end">Importe Bruto</th>
                        <th class="text-end px-10">Tu Comisión</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @forelse($commissionReport as $report)
                        <tr class="hover:bg-slate-50/50 transition-all border-l-4 border-transparent hover:border-l-primary">
                            <td class="py-6 px-10">
                                <span class="font-mono font-black text-slate-800 bg-slate-100 px-3 py-1 rounded-lg">
                                    {{ $report['localizador'] }}
                                </span>
                            </td>
                            <td class="font-bold text-slate-600 italic">
                                <i data-lucide="calendar" class="w-4 h-4 inline mr-1 text-slate-300"></i>
                                {{ \Carbon\Carbon::parse($report['fecha_traslado'])->format('d-m-Y') }}
                            </td>
                            <td class="text-end font-medium text-slate-400">
                                {{ number_format($report['precio_total'], 2) }}€
                            </td>
                            <td class="text-end px-10">
                                <span class="text-xl font-black text-primary tracking-tighter">
                                    +{{ number_format($report['comision_hotel'], 2) }}€
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-20 text-slate-400 italic font-bold">
                                No se han registrado comisiones en este periodo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="bg-slate-900 p-8 lg:px-14 flex flex-col md:flex-row justify-between items-center gap-6 text-white">
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-primary animate-ping"></div>
                <span class="font-black uppercase tracking-widest text-[10px] text-slate-400 italic">
                    Liquidación Corporativa Andrea Transfers
                </span>
            </div>
            <div class="flex items-center gap-6">
                <span class="font-black uppercase tracking-[0.2em] text-[10px] text-slate-500">Acumulado Neto:</span>
                <span class="text-3xl font-black text-primary underline decoration-primary/30 decoration-4 underline-offset-8">
                    {{ number_format($totalComision, 2) }}€
                </span>
            </div>
        </div>
    </div>
</div>
@endsection