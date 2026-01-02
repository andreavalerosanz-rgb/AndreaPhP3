@extends('layouts.app')

@section('content')
<div class="space-y-10 pb-20">
    <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-10 lg:p-14 shadow-2xl text-white">
        <div class="absolute -right-10 -top-10 h-64 w-64 rounded-full bg-primary/10 blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 bg-primary text-white rounded-3xl flex items-center justify-center shadow-lg shadow-orange-500/30"><span class="text-3xl font-black italic">A</span></div>
                <div>
                    <h1 class="text-3xl lg:text-4xl py-2 font-black italic uppercase tracking-tight italic">Panel de <span class="text-primary text-white underline decoration-primary/40 decoration-4 underline-offset-8">Control Global</span></h1>
                    <p class="text-slate-400 font-medium mt-1 uppercase tracking-widest text-[10px]">Gestión de Reservas y Partners</p>
                </div>
            </div>
            <div class="badge bg-slate-800 border border-white/5 text-primary font-black px-6 py-4 rounded-xl text-[10px] uppercase tracking-widest shadow-inner">Sistema Online</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach([['Reservas', $stats['reservasTotales'], 'clipboard-check', 'orange'], ['Viajeros', $stats['viajerosTotales'], 'users', 'slate'], ['Partners', $stats['hotelesTotales'], 'building-2', 'orange'], ['Usuarios', $stats['usuariosTotales'], 'user-plus', 'slate']] as [$l, $v, $i, $c])
        <div class="bg-white border border-slate-100 p-8 rounded-[2.5rem] shadow-sm hover:shadow-xl transition-all group">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $l }}</p>
            <div class="flex justify-between items-end">
                <h3 class="text-3xl font-black text-slate-800">{{ $v }}</h3>
                <div class="w-12 h-12 bg-{{ $c === 'orange' ? 'orange' : 'slate' }}-50 rounded-xl flex items-center justify-center text-{{ $c === 'orange' ? 'primary' : 'slate-500' }} group-hover:bg-primary group-hover:text-white transition-all"><i data-lucide="{{ $i }}"></i></div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2 bg-white border border-slate-100 rounded-[3rem] p-10 shadow-xl relative overflow-hidden">
            <div class="flex items-center justify-between mb-10 border-b border-slate-50 pb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-50 text-primary rounded-xl flex items-center justify-center"><i data-lucide="bar-chart-3"></i></div>
                    <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Análisis de Operaciones por Zonas</h3>
                </div>
                <div class="badge bg-slate-50 text-slate-400 font-bold border-none px-4 py-3 rounded-lg text-[9px] uppercase">Gráfico Dinámico</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="space-y-4">
                    @foreach ($zonas as $z)
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="font-black text-slate-700 uppercase text-[10px] tracking-tighter">{{ $z->zona }}</span>
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-black text-primary">{{ $z->num_traslados }}</span>
                            <span class="text-[10px] text-slate-400 font-bold">{{ $z->porcentaje }}%</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="relative aspect-square max-w-[280px] mx-auto">
                    <canvas id="zonesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-slate-50 border border-slate-100 rounded-[3rem] p-10 shadow-inner">
            <h3 class="font-black text-slate-800 uppercase tracking-widest text-[10px] mb-8 border-b border-slate-200 pb-4 text-center">Atajos Operativos</h3>
            <div class="space-y-4">
                <a href="{{ route('admin.reservations.list') }}" class="flex items-center justify-between p-5 bg-white rounded-2xl shadow-sm hover:border-primary border border-transparent transition-all group">
                    <span class="font-black uppercase tracking-tight text-xs">Gestión Reservas</span>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-primary opacity-0 group-hover:opacity-100 transition-all"></i>
                </a>
                <a href="{{ route('admin.hoteles.index') }}" class="flex items-center justify-between p-5 bg-white rounded-2xl shadow-sm hover:border-primary border border-transparent transition-all group">
                    <span class="font-black uppercase tracking-tight text-xs">Partners Hoteleros</span>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-primary opacity-0 group-hover:opacity-100 transition-all"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('zonesChart');
        if(ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($zonas->pluck('zona')) !!},
                    datasets: [{
                        data: {!! json_encode($zonas->pluck('num_traslados')) !!},
                        backgroundColor: ['#f97316', '#0f172a', '#64748b', '#fb923c', '#334155', '#ea580c'],
                        borderWidth: 6,
                        borderColor: '#ffffff',
                        hoverOffset: 15
                    }]
                },
                options: { responsive: true, cutout: '75%', plugins: { legend: { display: false } } }
            });
        }
    });
</script>
@endsection