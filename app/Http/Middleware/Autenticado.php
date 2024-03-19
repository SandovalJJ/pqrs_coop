<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;
use App\Models\Usuario;

class Autenticado
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
      if($sesion = session()->has('cedula')){
        return $next($request);
      }else{
        return redirect('/login');
        $request->session()->forget(['cedula', 'nombre', 'apellido', 'agencia', 'clave', 'perfil', 'estado']);
      }
    }
}
