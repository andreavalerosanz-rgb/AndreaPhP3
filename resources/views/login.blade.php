@extends('layouts.app')

@section('content')
<div class="flex flex-col justify-center items-center min-h-[70vh] py-10">
    <div class="card w-full max-w-md bg-white shadow-2xl border border-slate-100 overflow-hidden">
        <div class="h-2 bg-primary w-full"></div>
        
        <div class="card-body p-10">
            <div class="flex flex-col items-center mb-8">
                <div class="w-16 h-16 bg-orange-50 text-primary rounded-2xl flex items-center justify-center mb-4 shadow-inner">
                    <i data-lucide="lock" class="w-8 h-8"></i>
                </div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">Bienvenido</h2>
                <p class="text-slate-500 text-sm font-medium mt-1">Gestión profesional de traslados</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-error bg-red-50 border-red-200 text-red-700 text-sm py-3 rounded-xl flex gap-3">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-slate-600">Correo Electrónico</span></label>
                    <div class="relative">
                        <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                        <input type="email" name="email" placeholder="email@ejemplo.com" 
                               class="input input-bordered w-full pl-12 bg-slate-50 focus:border-primary focus:outline-none transition-all" required />
                    </div>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-bold text-slate-600">Contraseña</span>
                    </label>
                    <div class="relative">
                        <i data-lucide="key-round" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                        <input type="password" name="password" placeholder="••••••••" 
                               class="input input-bordered w-full pl-12 bg-slate-50 focus:border-primary focus:outline-none transition-all" required />
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-full text-white font-bold h-14 shadow-lg shadow-orange-500/30">
                    Acceder al Panel <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                </button>

                <div class="divider text-slate-300 text-[10px] font-black uppercase tracking-widest">¿Nuevo usuario?</div>

                <div class="text-center">
                    <a href="{{ route('register') }}" class="text-primary font-bold hover:underline decoration-2 underline-offset-4">
                        Crea una cuenta ahora
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection