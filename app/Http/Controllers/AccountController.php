<?php

namespace App\Http\Controllers;

use App\Models\StaffMember;
use App\Models\Partner;
use App\Models\Traveler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    private function authenticateIdentity(): array
    {
        foreach (['admin', 'corporate', 'web'] as $guard) {
            if (Auth::guard($guard)->check()) {
                return ['guard' => $guard, 'user' => Auth::guard($guard)->user()];
            }
        }
        abort(401);
    }

    public function edit()
    {
        $identity = $this->authenticateIdentity();
        return view('profile.edit', ['user' => $identity['user'], 'guard' => $identity['guard']]);
    }

    public function update(Request $request)
    {
        $identity = $this->authenticateIdentity();
        $user = $identity['user'];
        $guard = $identity['guard'];

        $config = match ($guard) {
            'admin'     => ['table' => 'transfer_adminAndrea', 'id' => 'id_admin', 'email' => 'email_admin'],
            'corporate' => ['table' => 'transfer_hotelesAndrea', 'id' => 'id_hotel', 'email' => 'email_hotel'],
            default     => ['table' => 'transfer_viajerosAndrea', 'id' => 'id_viajero', 'email' => 'email_viajero'],
        };

        $rules = [
            'nombre'   => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', "unique:{$config['table']},{$config['email']}," . $user->{$config['id']} . ",{$config['id']}"],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ];

        if ($guard === 'web') {
            $rules = array_merge($rules, [
                'apellido1' => ['nullable'], 'apellido2' => ['nullable'], 'direccion' => ['nullable'],
                'codigoPostal' => ['nullable'], 'ciudad' => ['nullable'], 'pais' => ['nullable']
            ]);
        }

        $validated = $request->validate($rules);

        $user->nombre = $validated['nombre'];
        $user->{$config['email']} = $validated['email'];

        if ($guard === 'web') {
            foreach (['apellido1', 'apellido2', 'direccion', 'codigoPostal', 'ciudad', 'pais'] as $field) {
                $user->{$field} = $validated[$field] ?? $user->{$field};
            }
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        return redirect()->route('profile.edit')->with('status', 'Account updated.');
    }
}