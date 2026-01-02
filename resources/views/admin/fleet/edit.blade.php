@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-10 pb-20">

        <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-10 shadow-2xl text-white">
            <div class="absolute -right-10 -top-10 h-40 w-40 bg-primary/20 rounded-full blur-3xl"></div>
            <div class="relative z-10 flex justify-between items-center">
                <div class="flex items-center gap-6">
                    <div
                        class="w-16 h-16 bg-primary/20 rounded-2xl flex items-center justify-center text-primary shadow-lg">
                        <i data-lucide="pencil-line" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black italic uppercase tracking-tight">Editar <span
                                class="text-primary underline underline-offset-8">Vehículo</span></h1>
                        <p class="text-slate-500 font-bold uppercase text-[10px] mt-2 tracking-widest italic">ID Registro:
                            #{{ $unit->id_vehiculo }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.fleet.index') }}"
                    class="btn btn-ghost text-slate-400 hover:text-white font-black uppercase text-[10px] tracking-widest">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Volver
                </a>
            </div>
        </div>

        <div class="bg-white border border-slate-100 rounded-[3rem] p-10 lg:p-14 shadow-xl">

            {{-- BLOQUE DE ERRORES MEJORADO --}}
            @if ($errors->any())
                <div
                    class="alert bg-red-50 border-red-200 text-red-700 rounded-3xl p-6 mb-10 flex flex-col items-start animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="flex items-center gap-2 font-black uppercase text-[10px] tracking-widest text-red-800 mb-2">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i> Errores de validación detectados
                    </div>
                    <ul class="list-disc list-inside text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.fleet.update', $unit->id_vehiculo) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="form-control md:col-span-2">
                        <label
                            class="label font-black text-[10px] text-slate-400 uppercase tracking-widest mb-1">Descripción
                            del Vehículo</label>
                        <input type="text" name="label" value="{{ old('label', $unit->label) }}"
                            class="input input-bordered bg-slate-50 h-14 rounded-2xl focus:border-primary transition-all font-bold"
                            required />
                    </div>

                    <div class="form-control">
                        <label class="label font-black text-[10px] text-slate-400 uppercase tracking-widest mb-1">Email del
                            Conductor</label>
                        <input type="email" name="driver_identity"
                            value="{{ old('driver_identity', $unit->email_conductor) }}"
                            class="input input-bordered bg-slate-50 h-14 rounded-2xl focus:border-primary font-bold"
                            required />
                    </div>

                    <div class="form-control">
                        <label class="label font-black text-[10px] text-slate-400 uppercase tracking-widest mb-1">Matrícula
                            (Clave)</label>
                        <input type="text" name="access_key" value="{{ old('access_key', $unit->password) }}"
                            class="input input-bordered bg-slate-50 h-14 rounded-2xl focus:border-primary font-mono font-bold"
                            required />
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="label font-black text-[10px] text-slate-400 uppercase tracking-widest mb-1">Precio
                            Base / Tarifa (€)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-5 flex items-center text-slate-400 font-bold">€</span>
                            <input type="number" step="0.01" name="rate" value="{{ old('rate', $unit->rate) }}"
                                placeholder="0.00"
                                class="input input-bordered bg-slate-50 h-14 rounded-2xl focus:border-primary font-bold w-full pl-10"
                                required />
                        </div>
                        <p class="text-[9px] text-slate-400 mt-2 italic">Este campo es obligatorio para el cálculo de
                            presupuestos.</p>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit"
                        class="btn bg-primary hover:bg-primary-focus border-none text-white w-full h-14 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-orange-500/20 transition-all">
                        Guardar Cambios <i data-lucide="save" class="w-4 h-4 ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection