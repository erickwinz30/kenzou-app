<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class notAdmin
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    // if (!auth()->user()->is_admin || auth()->user()->is_admin) {
    //   return $next($request);
    // }

    // return redirect()->route('dashboard-login');

    if (!auth()->check()) {
      return redirect()->route('dashboard-login');
    }

    // Jika pengguna sudah login, lanjutkan permintaan
    return $next($request);
  }
}
