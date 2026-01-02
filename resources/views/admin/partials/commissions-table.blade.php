<div class="bg-white border border-slate-100 rounded-[3rem] overflow-hidden shadow-xl">
    <div class="bg-slate-900 p-10 text-white flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h3 class="font-black italic uppercase tracking-tight text-xl">Balance <span class="text-primary">Económico</span></h3>
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-2 italic">Reporte de Comisiones por Partner</p>
        </div>
        <div class="badge bg-primary text-white border-none font-black px-8 py-6 rounded-[1.5rem] text-2xl shadow-lg shadow-orange-500/20">
            Total Andrea: {{ number_format($commissionReport->sum('total_comision'), 2) }}€
        </div>
    </div>

    <div class="p-10">
        <form method="GET" action="{{ route('admin.hoteles.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-12 bg-slate-50 p-6 rounded-[2rem] border border-slate-100">
            <input type="hidden" name="tab" value="comisiones">
            <select name="month" class="select select-bordered rounded-xl font-bold text-xs">
                @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}" @selected($month == $i)>{{ ucfirst(\Carbon\Carbon::createFromDate(null, $i, 1)->locale('es')->monthName) }}</option> @endfor
            </select>
            <select name="year" class="select select-bordered rounded-xl font-bold text-xs">
                @for ($i = now()->year; $i >= now()->year - 2; $i--) <option value="{{ $i }}" @selected($year == $i)>Año {{ $i }}</option> @endfor
            </select>
            <button class="btn bg-primary text-white border-none rounded-xl font-black uppercase text-xs md:col-span-2">Actualizar Análisis</button>
        </form>

        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-slate-50 text-slate-500 font-black text-[10px] uppercase">
                    <tr>
                        <th class="p-6">Partner Comercial</th>
                        <th class="text-center">Ventas</th>
                        <th class="text-end">Bruto Generado</th>
                        <th class="text-end">Comisión Andrea</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @foreach ($commissionReport as $report)
                    <tr class="hover:bg-slate-50/50 transition-colors font-medium">
                        <td class="p-6 font-black text-slate-800 uppercase tracking-tighter">{{ $report['nombre_hotel'] }}</td>
                        <td class="text-center font-bold text-slate-600">{{ $report['total_reservas'] }}</td>
                        <td class="text-end font-medium text-slate-500">{{ number_format($report['total_ingresos'], 2) }}€</td>
                        <td class="text-end font-black text-primary text-lg">{{ number_format($report['total_comision'], 2) }}€</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>