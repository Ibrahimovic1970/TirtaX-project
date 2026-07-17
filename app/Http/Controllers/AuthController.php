<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login form submission
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'courier') {
                return redirect()->intended('/courier/dashboard');
            } else {
                return redirect()->intended('/customer/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration form submission
     */
    public function register(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name'                 => ['required', 'string', 'max:255'],
            'email'                => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'             => ['required', 'string', 'min:8', 'confirmed'],
            'terms'                => ['accepted'],
            'g-recaptcha-response' => ['required', function ($attribute, $value, $fail) {
                // Verify reCAPTCHA
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => config('services.recaptcha.secret_key'),
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);

                if (! $response->successful()) {
                    $fail('Verifikasi reCAPTCHA gagal. Silakan coba lagi.');
                    return;
                }

                $result = $response->json();

                if (! $result['success']) {
                    $fail('Verifikasi reCAPTCHA gagal. Silakan coba lagi.');
                    return;
                }

                // Untuk production, check score (untuk reCAPTCHA v3)
                // Untuk reCAPTCHA v2 checkbox, success sudah cukup
            }],
        ], [
            'name.required'                 => 'Nama lengkap harus diisi.',
            'email.required'                => 'Email harus diisi.',
            'email.email'                   => 'Format email tidak valid.',
            'email.unique'                  => 'Email sudah terdaftar.',
            'password.required'             => 'Password harus diisi.',
            'password.min'                  => 'Password minimal 8 karakter.',
            'password.confirmed'            => 'Konfirmasi password tidak cocok.',
            'terms.accepted'                => 'Anda harus menyetujui syarat dan ketentuan.',
            'g-recaptcha-response.required' => 'Silakan verifikasi bahwa Anda bukan robot.',
        ]);

        // Buat user baru
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'customer', // Default role
        ]);

        // Login user
        auth()->login($user);

        return redirect()->route('customer.dashboard')
            ->with('success', 'Akun berhasil dibuat! Selamat datang di TirtaX.');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password form submission
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        // Send password reset email logic here
        // For now, just return success
        return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
    }

    /**
     * Show reset password form
     */
    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle reset password form submission
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        // Reset password logic here
        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah logout.');
    }
}
