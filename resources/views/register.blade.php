@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <div class="card bg-white shadow-2xl border border-slate-100 overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12">
            
            <div class="lg:col-span-4 bg-slate-900 p-10 text-white flex flex-col justify-center hidden lg:flex">
                <div class="w-14 h-14 bg-primary/20 text-primary rounded-2xl flex items-center justify-center mb-8">
                    <i data-lucide="user-plus" class="w-8 h-8"></i>
                </div>
                <h2 class="text-3xl font-black mb-4 leading-tight">Tu viaje <span class="text-primary italic">empieza</span> aquí.</h2>
                <p class="text-slate-400 text-sm leading-relaxed">Regístrate para gestionar tus traslados y consultar tus localizadores al instante.</p>
            </div>

            <div class="lg:col-span-8 p-8 lg:p-12">
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-slate-800">Crear cuenta de Viajero</h2>
                    <p class="text-slate-500 text-sm">Por favor, rellena todos los campos obligatorios.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-error bg-red-50 border-red-200 text-red-700 text-sm py-4 rounded-xl mb-6 flex flex-col items-start gap-1 shadow-sm">
                        <div class="flex items-center gap-2 font-bold">
                            <i data-lucide="alert-circle" class="w-5 h-5"></i>
                            <span>Se han detectado errores:</span>
                        </div>
                        <ul class="list-disc list-inside ml-7">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="role" value="viajero">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label font-bold text-slate-700 text-xs uppercase tracking-wider">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" class="input input-bordered bg-slate-50 focus:border-primary" required />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-700 text-xs uppercase tracking-wider">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="input input-bordered bg-slate-50 focus:border-primary" required />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label font-bold text-slate-700 text-xs uppercase tracking-wider">Primer Apellido</label>
                            <input type="text" name="apellido1" value="{{ old('apellido1') }}" class="input input-bordered bg-slate-50" required />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-700 text-xs uppercase tracking-wider">Segundo Apellido</label>
                            <input type="text" name="apellido2" value="{{ old('apellido2') }}" class="input input-bordered bg-slate-50" required />
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label font-bold text-slate-700 text-xs uppercase tracking-wider">Dirección</label>
                        <input type="text" name="direccion" value="{{ old('direccion') }}" class="input input-bordered bg-slate-50" required />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-control">
                            <label class="label font-bold text-slate-700 text-xs uppercase tracking-wider">Cód. Postal</label>
                            <input type="text" name="codigoPostal" value="{{ old('codigoPostal') }}" class="input input-bordered bg-slate-50" required />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-700 text-xs uppercase tracking-wider">Ciudad</label>
                            <input type="text" name="ciudad" value="{{ old('ciudad') }}" class="input input-bordered bg-slate-50" required />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-700 text-xs uppercase tracking-wider">País</label>
                            <input type="text" name="pais" value="{{ old('pais') }}" class="input input-bordered bg-slate-50" required />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                        <div class="form-control">
                            <label class="label font-bold text-slate-700 text-xs uppercase tracking-wider">Contraseña</label>
                            <input type="password" name="password" class="input input-bordered bg-slate-50" required />
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-slate-700 text-xs uppercase tracking-wider">Confirmar</label>
                            <input type="password" name="password_confirmation" class="input input-bordered bg-slate-50" required />
                        </div>
                    </div>

                    <button type="submit" class="btn btn bg-[#f97316] hover:bg-[#ea580c] w-full text-white font-black uppercase tracking-[0.2em] h-14  border-none">
                        Finalizar Registro <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection