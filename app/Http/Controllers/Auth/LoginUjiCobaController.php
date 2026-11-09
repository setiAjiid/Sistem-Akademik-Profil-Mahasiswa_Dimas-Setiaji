<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Login instan untuk akun uji coba, supaya demo tidak perlu mengetik password.
 * Hanya aktif saat APP_ENV=local. Di environment lain rutenya menjawab 404.
 */
class LoginUjiCobaController extends Controller
{
    public function __invoke(string $role): RedirectResponse
    {
        abort_unless(app()->environment('local'), 404);
        abort_unless(in_array($role, User::DAFTAR_ROLE, true), 404);

        $user = User::where('role', $role)->orderBy('id')->first();

        if ($user === null) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Akun uji coba belum ada. Jalankan php artisan db:seed dulu.']);
        }

        Auth::login($user);
        request()->session()->regenerate();

        return redirect()->route($user->rutePascaLogin());
    }
}
