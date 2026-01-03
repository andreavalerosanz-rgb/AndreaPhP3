<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Traveler;
use App\Models\Partner;
use App\Models\StaffMember;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function showRegister()
    {
        $zonas = \DB::table('transfer_zonasAndrea')->get();
        return view('register', compact('zonas'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $credentials['email'];
        $password = $credentials['password'];

        $partner = Partner::where('email_hotel', $email)->first();

        if ($partner && $partner->activo == 0) {
            throw ValidationException::withMessages([
                'email' => 'Su cuenta de partner está inhabilitada.',
            ]);
        }

        $traveler = Traveler::where('email_viajero', $email)->first();

        if ($traveler) {
            if (Auth::guard('web')->attempt(['email_viajero' => $email, 'password' => $password])) {
                $request->session()->regenerate();
                return redirect()->route('user.dashboard');
            }
            throw ValidationException::withMessages(['email' => __('Contraseña incorrecta')]);
        }

        if ($partner) {
            if (Auth::guard('corporate')->attempt(['email_hotel' => $email, 'password' => $password])) {
                $request->session()->regenerate();
                return redirect()->route('corporate.dashboard');
            }
            throw ValidationException::withMessages(['email' => __('Contraseña incorrecta')]);
        }

        $staff = StaffMember::where('email_admin', $email)->first();

        if ($staff) {
            if (Auth::guard('admin')->attempt(['email_admin' => $email, 'password' => $password])) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }
            throw ValidationException::withMessages(['email' => __('Contraseña incorrecta')]);
        }

        throw ValidationException::withMessages([
            'email' => __('Este email no está registrado.'),
        ]);
    }

    public function register(Request $request)
    {
        $baseRules = [
            'role' => 'required|in:viajero,hotel',
            'nombre' => 'required|string|max:100',
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:6|confirmed',
        ];

        if ($request->role === 'viajero') {
            $travelerRules = [
                'apellido1' => 'required|string|max:100',
                'apellido2' => 'required|string|max:100',
                'direccion' => 'required|string|max:100',
                'codigoPostal' => 'required|string|max:100',
                'ciudad' => 'required|string|max:100',
                'pais' => 'required|string|max:100',
            ];
            $request->validate(array_merge($baseRules, $travelerRules));
        } else {
            $partnerRules = [
                'comision' => 'required|integer|min:0|max:100',
                'id_zona' => 'required|exists:transfer_zonasAndrea,id_zona',
            ];
            $request->validate(array_merge($baseRules, $partnerRules));
        }

        $email = $request->email;
        $password = Hash::make($request->password);
        $role = $request->role;

        try {
            if ($role === 'viajero') {
                if (Traveler::where('email_viajero', $email)->exists()) {
                    throw ValidationException::withMessages(['email' => 'Este email ya está registrado.']);
                }

                $user = Traveler::create([
                    'nombre' => $request->nombre,
                    'email_viajero' => $email,
                    'password' => $password,
                    'apellido1' => $request->apellido1,
                    'apellido2' => $request->apellido2,
                    'direccion' => $request->direccion,
                    'codigoPostal' => $request->codigoPostal,
                    'ciudad' => $request->ciudad,
                    'pais' => $request->pais,
                ]);

                $guard = 'web';
            } elseif ($role === 'hotel') {
                if (Partner::where('email_hotel', $email)->exists()) {
                    throw ValidationException::withMessages(['email' => 'Este email ya está registrado.']);
                }

                $user = Partner::create([
                    'nombre' => $request->nombre,
                    'email_hotel' => $email,
                    'password' => $password,
                    'Comision' => $request->comision,
                    'id_zona' => $request->id_zona,
                ]);

                $guard = 'corporate';
            }

            Auth::guard($guard)->login($user);
            return $this->redirectDashboard();
        } catch (QueryException $e) {
            throw ValidationException::withMessages([
                'registro_error' => 'Error en la base de datos durante el registro.'
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        Auth::guard('corporate')->logout();
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function redirectDashboard()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        if (Auth::guard('corporate')->check()) {
            return redirect()->route('corporate.dashboard');
        }
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.dashboard');
        }
        return redirect()->route('home');
    }
}