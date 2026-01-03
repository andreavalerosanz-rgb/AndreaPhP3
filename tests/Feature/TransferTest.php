<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\FleetUnit;
use App\Models\Partner;
use App\Models\Region;
use App\Models\Traveler;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class TransferTest extends TestCase
{
    use RefreshDatabase; // Resets DB after each test

    protected $region;
    protected $partner;
    protected $fleet;
    protected $traveler;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup initial data
        $this->region = Region::create([
            'descripcion' => 'Mallorca North'
        ]);

        $this->partner = Partner::create([
            'nombre' => 'Grand Hotel',
            'id_zona' => $this->region->id_zona,
            'email_hotel' => 'hotel@example.com',
            'Comision' => 10,
            'password' => Hash::make('secret'),
            'activo' => 1
        ]);

        $this->fleet = FleetUnit::create([
            'Descripción' => 'Sedan Standard',
            'email_conductor' => 'driver@example.com',
            'password' => 'secret',
            'activo' => 1,
            'precio' => 50.00
        ]);

        $this->traveler = Traveler::create([
            'nombre' => 'John',
            'apellido1' => 'Doe',
            'email_viajero' => 'john@example.com',
            'password' => Hash::make('password'),
            'direccion' => '123 Fake St',
            'codigoPostal' => '00000',
            'ciudad' => 'Nowhere',
            'pais' => 'Noland'
        ]);
    }

    /** @test */
    public function transfer_selection_page_loads()
    {
        $response = $this->get(route('transfer.select-type'));
        $response->assertStatus(200);
        $response->assertViewIs('transfers.type');
    }

    /** @test */
    public function guest_can_view_reservation_form()
    {
        $response = $this->get(route('transfer.reserve.form', ['type' => 'airport_to_hotel']));
        $response->assertStatus(200);
        $response->assertSee('Aeropuerto Origen');
        $response->assertSee($this->partner->nombre);
        $response->assertSee($this->fleet->label);
    }

    /** @test */
    public function user_can_submit_reservation_airport_to_hotel()
    {
        // Simulate a logged in traveler if needed, or guest if allowed. 
        // Logic says Auth::guard('web')->user() is used if logged in, otherwise it creates new?
        // Let's force login as traveler to simplify "owner" logic
        $this->actingAs($this->traveler, 'web');

        $data = [
            'reservation_type' => 'airport_to_hotel',
            'aeropuerto_origen' => 'PMI',
            'num_vuelo' => 'IB1234',
            'fecha_llegada' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'hora_llegada' => '12:00',
            'id_hotel_destino' => $this->partner->id_hotel,
            'id_vehiculo' => $this->fleet->id_vehiculo,
            'pax' => 2,
            'nombre_contacto' => 'John Doe',
            'email_contacto' => 'john@example.com',
            'telefono' => '555-5555'
        ];

        $response = $this->post(route('transfer.reserve.confirm'), $data);

        $response->assertStatus(200); // Should return view with confirmation
        $response->assertViewIs('transfers.confirmation');
        $response->assertViewHas('localizador');

        $this->assertDatabaseHas('transfer_reservasAndrea', [
            'email_cliente' => 'john@example.com',
            'id_hotel' => $this->partner->id_hotel,
            'id_vehiculo' => $this->fleet->id_vehiculo
        ]);
    }

    /** @test */
    public function validation_fails_if_required_fields_missing()
    {
        $this->actingAs($this->traveler, 'web');

        $response = $this->post(route('transfer.reserve.confirm'), [
            'reservation_type' => 'airport_to_hotel',
            // Missing all other fields
        ]);

        $response->assertSessionHasErrors(['aeropuerto_origen', 'id_hotel_destino']);
    }
}
