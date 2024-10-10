<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'], // bisa username atau phone
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Cek apakah input login berupa angka atau bukan untuk menentukan username atau phone
        $loginType = is_numeric($this->login) ? 'phone' : 'username';

        // Cek apakah user ada berdasarkan loginType (phone atau username)
        $user = User::where($loginType, $this->login)->first();

        if (!$user) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('Username/No Hp Tidak Tersedia!'),
            ]);
        }

        // Autentikasi menggunakan username atau phone dan password
        if (!Auth::attempt([$loginType => $this->login, 'password' => $this->password], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'password' => trans('Password Anda salah!'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }


    // Method untuk mencoba login menggunakan username atau phone
    protected function attemptLoginWithUsernameOrPhone()
    {
        $credentials = $this->only('password');

        // Cek apakah input 'login' berupa angka atau bukan
        $loginInput = $this->input('login');

        if (is_numeric($loginInput)) {
            // Jika input berupa angka, berarti itu nomor HP (phone)
            $credentials['phone'] = $loginInput;
        } else {
            // Jika bukan angka, berarti itu username
            $credentials['username'] = $loginInput;
        }

        // Coba autentikasi dengan field phone atau username
        return Auth::attempt($credentials, $this->boolean('remember'));
    }


    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        throw ValidationException::withMessages([
            'login' => __('auth.throttle', ['seconds' => RateLimiter::availableIn($this->throttleKey())]),
        ]);
    }

    public function throttleKey(): string
    {
        return strtolower($this->input('login')) . '|' . $this->ip();
    }
}
