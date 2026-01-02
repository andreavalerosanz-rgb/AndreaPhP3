@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 space-y-10">
    <div class="card bg-white shadow-2xl border border-slate-100 rounded-[3rem] overflow-hidden">
        <div class="bg-primary p-12 text-white flex flex-col items-center text-center relative overflow-hidden">
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center mb-6 shadow-inner"><i data-lucide="bar-chart-big" class="w-10 h-10 text-white"></i></div>
            <h2 class="text-3xl font-black italic uppercase tracking-tight italic">Cierre <span class="underline decoration-white/30 decoration-4 underline-offset-8">Financiero</span></h2>
            <p class="text-orange-100 font-bold uppercase tracking-[0.3em] text-xs mt-4 italic">Auditoría Mensual: {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
        </div>

        <div class="p-10 lg:p-16">
            <table class="table w-full">
                <thead class="text-slate-400 font-black text-[10px] uppercase tracking-widest border-b border-slate-100 text-center">
                    <tr><th class="pb-6 text-left">Partner</th><th class="text-end pb-6">Facturado</th><th class="text-end pb-6">Comisión</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($commissionReport as $report)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-6 font-black text-slate-800 uppercase tracking-tighter text-sm">{{ $report['nombre_hotel'] }}</td>
                            <td class="text-end font-bold text-slate-500 italic">{{ number_format($report['total_ingresos'], 2) }}€</td>
                            <td class="text-end font-black text-primary text-xl tracking-tighter">{{ number_format($report['total_comision'], 2) }}€</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center py-20 text-slate-400 italic">Sin actividad financiera registrada.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-12 bg-slate-900 rounded-[2rem] p-10 flex flex-col md:flex-row justify-between items-center gap-6 shadow-xl">
                <div class="text-center md:text-left text-slate-500">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] mb-1">Cálculo Neto Auditoría</p>
                    <p class="text-xs text-primary font-bold italic tracking-tight italic">Andrea Transfers Software SaaS</p>
                </div>
                <div class="text-4xl font-black text-white tracking-tighter">{{ number_format($commissionReport->sum('total_comision'), 2) }}€</div>
            </div>
        </div>
    </div>
</div>
@endsection