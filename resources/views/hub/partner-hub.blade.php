@extends('layouts.app')

@section('content')
    <div class="space-y-10 pb-20">

        <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-10 lg:p-14 shadow-2xl text-white">
            <div class="absolute -left-10 -top-10 w-40 h-40 bg-primary/20 rounded-full blur-3xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h2 class="text-3xl font-black italic uppercase tracking-tight">Gestión de <span
                            class="text-primary text-white underline decoration-primary/40 decoration-4 underline-offset-4">Partners</span>
                    </h2>
                    <p class="text-slate-500 font-bold uppercase text-[10px] tracking-widest mt-2 italic">Control de accesos
                        y registro corporativo</p>
                </div>

                <div class="flex gap-2 p-1.5 bg-slate-800 rounded-2xl border border-white/5 shadow-inner">
                    <button onclick="verSeccion('directorio')" id="btn-directorio"
                        class="tab-btn active px-8 py-2.5 rounded-xl font-black uppercase text-[10px] tracking-widest transition-all">Directorio</button>
                    <button onclick="verSeccion('registro')" id="btn-registro"
                        class="tab-btn px-8 py-2.5 rounded-xl font-black uppercase text-[10px] tracking-widest transition-all">Registrar
                        Partner</button>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto space-y-4">
            @if(session('status') || session('success'))
                <div
                    class="alert bg-green-50 border-green-200 text-green-700 rounded-3xl p-6 shadow-sm flex items-center gap-4">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                    <span class="font-bold">{{ session('status') ?? session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div
                    class="alert bg-red-50 border-red-200 text-red-700 rounded-3xl p-6 shadow-sm flex flex-col items-start gap-2">
                    <div class="flex items-center gap-2 font-black uppercase text-[10px] tracking-widest text-red-800 mb-1">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        <span>No se pudo completar la acción:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm font-medium ml-2">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div id="cont-directorio" class="animate-in fade-in duration-500">
            <div class="bg-white border border-slate-100 rounded-[3rem] overflow-hidden shadow-xl">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <h4 class="font-black uppercase text-[10px] tracking-[0.2em] text-slate-400">Lista de Partners Activos
                    </h4>
                    <span
                        class="badge bg-slate-900 text-white border-none font-bold px-4 py-3 rounded-lg">{{ $hoteles->count() }}
                        Registrados</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead class="bg-slate-50 text-slate-500 font-black text-[10px] uppercase">
                            <tr>
                                <th class="p-6">Nombre del Hotel</th>
                                <th>Email de Acceso</th>
                                <th class="text-center">Comisión %</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($hoteles as $hotel)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="p-6 font-black text-slate-800 uppercase tracking-tighter">{{ $hotel->nombre }}
                                    </td>
                                    <td class="font-medium text-slate-500 italic">{{ $hotel->email_hotel }}</td>
                                    <td class="text-center"><span
                                            class="badge bg-orange-50 text-primary border-none font-bold px-4">{{ $hotel->Comision }}%</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $hotel->activo ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} border-none font-black text-[9px] uppercase px-4 py-2 rounded-xl">
                                            {{ $hotel->activo ? 'Operativo' : 'Inhabilitado' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <form method="POST"
                                            action="{{ route($hotel->activo ? 'admin.hoteles.disable' : 'admin.hoteles.enable', $hotel->id_hotel) }}">
                                            @csrf @method('PUT')
                                            <button
                                                class="btn btn-sm {{ $hotel->activo ? 'btn-ghost text-red-400 hover:bg-red-600' : 'btn-primary shadow-lg shadow-orange-500/10' }} rounded-xl font-black text-[9px] uppercase tracking-widest">
                                                {{ $hotel->activo ? 'Inhabilitar' : 'Activar' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-20 text-slate-400 font-bold italic">No hay partners
                                        registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="cont-registro" class="hidden animate-in fade-in duration-500">
            <div
                class="max-w-4xl mx-auto bg-white border border-slate-100 p-12 rounded-[3.5rem] shadow-xl relative overflow-hidden">
                <h3 class="text-center font-black italic uppercase text-slate-800 text-xl mb-12">Dar de Alta Nuevo <span
                        class="text-primary underline decoration-primary/20 decoration-4">Partner</span></h3>

                <form method="POST" action="{{ route('admin.hoteles.store') }}"
                    class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @csrf
                    <div class="form-control">
                        <label class="label font-bold text-[10px] text-slate-400 uppercase tracking-widest">Nombre
                            Comercial</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}"
                            class="input input-bordered bg-slate-50 h-14 rounded-2xl" required />
                    </div>
                    <div class="form-control">
                        <label class="label font-bold text-[10px] text-slate-400 uppercase tracking-widest">Email
                            Corporativo</label>
                        <input type="email" name="email_hotel" value="{{ old('email_hotel') }}"
                            class="input input-bordered bg-slate-50 h-14 rounded-2xl" required />
                    </div>
                    <div class="form-control">
                        <label class="label font-bold text-[10px] text-slate-400 uppercase tracking-widest">Comisión
                            Asignada (%)</label>
                        <input type="number" name="Comision" value="{{ old('Comision', 15) }}"
                            class="input input-bordered bg-slate-50 h-14 rounded-2xl" required />
                    </div>
                    <div class="form-control">
                        <label class="label font-bold text-[10px] text-slate-400 uppercase tracking-widest">Zona
                            Operativa</label>
                        <select name="id_zona" class="select select-bordered bg-slate-50 h-14 rounded-2xl" required>
                            <option value="">Seleccionar zona...</option>
                            @foreach($zonas as $z)
                                <option value="{{ $z->id_zona }}" {{ old('id_zona') == $z->id_zona ? 'selected' : '' }}>
                                    {{ $z->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label font-bold text-[10px] text-slate-400 uppercase tracking-widest">Contraseña
                            Temporal</label>
                        <input type="password" name="password" class="input input-bordered bg-slate-50 h-14 rounded-2xl"
                            required />
                    </div>
                    <div class="form-control">
                        <label class="label font-bold text-[10px] text-slate-400 uppercase tracking-widest">Confirmar
                            Clave</label>
                        <input type="password" name="password_confirmation"
                            class="input input-bordered bg-slate-50 h-14 rounded-2xl" required />
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <button type="submit"
                            class="btn bg-primary hover:bg-primary-focus border-none text-white w-full h-14 rounded-2xl font-black uppercase text-xs shadow-xl shadow-orange-500/30 transition-all">
                            Crear Partner Corporativo <i data-lucide="user-plus" class="w-4 h-4 ml-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <style>
        .tab-btn.active {
            background: #f97316;
            color: white;
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.2);
        }

        .tab-btn:not(.active) {
            color: #94a3b8;
        }

        .tab-btn:hover:not(.active) {
            background: rgba(255, 255, 255, 0.05);
            color: #f97316;
        }
    </style>

    <script>
        function verSeccion(seccion) {
            // Ocultar contenidos
            document.getElementById('cont-directorio').classList.add('hidden');
            document.getElementById('cont-registro').classList.add('hidden');

            // Quitar activos
            document.getElementById('btn-directorio').classList.remove('active');
            document.getElementById('btn-registro').classList.remove('active');

            // Mostrar seleccionado
            document.getElementById('cont-' + seccion).classList.remove('hidden');
            document.getElementById('btn-' + seccion).classList.add('active');
        }
    </script>
@endsection