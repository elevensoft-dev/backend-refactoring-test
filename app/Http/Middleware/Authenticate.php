<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * Redirect to documentation page when request does not expect JSON.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : url('/api/documentation');
    }
}
