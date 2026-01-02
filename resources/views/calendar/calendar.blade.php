@extends('layouts.app')

@section('content')
<div class="space-y-8 pb-20">
    {{-- Header del Calendario --}}
    <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-10 lg:p-14 shadow-2xl text-white">
        <div class="absolute -right-10 -top-10 h-64 w-64 rounded-full bg-primary/10 blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 bg-primary/20 rounded-2xl flex items-center justify-center text-primary shadow-lg">
                    <i data-lucide="calendar-range" class="w-10 h-10"></i>
                </div>
                <div>
                    <h1 class="text-3xl lg:text-4xl font-black italic uppercase tracking-tight">Agenda de <span class="text-primary text-white underline decoration-primary/40 decoration-4 underline-offset-8">Operaciones</span></h1>
                    <p class="text-slate-400 font-medium mt-1 uppercase tracking-widest text-[10px]">Vista cronológica de traslados confirmados</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Cuerpo del Calendario --}}
    <div class="calendar-wrapper max-w-[1400px] mx-auto">
        <div class="bg-white border border-slate-100 rounded-[3rem] p-8 lg:p-12 shadow-xl overflow-hidden">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<style>
    .fc-toolbar-title { font-size: 1.5rem !important; font-weight: 800 !important; color: #0f172a !important; text-transform: uppercase; letter-spacing: -0.025em; }
    .fc .fc-button { background-color: #f1f5f9 !important; border: none !important; color: #475569 !important; font-weight: 700 !important; text-transform: uppercase !important; font-size: 0.7rem !important; letter-spacing: 0.1em !important; padding: 10px 20px !important; border-radius: 12px !important; transition: all 0.2s; }
    .fc .fc-button:hover { background-color: #e2e8f0 !important; color: #0f172a !important; }
    .fc .fc-button-active { background-color: #f97316 !important; color: white !important; box-shadow: 0 10px 15px rgba(249,115,22,0.3) !important; }
    .fc-col-header-cell { background: #0f172a !important; border: none !important; padding: 12px 0 !important; }
    .fc-col-header-cell-cushion { color: #94a3b8 !important; font-size: 0.7rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em; }
    .fc-theme-standard td, .fc-theme-standard th { border: 1px solid #f1f5f9 !important; }
    .fc-daygrid-day:hover { background-color: #f8fafc !important; }
    .fc-daygrid-day-number { font-weight: 800 !important; color: #cbd5e1 !important; padding: 10px !important; font-size: 0.9rem; }
    .fc-day-today { background-color: #fff7ed !important; }
    .fc-day-today .fc-daygrid-day-number { color: #f97316 !important; }
    .fc-v-event { border: none !important; padding: 4px 8px !important; border-radius: 8px !important; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    .fc-event-title { font-weight: 700 !important; font-size: 0.75rem !important; }
</style>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        firstDay: 1,
        initialView: 'dayGridMonth',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,dayGridDay'
        },
        buttonText: { today: 'Hoy', month: 'Mes', week: 'Semana', day: 'Día' },
        
        events: function(info, successCallback, failureCallback) {
            const url = `{{ route('calendar.events') }}?from=${info.startStr}&to=${info.endStr}`;
            
            fetch(url)
                .then(r => {
                    if (!r.ok) throw new Error('Error en la carga');
                    return r.json();
                })
                .then(data => {
                    successCallback(
                        data.map(ev => ({
                            id: ev.id,
                            title: ev.title,
                            start: ev.start,
                            allDay: true,
                            backgroundColor: ev.tipo === 1 ? '#22c55e' : (ev.tipo === 2 ? '#3b82f6' : '#f97316'),
                            borderColor: 'transparent'
                        }))
                    );
                })
                .catch(err => {
                    console.error("Error cargando eventos:", err);
                    failureCallback(err);
                });
        },

        eventClick: function(info) {
            window.location.href = `/schedule/booking/${info.event.id}`;
        }
    });
    
    calendar.render();
});
</script>
@endsection