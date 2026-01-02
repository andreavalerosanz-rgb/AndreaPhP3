@extends('layouts.app')

@section('content')
    <div class="space-y-10 pb-20">

        <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-10 lg:p-14 shadow-2xl text-white">
            <div class="absolute -right-10 -top-10 h-64 w-64 rounded-full bg-primary/10 blur-3xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-6">
                    <div
                        class="w-16 h-16 bg-primary/20 rounded-2xl flex items-center justify-center text-primary shadow-lg">
                        <i data-lucide="truck" class="w-10 h-10"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl lg:text-4xl font-black italic uppercase tracking-tight">Gestión de <span
                                class="text-primary text-white underline decoration-primary/40 decoration-4 underline-offset-8">Flota</span>
                        </h1>
                        <p class="text-slate-400 font-medium mt-1 uppercase tracking-widest text-[10px]">Control de
                            inventario y conductores activos</p>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <a href="{{ route('admin.fleet.create') }}"
                        class="btn bg-primary hover:bg-primary-focus border-none text-white px-8 h-14 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-orange-500/20">
                        + Añadir Vehículo
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="max-w-4xl mx-auto animate-in fade-in slide-in-from-top-4 duration-300">
                <div
                    class="alert bg-green-50 border-green-200 text-green-700 rounded-3xl p-6 shadow-sm flex items-center gap-4">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="bg-white border border-slate-100 rounded-[3rem] overflow-hidden shadow-xl">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                <h4 class="font-black uppercase text-[10px] tracking-[0.2em] text-slate-400 italic">Unidades en Servicio
                </h4>
                <span class="badge bg-slate-900 text-white border-none font-bold px-4 py-3 rounded-lg">{{ $units->count() }}
                    Registrados</span>
            </div>

            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 font-black text-[10px] uppercase">
                        <tr>
                            <th class="p-6 text-center">ID</th>
                            <th>Modelo / Descripción</th>
                            <th>Email Conductor</th>
                            <th class="text-center">Matrícula / Clave</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Operaciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse($units as $u)
                            <tr
                                class="hover:bg-slate-50/50 transition-all border-l-4 {{ $u->activo ? 'border-l-primary' : 'border-l-slate-200' }}">
                                <td class="p-6 text-center">
                                    <span class="font-mono font-black text-slate-400">#{{ $u->id_vehiculo }}</span>
                                </td>
                                <td>
                                    <span
                                        class="font-black text-slate-800 uppercase tracking-tighter text-lg">{{ $u->label }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2 font-medium text-slate-500 italic">
                                        <i data-lucide="mail" class="w-4 h-4 text-slate-300"></i>
                                        {{ $u->email_conductor }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge bg-slate-100 border-slate-200 text-slate-600 font-mono font-bold px-4 py-3 rounded-lg">
                                        {{ $u->password }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge {{ $u->activo ? 'bg-orange-50 text-primary' : 'bg-slate-100 text-slate-400' }} border-none font-black text-[9px] uppercase px-4 py-2 rounded-xl tracking-widest">
                                        {{ $u->activo ? 'Activo' : 'Inhabilitado' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex justify-center gap-2">
                                        {{-- Editar --}}
                                        <a href="{{ route('admin.fleet.edit', $u->id_vehiculo) }}"
                                            class="btn btn-square btn-sm bg-slate-100 hover:bg-primary hover:text-white border-none transition-all rounded-xl shadow-sm">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>

                                        {{-- Toggle Estado --}}
                                        <form method="POST"
                                            action="{{ route($u->activo ? 'admin.fleet.deactivate' : 'admin.fleet.activate', $u->id_vehiculo) }}"
                                            onsubmit="return confirm('¿Actualizar estado de esta unidad?');">
                                            @csrf @method('PUT')
                                            <button type="submit"
                                                class="btn btn-square btn-sm {{ $u->activo ? 'bg-red-50 text-red-400 hover:bg-red-500' : 'bg-green-50 text-green-500 hover:bg-green-500' }} hover:text-white border-none transition-all rounded-xl shadow-sm">
                                                <i data-lucide="{{ $u->activo ? 'lock' : 'unlock' }}" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-24">
                                    <div class="flex flex-col items-center gap-4 opacity-20">
                                        <i data-lucide="car-front" class="w-20 h-20"></i>
                                        <span class="text-xl font-black uppercase italic">Sin unidades registradas</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-center gap-8">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-2 text-[10px] font-black uppercase text-slate-400 hover:text-primary transition-colors">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Panel de Control
            </a>
            <a href="{{ route('admin.reservations.list') }}"
                class="flex items-center gap-2 text-[10px] font-black uppercase text-slate-400 hover:text-primary transition-colors">
                <i data-lucide="clipboard-list" class="w-4 h-4"></i> Ver Reservas
            </a>
        </div>
    </div>
@endsection