<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\MyServicesController;
use App\Http\Controllers\HubManagerController;
use App\Http\Controllers\PartnerOperationsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\ShowcaseController;
use App\Http\Controllers\PartnerManagementController;

// --- RUTAS PÚBLICAS ---
Route::get('/', [ShowcaseController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// --- CALENDARIO / AGENDA ---
Route::prefix('schedule')->name('calendar.')->group(function () {
    Route::get('/', [ScheduleController::class, 'index'])->name('index');
    Route::get('/events', [ScheduleController::class, 'events'])->name('events');
    Route::get('/booking/{id}', [ScheduleController::class, 'show'])->name('show');
});

// --- PROCESO DE RESERVA ---
Route::prefix('transfer')->name('transfer.')->group(function () {
    Route::get('/select-type', [TransferController::class, 'showTypeSelection'])->name('select-type');
    Route::post('/select-type', [TransferController::class, 'postTypeSelection'])->name('select-type.post');
    Route::get('/reserve/{type}', [TransferController::class, 'showReservationForm'])->name('reserve.form');
});

// --- RUTAS PROTEGIDAS ---
Route::middleware(['auth:admin,corporate,web', 'clear-others'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AuthController::class, 'redirectDashboard'])->name('dashboard');

    // Perfil de Usuario
    Route::prefix('account')->name('profile.')->group(function () {
        Route::get('/', [AccountController::class, 'edit'])->name('edit');
        Route::put('/', [AccountController::class, 'update'])->name('update');
    });

    Route::post('transfer/confirm', [TransferController::class, 'confirmReservation'])->name('transfer.reserve.confirm');

    // Mis Reservas (Común para todos)
    Route::prefix('my-services')->name('mis_reservas')->group(function () {
        Route::get('/', [MyServicesController::class, 'index'])->name(''); // route('mis_reservas')
        Route::get('{id}/edit', [MyServicesController::class, 'edit'])->name('.edit');
        Route::put('{id}', [MyServicesController::class, 'update'])->name('.update');
        Route::delete('{id}', [MyServicesController::class, 'destroy'])->name('.destroy');
    });

    // --- PANEL ADMINISTRACIÓN ---
    Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        
        Route::get('/services/registry', [HubManagerController::class, 'listServices'])->name('reservations.list');
        Route::get('/services/file/{id}', [HubManagerController::class, 'viewServiceFile'])->name('reserva.detalle');
        Route::get('/revenue/audit', [HubManagerController::class, 'revenueReport'])->name('commissions');

        // Gestión de Flota (Antes Vehículos)
        Route::prefix('fleet')->name('fleet.')->group(function () {
            Route::get('/', [FleetController::class, 'index'])->name('index');
            Route::get('/create', [FleetController::class, 'create'])->name('create');
            Route::post('/', [FleetController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [FleetController::class, 'edit'])->name('edit');
            Route::put('/{id}', [FleetController::class, 'update'])->name('update');
            Route::put('/{id}/deactivate', [FleetController::class, 'deactivate'])->name('deactivate');
            Route::put('/{id}/activate', [FleetController::class, 'activate'])->name('activate');
        });

        // Gestión de Partners (Antes Hoteles)
        Route::prefix('partners')->name('hoteles.')->group(function () {
            Route::get('/', [PartnerManagementController::class, 'index'])->name('index');
            Route::post('/', [PartnerManagementController::class, 'store'])->name('store');
            Route::put('/{id}/disable', [PartnerManagementController::class, 'disable'])->name('disable');
            Route::put('/{id}/enable', [PartnerManagementController::class, 'enable'])->name('enable');
        });

        Route::get('/export/regions-json', function () {
            $total = \DB::table('transfer_reservas')->count();
            $zonas = \DB::table('transfer_zonas AS z')
                ->leftJoin('transfer_hoteles AS h', 'h.id_zona', '=', 'z.id_zona')
                ->leftJoin('transfer_reservas AS r', 'r.id_hotel', '=', 'h.id_hotel')
                ->selectRaw('z.descripcion AS zona, COUNT(r.id_reserva) AS num_traslados')
                ->groupBy('z.descripcion')->get()
                ->map(function ($item) use ($total) {
                    $item->porcentaje = $total > 0 ? round(($item->num_traslados / $total) * 100, 2) : 0;
                    return $item;
                });
            return response(json_encode(['total' => $total, 'regions' => $zonas], JSON_PRETTY_PRINT))
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', 'attachment; filename="analytics_regions.json"');
        })->name('descargarJsonZonas');
    });

    // --- PANEL PARTNER (CORPORATIVO) ---
    Route::prefix('partner-access')->name('corporate.')->middleware('auth:corporate')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'hotel'])->name('dashboard');
        Route::get('/audit/earnings', [PartnerOperationsController::class, 'earningsAudit'])->name('comissions');
    });

    // --- PANEL VIAJERO ---
    Route::prefix('traveler')->name('user.')->middleware('auth:web')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'user'])->name('dashboard');
    });
});