<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Roles;

class IsLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check()){
            session()->flash('errors', 'Invalid Credentials');
            return redirect('/')->withErrors([
               'credentials' => 'Invalid credentials, please login first !!!'
            ]);
        }

        $user = Auth::user();
        $nameRoles = Roles::where('id',$user->role_id)->first();
        session()->put('role', $nameRoles->name);

        return $next($request);
    }
}
