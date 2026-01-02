@extends('layouts.app')

@section('content')
<div class="space-y-10 pb-20">
    
    <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-8 lg:p-12 shadow-2xl">
        <div class="absolute -right-10 -top-10 h-64 w-64 rounded-full bg-primary/10 blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6 text-white">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 bg-primary/20 rounded-2xl flex items-center justify-center text-primary shadow-lg">
                    <i data-lucide="clipboard-list" class="w-10 h-10"></i>
                </div>
                <div>
                    <h1 class="text-3xl lg:text-4xl font-black italic">Gestión de <span class="text-primary">Servicios</span></h1>
                    <p class="text-slate-400 font-medium mt-1 uppercase tracking-widest text-[10px]">Registro de Operaciones</p>
                </div>
            </div>
            <div class="bg-slate-800/50 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/5">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total</span>
                <div class="text-2xl font-black text-primary">{{ $reservas->total() }}</div>
            </div>
        </div>
    </div>

    <div class="card bg-white border border-slate-100 shadow-xl rounded-[2.5rem] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table table-lg w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-slate-500 font-black text-[10px] uppercase tracking-widest">
                        <th class="py-6 px-8 text-center">Referencia</th>
                        <th>Trayecto / Destino</th>
                        <th>Cronograma</th>
                        <th>Flota</th>
                        <th class="text-center">Pax</th>
                        <th class="text-end">Tarifa</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($reservas as $reserva)
                    @php
                        $isModifiable = $reserva->isModifiableBy($role);
                        $partnerName = $reserva->partner->nombre ?? 'N/A';
                        
                        $statusStyles = [
                            'confirmada' => 'bg-orange-50 text-primary',
                            'anulada'    => 'bg-red-50 text-red-600',
                            'finalizada' => 'bg-slate-100 text-slate-500'
                        ];
                        $currentStyle = $statusStyles[$reserva->estado] ?? 'bg-slate-100';
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-6 px-8 text-center">
                            <span class="font-mono font-black text-slate-800 tracking-tighter text-sm">{{ $reserva->localizador }}</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-slate-700">{{ $partnerName }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col gap-1">
                                @if(in_array($reserva->id_tipo_reserva, [1, 3]))
                                    <div class="flex items-center gap-2 text-[11px]">
                                        <span class="font-black text-slate-600 italic">IDA:</span>
                                        <span class="text-slate-400 font-medium">{{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') }} - {{ $reserva->hora_entrada }}</span>
                                    </div>
                                @endif
                                @if(in_array($reserva->id_tipo_reserva, [2, 3]))
                                    <div class="flex items-center gap-2 text-[11px]">
                                        <span class="font-black text-slate-600 italic">VTA:</span>
                                        <span class="text-slate-400 font-medium">{{ \Carbon\Carbon::parse($reserva->fecha_vuelo_salida)->format('d/m/Y') }} - {{ $reserva->hora_vuelo_salida }}</span>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="text-xs font-bold text-slate-500">{{ $reserva->fleetUnit->label ?? '-' }}</span>
                        </td>
                        <td class="text-center">
                            <span class="font-black text-slate-800">{{ $reserva->num_viajeros }}</span>
                        </td>
                        <td class="text-end font-black text-primary">
                            {{ number_format($reserva->precio_total, 2) }}€
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $currentStyle }} border-none font-black text-[9px] uppercase px-4 py-3 rounded-xl tracking-widest">
                                {{ $reserva->estado }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="flex justify-center gap-2">
                                @if($isModifiable && $reserva->estado === 'confirmada')
                                    <a href="{{ route('mis_reservas.edit', $reserva->id_reserva) }}" class="btn btn-square btn-ghost btn-sm hover:text-primary">
                                        <i data-lucide="pencil" class="w-5 h-5"></i>
                                    </a>
                                    <form action="{{ route('mis_reservas.destroy', $reserva->id_reserva) }}" method="POST" onsubmit="return confirm('¿Confirmar anulación del servicio?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-square btn-ghost btn-sm hover:text-red-600">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </form>
                                @else
                                    <i data-lucide="lock" class="w-4 h-4 text-slate-200"></i>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($reservas->hasPages())
            <div class="p-8 bg-slate-50/50 flex justify-center">
                {{ $reservas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection