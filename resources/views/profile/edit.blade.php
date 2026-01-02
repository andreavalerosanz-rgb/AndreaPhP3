@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 space-y-10">
    
    <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-8 lg:p-12 shadow-2xl">
        <div class="absolute -right-10 -top-10 h-64 w-64 rounded-full bg-primary/10 blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="avatar placeholder">
                    <div class="bg-primary text-white rounded-3xl w-20 h-20 shadow-lg shadow-orange-500/30">
                        <span class="text-3xl font-black">{{ substr($user->nombre, 0, 1) }}</span>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl lg:text-4xl font-black text-white italic">
                        Mi <span class="text-primary text-white underline decoration-primary/30 decoration-4 underline-offset-8">Perfil</span>
                    </h1>
                    <p class="text-slate-400 font-medium mt-2">Mantén tus datos de contacto siempre actualizados.</p>
                </div>
            </div>
            <div class="badge bg-slate-800 border-none text-primary font-black p-4 rounded-xl tracking-widest text-[10px] uppercase">
                Cuenta {{ $guard ?? 'Usuario' }}
            </div>
        </div>
    </div>

    <div class="card bg-white shadow-xl border border-slate-100 rounded-[2.5rem] overflow-hidden">
        <div class="card-body p-8 lg:p-12">
            
            @if(session('status'))
                <div class="alert bg-orange-50 border-orange-200 text-primary rounded-2xl mb-8 font-bold">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-12">
                @csrf
                @method('PUT')

                <section class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
                        <div class="w-10 h-10 bg-orange-50 text-primary rounded-xl flex items-center justify-center">
                            <i data-lucide="user"></i>
                        </div>
                        <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Información Personal</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label font-bold text-slate-500 text-[10px] uppercase">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $user->nombre ?? '') }}" class="input input-bordered bg-slate-50 focus:border-primary" required />
                            @error('nombre') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label font-bold text-slate-500 text-[10px] uppercase">Correo Electrónico</label>
                            <input type="email" name="email" value="{{ old('email', $user->email_admin ?? $user->email_hotel ?? $user->email_viajero ?? '') }}" class="input input-bordered bg-slate-50" required />
                            @error('email') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        @if(($guard ?? '') === 'web')
                            <div class="form-control">
                                <label class="label font-bold text-slate-500 text-[10px] uppercase">Primer Apellido</label>
                                <input type="text" name="apellido1" value="{{ old('apellido1', $user->apellido1 ?? '') }}" class="input input-bordered bg-slate-50" required />
                            </div>
                            <div class="form-control">
                                <label class="label font-bold text-slate-500 text-[10px] uppercase">Segundo Apellido</label>
                                <input type="text" name="apellido2" value="{{ old('apellido2', $user->apellido2 ?? '') }}" class="input input-bordered bg-slate-50" />
                            </div>
                        @endif
                    </div>
                </section>

                @if(($guard ?? '') === 'web')
                <section class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
                        <div class="w-10 h-10 bg-orange-50 text-primary rounded-xl flex items-center justify-center">
                            <i data-lucide="map-pin"></i>
                        </div>
                        <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Ubicación y Facturación</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="form-control md:col-span-3">
                            <label class="label font-bold text-slate-500 text-[10px] uppercase">Dirección Completa</label>
                            <input type="text" name="direccion" value="{{ old('direccion', $user->direccion ?? '') }}" class="input input-bordered bg-slate-50" />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-500 text-[10px] uppercase">Código Postal</label>
                            <input type="text" name="codigoPostal" value="{{ old('codigoPostal', $user->codigoPostal ?? '') }}" class="input input-bordered bg-slate-50" />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-500 text-[10px] uppercase">Ciudad</label>
                            <input type="text" name="ciudad" value="{{ old('ciudad', $user->ciudad ?? '') }}" class="input input-bordered bg-slate-50" />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-500 text-[10px] uppercase">País</label>
                            <input type="text" name="pais" value="{{ old('pais', $user->pais ?? '') }}" class="input input-bordered bg-slate-50" />
                        </div>
                    </div>
                </section>
                @endif

                <section class="space-y-6 bg-slate-50 p-8 rounded-[2rem]">
                    <div class="flex items-center gap-3 border-b border-slate-200 pb-4">
                        <div class="w-10 h-10 bg-white text-primary rounded-xl flex items-center justify-center shadow-sm">
                            <i data-lucide="key-round"></i>
                        </div>
                        <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Cambiar Contraseña</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control">
                            <label class="label font-bold text-slate-500 text-[10px] uppercase">Nueva Contraseña</label>
                            <input type="password" name="password" class="input input-bordered bg-white" placeholder="Dejar en blanco para mantener" />
                            @error('password') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-500 text-[10px] uppercase">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="input input-bordered bg-white" placeholder="Repite la contraseña" />
                        </div>
                    </div>
                </section>

                <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-6">
                    <a href="{{ route('dashboard') }}" class="btn btn-ghost text-slate-400 gap-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> Cancelar y Volver
                    </a>
                    <button type="submit" class="btn bg-[#f97316] hover:bg-[#ea580c] border-none text-white px-12 h-14 shadow-xl shadow-orange-500/30 rounded-2xl font-black uppercase tracking-widest text-xs">
                        Guardar Cambios <i data-lucide="save" class="w-5 h-5 ml-2"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection