<?php

namespace BilliftySDK\SharedResources\Modules\User\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		if ($request->user() && !$request->user()->email_verified_at) {
			return response()->json([
				'message' => 'Please verify your email address before creating invoices.',
				'code' => 'EMAIL_NOT_VERIFIED',
			], 403);
		}
        return $next($request);
    }
}
