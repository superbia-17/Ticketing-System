<?php

namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
 
class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);
 
        $user = User::whereRaw('LOWER(email) = ?', [strtolower($request->email)])->first();
 
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string|min:8|confirmed',
            'role'      => 'required|in:public,student',
            // NIM is required only when registering as a student
            'nim'       => 'required_if:role,student|nullable|string|max:20|unique:users,nim',
        ]);
 
        $user = User::create([
            'name'     => $request->name,
            'email'    => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'nim'      => $request->role === 'student' ? $request->nim : null,
        ]);
 
        Auth::login($user);
 
        return redirect(route('dashboard')); // Adjust redirect as needed
    }
 
    // API methods can be kept or removed if not needed
    public function apiLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);
 
        $user = User::where('email', $request->email)->first();
 
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
 
        $token = $user->createToken('api')->plainTextToken;
 
        return response()->json(['user' => $user, 'token' => $token]);
    }
 
    public function apiLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    }
 
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}