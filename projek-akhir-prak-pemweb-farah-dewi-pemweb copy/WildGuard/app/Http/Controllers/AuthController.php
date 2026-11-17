<?php

namespace App\Http\Controllers;

use App\Http\Concerns\PesanValidasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use PesanValidasi;

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $cred = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], $this->pesan(), ['email' => 'Email', 'password' => 'Password']);

        if (!Auth::attempt($cred, $request->boolean('ingat'))) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        $request->session()->regenerate();
        $default = Auth::user()->role === 'admin' ? route('admin.dashboard') : route('laporan-saya.index');
        return redirect()->intended($default);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $pesan = array_merge($this->pesan(), [
            'name.regex' => 'Nama hanya boleh berisi huruf, spasi, titik, apostrof, dan tanda hubung.',
            'email.email' => 'Format email tidak valid (contoh: nama@email.com).',
            'password.min' => 'Password minimal 8 karakter.',
            'password.max' => 'Password maksimal 72 karakter.',
            'password.regex' => 'Password tidak boleh mengandung spasi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Ulangi password wajib diisi.',
        ]);

        $data = $request->validate([
            'name' => ['required', 'min:3', 'max:100', "regex:/^[\pL\s.'-]+$/u"],
            'email' => ['required', 'email:rfc', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:72', 'regex:/^\S+$/', 'confirmed'],
            'password_confirmation' => ['required'],
        ], $pesan, [
            'name' => 'Nama', 'email' => 'Email', 'password' => 'Password',
            'password_confirmation' => 'Ulangi password',
        ]);

        // Aturan komposisi password: huruf besar, huruf kecil, angka, simbol, dan tidak mirip data diri
        $galat = [];
        $pw = $data['password'];
        if (!preg_match('/\p{Lu}/u', $pw)) $galat[] = 'Password harus memuat minimal satu huruf besar.';
        if (!preg_match('/\p{Ll}/u', $pw)) $galat[] = 'Password harus memuat minimal satu huruf kecil.';
        if (!preg_match('/\d/', $pw)) $galat[] = 'Password harus memuat minimal satu angka.';
        if (!preg_match('/[^\p{L}\d]/u', $pw)) $galat[] = 'Password harus memuat minimal satu simbol (contoh: ! @ # $ %).';
        $kecil = mb_strtolower($pw);
        $nama = mb_strtolower(trim($data['name']));
        $bagianEmail = mb_strtolower(strstr($data['email'], '@', true) ?: $data['email']);
        if ($kecil === $nama || $kecil === mb_strtolower($data['email']) || $kecil === $bagianEmail) {
            $galat[] = 'Password tidak boleh sama dengan nama atau email Anda.';
        }
        if ($galat) {
            return back()->withInput($request->only('name', 'email'))->withErrors(['password' => $galat]);
        }

        // forceFill: kolom role tidak ada di $fillable bawaan User, jadi diisi eksplisit
        $user = (new User())->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user', // pendaftar publik selalu user biasa
        ]);
        $user->save();

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('laporan-saya.index')->with('ok', 'Akun berhasil dibuat. Selamat datang!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
