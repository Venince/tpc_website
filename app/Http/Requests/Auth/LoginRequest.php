<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * How many failed attempts are allowed per lockout window.
     */
    const ATTEMPTS_PER_BLOCK = 5;

    /**
     * How long (in seconds) the attempts counter lives without any activity.
     */
    const ATTEMPTS_TTL_HOURS = 24;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            $this->recordFailedAttempt();

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Successful login — wipe all tracking for this key
        $this->clearAllLimits();
    }

    /**
     * Ensure the login request is not currently locked out.
     *
     * Reads the stored lockout-until timestamp and, if it is still in the
     * future, fires the Lockout event and throws a throttle exception.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        $lockedUntil = Cache::get($this->lockoutKey());

        if (! $lockedUntil) {
            return;
        }

        $secondsRemaining = $lockedUntil - now()->timestamp;

        if ($secondsRemaining <= 0) {
            // The lockout window has passed; remove the lockout entry.
            // NOTE: We intentionally leave the attempts counter intact so the
            // next block of failures will escalate to a longer duration.
            Cache::forget($this->lockoutKey());
            return;
        }

        event(new Lockout($this));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $secondsRemaining,
                'minutes' => ceil($secondsRemaining / 60),
            ]),
        ]);
    }

    /**
     * Record a failed attempt.
     *
     * Increments the persistent total-attempts counter.  When the count
     * reaches a multiple of ATTEMPTS_PER_BLOCK, a new lockout is stored and
     * a throttle exception is thrown immediately with the correct duration.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function recordFailedAttempt(): void
    {
        $attemptsKey = $this->attemptsKey();
        $ttl         = now()->addHours(self::ATTEMPTS_TTL_HOURS);

        // Read the current count, default to 0 if the key doesn't exist yet.
        $current = (int) Cache::get($attemptsKey, 0);
        $current++;

        // Always (re)write with a fresh TTL so inactivity eventually resets it.
        Cache::put($attemptsKey, $current, $ttl);

        // Trigger a lockout every time a full block of 5 is completed.
        if ($current % self::ATTEMPTS_PER_BLOCK === 0) {
            $blockNumber    = intdiv($current, self::ATTEMPTS_PER_BLOCK); // 1, 2, 3 …
            $lockoutSeconds = $blockNumber * 60;                           // 60, 120, 180 …

            // Store the Unix timestamp at which the lockout expires.
            Cache::put(
                $this->lockoutKey(),
                now()->addSeconds($lockoutSeconds)->timestamp,
                now()->addSeconds($lockoutSeconds)
            );

            event(new Lockout($this));

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $lockoutSeconds,
                    'minutes' => ceil($lockoutSeconds / 60),
                ]),
            ]);
        }
    }

    /**
     * Clear all rate-limit and lockout data on successful login.
     */
    protected function clearAllLimits(): void
    {
        Cache::forget($this->attemptsKey());
        Cache::forget($this->lockoutKey());
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Cache key for the persistent total failed-attempts counter.
     * Tracks cumulative failures across all lockout windows for this email+IP.
     */
    public function attemptsKey(): string
    {
        return 'login_attempts|' . Str::transliterate(
            Str::lower($this->string('email')) . '|' . $this->ip()
        );
    }

    /**
     * Cache key that holds the Unix timestamp of when the active lockout ends.
     * Absent when no lockout is in effect.
     */
    public function lockoutKey(): string
    {
        return 'login_lockout|' . Str::transliterate(
            Str::lower($this->string('email')) . '|' . $this->ip()
        );
    }

    /**
     * Kept for compatibility with anything that references the original throttle key.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string('email')) . '|' . $this->ip()
        );
    }
}
