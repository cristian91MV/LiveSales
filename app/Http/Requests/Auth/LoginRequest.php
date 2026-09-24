<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials with rate limiting and throttle protection.
     *
     * Centralizes all throttle logic and authentication attempts.
     * Validates rate limit before attempting authentication.
     * If authentication fails, increments the rate limiter.
     * If authentication succeeds, clears the rate limiter.
     * The is_active check is the responsibility of the controller.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        // Generate throttle key: sha1(email|ip)
        $key = sha1($this->input('email') . '|' . $this->ip());

        // Check if too many attempts have been made
        if (RateLimiter::tooManyAttempts($key, 5)) {
            // Get seconds remaining until throttle is lifted
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => "Demasiados intentos de inicio de sesión. Por favor, espere {$seconds} segundos antes de intentarlo nuevamente.",
            ]);
        }

        // Attempt authentication with email and password only
        // Note: is_active is NOT included in the authentication attempt
        // The controller is responsible for checking is_active after successful authentication
        if (!Auth::attempt(
            ['email' => $this->email, 'password' => $this->password],
            $this->boolean('remember')
        )) {
            // Authentication failed: increment the rate limiter
            RateLimiter::hit($key);

            // Throw a generic validation exception without indicating which field is incorrect
            throw ValidationException::withMessages([
                'email' => 'Las credenciales proporcionadas no son válidas.',
            ]);
        }

        // Authentication succeeded: clear the rate limiter
        RateLimiter::clear($key);
    }
}
