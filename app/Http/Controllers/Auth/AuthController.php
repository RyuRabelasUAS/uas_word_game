<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile_number' => 'required|string|max:20',
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile_number' => $validated['mobile_number'],
            'company' => $validated['company'],
            'position' => $validated['position'],
            'is_admin' => false,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('game.index');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'No account found with this email address.',
            ])->onlyInput('email');
        }

        // If user is admin, require password
        if ($user->is_admin) {
            if (!$request->filled('password')) {
                return back()->withErrors([
                    'password' => 'This account requires a password. Please enter your password.',
                ])->onlyInput('email');
            }

            if (!Hash::check($request->password, $user->password)) {
                return back()->withErrors([
                    'password' => 'The provided password is incorrect.',
                ])->onlyInput('email');
            }
        }

        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        return redirect()->intended($user->is_admin ? route('admin.levels.index') : route('game.index'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('game.index');
    }

    // API endpoint to check if email is admin
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'is_admin' => $user ? $user->is_admin : false,
        ]);
    }
}
