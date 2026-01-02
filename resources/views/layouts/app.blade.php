<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Isla Transfers | Panel de Gestión</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                primary: '#f97316', 
                'primary-focus': '#ea580c', 
                secondary: '#1e293b',
              }
            }
          }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; }
        .menu li > a.active { 
            background-color: #f97316 !important; 
            color: white !important; 
            font-weight: 800;
            box-shadow: 0 4px 15px rgba(249, 115, 22, 0.25);
        }
        .menu li > a.active i, .menu li > a.active svg { color: white !important; }
        .menu li > a:hover:not(.active) { background-color: rgba(249, 115, 22, 0.1); color: #f97316; }
    </style>
</head>
<body class="bg-slate-50 antialiased">

@php
    $isAdmin     = auth('admin')->check();
    $isCorporate = auth('corporate')->check();
    $isTraveler  = auth('web')->check();
    
    $user        = $isAdmin ? auth('admin')->user() : ($isCorporate ? auth('corporate')->user() : auth('web')->user());
    $userName    = $user->nombre ?? 'Usuario';
    
    $homeRoute = $isAdmin ? route('admin.dashboard') : ($isCorporate ? route('corporate.dashboard') : route('user.dashboard'));
    
    $isAnyAuth = $isAdmin || $isCorporate || $isTraveler;
@endphp

<div class="drawer lg:drawer-open">
    <input id="main-drawer" type="checkbox" class="drawer-toggle" />
    
    <div class="drawer-content flex flex-col min-h-screen">
        
        <header class="navbar bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 px-6 h-20">
            <div class="flex-none lg:hidden">
                <label for="main-drawer" class="btn btn-square btn-ghost"><i data-lucide="menu"></i></label>
            </div>
            
            <div class="flex-1 lg:hidden">
                <span class="text-xl font-extrabold text-primary ml-2 italic">ISLA <span class="text-slate-800">P3</span></span>
            </div>

            <div class="flex-none ml-auto flex items-center gap-4">
                @if($isAnyAuth)
                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-4 hover:bg-slate-50 p-2 rounded-2xl transition-all duration-300">
                        <div class="hidden md:block text-right">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 group-hover:text-primary transition-colors">Sesión Activa</p>
                            <p class="text-sm font-extrabold text-slate-700">{{ $userName }}</p>
                        </div>
                        <div class="avatar placeholder">
                            <div class="bg-orange-100 text-primary rounded-full w-10 ring-2 ring-transparent group-hover:ring-primary/30 transition-all shadow-sm">
                                <span class="text-xs font-bold">{{ substr($userName, 0, 1) }}</span>
                            </div>
                        </div>
                    </a>
                @endif
            </div>
        </header>

        <main class="flex-1 p-6 lg:p-12 max-w-[1600px] w-full mx-auto">
            @yield('content')
        </main>
    </div> 

    <aside class="drawer-side z-40">
        <label for="main-drawer" class="drawer-overlay"></label>
        <div class="menu p-6 w-72 min-h-full bg-slate-900 text-slate-300 shadow-2xl">
            
            <div class="flex items-center gap-3 px-2 mb-12">
                <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-500/40">
                    <i data-lucide="send" class="w-7 h-7"></i>
                </div>
                <div class="leading-none">
                    <h1 class="text-white font-extrabold text-xl tracking-tighter italic">ISLA <span class="text-primary">P3</span></h1>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Transfers v2.0</p>
                </div>
            </div>

            <nav class="flex flex-col gap-1">
                <p class="text-[10px] font-black text-slate-600 uppercase px-4 mb-4 tracking-widest">Navegación</p>
                
                @if($isAnyAuth)
                    {{-- LINKS PARA USUARIOS LOGUEADOS --}}
                    <li><a href="{{ $homeRoute }}" class="flex gap-4 py-3 {{ (request()->routeIs('*.dashboard')) ? 'active' : '' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i> 
                        {{ $isAdmin ? 'Dashboard Admin' : 'Inicio' }}
                    </a></li>

                    <li><a href="{{ route('calendar.index') }}" class="flex gap-4 py-3 {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                        <i data-lucide="calendar" class="w-5 h-5"></i> Calendario
                    </a></li>

                    @if ($isAdmin)
                        {{-- Actualizado a admin.fleet.index --}}
                        <li><a href="{{ route('admin.fleet.index') }}" class="flex gap-4 py-3 {{ request()->routeIs('admin.fleet.*') ? 'active' : '' }}">
                            <i data-lucide="car" class="w-5 h-5"></i> Gestionar Flota
                        </a></li>
                        <li><a href="{{ route('admin.reservations.list') }}" class="flex gap-4 py-3 {{ request()->routeIs('admin.reservations.list') ? 'active' : '' }}">
                            <i data-lucide="clipboard-list" class="w-5 h-5"></i> Listado Traslados
                        </a></li>
                        <li><a href="{{ route('admin.hoteles.index') }}" class="flex gap-4 py-3 {{ request()->routeIs('admin.hoteles.*') ? 'active' : '' }}">
                            <i data-lucide="building-2" class="w-5 h-5"></i> Gestión Hoteles
                        </a></li>

                    @elseif ($isCorporate)
                        <li><a href="{{ route('transfer.select-type') }}" class="flex gap-4 py-3 {{ request()->routeIs('transfer.*') ? 'active' : '' }}">
                            <i data-lucide="plus-circle" class="w-5 h-5"></i> Nueva Reserva
                        </a></li>
                        <li><a href="{{ route('mis_reservas') }}" class="flex gap-4 py-3 {{ request()->is('my-services') ? 'active' : '' }}">
                            <i data-lucide="list" class="w-5 h-5"></i> Mis Reservas
                        </a></li>
                        <li><a href="{{ route('corporate.comissions') }}" class="flex gap-4 py-3 {{ request()->routeIs('corporate.comissions') ? 'active' : '' }}">
                            <i data-lucide="wallet" class="w-5 h-5"></i> Comisiones
                        </a></li>

                    @elseif ($isTraveler)
                        <li><a href="{{ route('mis_reservas') }}" class="flex gap-4 py-3 {{ request()->is('my-services') ? 'active' : '' }}">
                            <i data-lucide="list" class="w-5 h-5"></i> Mis Reservas
                        </a></li>
                        <li><a href="{{ route('transfer.select-type') }}" class="flex gap-4 py-3 {{ request()->routeIs('transfer.*') ? 'active' : '' }}">
                            <i data-lucide="map-pin" class="w-5 h-5"></i> Reservar
                        </a></li>
                    @endif

                    <div class="divider before:bg-slate-800 after:bg-slate-800 my-2 opacity-50"></div>

                    <li><a href="{{ route('profile.edit') }}" class="flex gap-4 py-3 {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i data-lucide="user-cog" class="w-5 h-5"></i> Mi Perfil
                    </a></li>
                @else
                    {{-- LINKS PARA VISITANTES --}}
                    <li><a href="{{ route('login') }}" class="flex gap-4 py-3 hover:bg-slate-800 hover:text-white">
                        <i data-lucide="log-in" class="w-5 h-5"></i> Iniciar Sesión
                    </a></li>
                    <li><a href="{{ route('register') }}" class="flex gap-4 py-3 hover:bg-slate-800 hover:text-white">
                        <i data-lucide="user-plus" class="w-5 h-5"></i> Registrarse
                    </a></li>
                    <div class="divider before:bg-slate-800 after:bg-slate-800 my-2 opacity-50"></div>
                    <li><a href="/" class="flex gap-4 py-3 hover:bg-slate-800 hover:text-white">
                        <i data-lucide="home" class="w-5 h-5"></i> Volver a la Web
                    </a></li>
                @endif
            </nav>

            @if($isAnyAuth)
            <div class="mt-auto pt-8 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="btn btn-ghost w-full justify-start text-red-400 hover:bg-red-500/10 gap-4 font-bold text-xs uppercase tracking-widest">
                        <i data-lucide="power" class="w-4 h-4"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
            @endif
        </div>
    </aside>
</div>

<script>
    lucide.createIcons();
</script>

</body>
</html>