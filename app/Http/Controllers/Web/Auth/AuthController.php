<?php

namespace App\Http\Controllers\Web\Auth;

use App\Enums\LearnerProfile;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Mail\Admin\NouveauCompte;
use App\Mail\BienvenueApprenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->isAdmin() ? 'admin.dashboard' : 'catalogue');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = $request->input('login');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $user  = User::where($field, $login)->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            return back()
                ->withErrors(['login' => 'Identifiants incorrects.'])
                ->withInput($request->only('login'));
        }

        if (! $user->is_active) {
            return back()
                ->withErrors(['login' => 'Votre compte a été désactivé.'])
                ->withInput($request->only('login'));
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(
            $user->isAdmin() ? route('admin.dashboard') : route('catalogue')
        );
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('catalogue');
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'last_name'    => ['required', 'string', 'max:100'],
            'first_name'   => ['required', 'string', 'max:100'],
            'profile_type' => ['required', \Illuminate\Validation\Rule::enum(LearnerProfile::class)],
            'intitule'     => ['nullable', 'string', 'max:200'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'        => ['required', 'string', 'max:30'],
            'password'     => ['required', 'confirmed', Password::defaults()],
            'country'      => ['required', 'string', 'max:100'],
            'city'         => ['required', 'string', 'max:100'],
            'birth_date'   => ['nullable', 'date', 'before:today'],
            'gender'       => ['nullable', 'string', 'in:homme,femme,autre'],
        ]);

        $user = User::create([
            ...$data,
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'role' => UserRole::Apprenant,
        ]);

        Auth::login($user);

        try {
            Mail::to($user->email)->send(new BienvenueApprenant($user));
            Mail::to(config('formation.admin.email'))->send(new NouveauCompte($user));
        } catch (\Exception) {
            // L'envoi d'email ne bloque pas l'inscription
        }

        return redirect()->route('catalogue')->with('success', 'Bienvenue ' . $user->first_name . ' ! Votre compte a été créé.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}
