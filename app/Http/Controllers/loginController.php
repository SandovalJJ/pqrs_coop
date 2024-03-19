<?php

namespace App\Http\Controllers;

use App\Models\Usuario;

use Illuminate\Http\Request;

class loginController extends Controller
{
  
  public function login(){
    return view('auth.login');
  }

  public function ingresar(Request $request){

    $usuario = Usuario::select('cedula', 'nombre', 'apellido', 'agencia', 'clave', 'perfil', 'estado')
      ->where('cedula', $request->cedula)
      ->get();
    if (sizeof($usuario) != 0) {
      if($usuario[0]->clave == $request->password){
        session()->put(
          [ 
            'cedula'   => $usuario[0]->cedula, 
            'nombre'   => $usuario[0]->nombre, 
            'apellido' => $usuario[0]->apellido,  
            'agencia'  => $usuario[0]->agencia, 
            'perfil'   => $usuario[0]->perfil,
            'estado'   => $usuario[0]->estado
          ]);

        return redirect('inicio'); 
      }
      return back()->with('msj-error', 'Contraseña incorrecta');
    }
    return back()->with('msj-error', 'Este numero de cedula o agencia '.$request->cedula.' no existe ');
  }

  public function logout(Request $request){
    $request->session()->forget(['cedula', 'nombre', 'apellido', 'agencia', 'perfil', 'estado']);
    return redirect('/login');
  } 
}
