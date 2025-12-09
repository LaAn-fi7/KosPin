<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            // jika ingin redirect ke home dengan pesan
            return redirect('/')->with('error', 'Akses admin diperlukan.');
        }
        return $next($request);
    }
}
