<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CashierController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['email'] = $data['username'].'@dalwa-water.local';
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('dashboard', ['page' => 'cashiers'])->with('success', 'User kasir berhasil ditambahkan.');
    }

    public function update(Request $request, User $cashier): RedirectResponse
    {
        $data = $this->validated($request, $cashier);
        $data['email'] = $data['username'].'@dalwa-water.local';

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $cashier->update($data);

        return redirect()->route('dashboard', ['page' => 'cashiers'])->with('success', 'User berhasil diperbarui.');
    }

    public function resetPassword(Request $request, User $cashier): RedirectResponse
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:6'],
        ]);

        $cashier->update(['password' => Hash::make($data['password'])]);

        return redirect()->route('dashboard', ['page' => 'cashiers'])->with('success', 'Password berhasil direset.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        $passwordRule = $user ? ['nullable', 'string', 'min:6'] : ['required', 'string', 'min:6'];

        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'max:80', Rule::unique('users', 'username')->ignore($user)],
            'password' => $passwordRule,
            'role' => ['required', Rule::in(['admin', 'cashier'])],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }
}
