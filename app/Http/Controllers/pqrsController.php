<?php

namespace App\Http\Controllers;

use DB;
use DateTime;
use Response;

use App\Mail\pqrs;
use App\Mail\pqrs_user;
use App\Mail\resp_user;
use App\Mail\pqrs_fin_user;
use App\Mail\pqrs_fin_master;
use App\Models\Agencia;
use App\Models\Radicado;
use App\Models\Soporte;
use App\Services\PayUService\Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
date_default_timezone_set('America/Bogota');

class pqrsController extends Controller
{
  
  public function index(){

    $agencias = Agencia::select('cu', 'nombre_agencia')->orderBy('nombre_agencia', 'ASC')->get();

    return view('welcome', compact('agencias'));
  }

  public function radicar(Request $request){

    $fecha_hora = new DateTime();
    try {
      $datos = request()->validate([
        'tipo_solicitud' => 'required',
        'tipo_ide'       => 'required',
        'numero_ide'     => 'required|numeric|max:999999999999',
        'nombres'        => 'required|regex:/^[a-zA-Z]+(\s*[a-zA-Z]*)*[a-zA-Z]+$/|max:40',
        'apellidos'      => 'required|regex:/^[a-zA-Z]+(\s*[a-zA-Z]*)*[a-zA-Z]+$/|max:40',
        'nomina'         => 'required|regex:/^[a-zA-Z]+(\s*[a-zA-Z]*)*[a-zA-Z]+$/|max:40',
        'direccion'      => 'required|max:120',
        'email'          => 'required|regex:/\S+@\S+\.\S+/|max:100',
        'telefono'       => 'nullable|numeric|max:9999999999',
        'celular'        => 'required|numeric|max:999999999999',
        'whatsapp'       => 'required|numeric|max:999999999999',
        'agencia'        => 'required',
        'mensaje'        => 'max:900',
        'check_uno'      => 'required',
        'check_dos'      => 'required'
      ],[
        'required'        => 'Este campo es obligatorio.',
        'numeric'         => 'Solo se admiten números.',
        'numero_ide.max'  => 'Este campo no puede superar los 12 caracteres.',
        'nombres.regex'   => 'Solo se permiten letras.',
        'nombres.max'     => 'Solo se permiten 40 caracteres.',
        'apellidos.regex' => 'Solo se permiten letras.',
        'apellidos.max'   => 'Solo se permiten 40 caracteres.',
        'nomina.max'      => 'Solo se permiten 40 caracteres.',
        'direccion.max'   => 'Solo se permiten 120 caracteres',
        'email.regex'     => 'Este formato no es corecto',
        'email.max'       => 'Solo se permiten 100 caracteres',
        'telefono'        => 'Solo se permiten 10 caracteres',
        'celular'         => 'Solo se permiten 12 caracteres',
        'whatsapp'        => 'Solo se permiten 12 caracteres',
        'mensaje.max'     => 'Solo se permiten 900 caracteres',
      ]);

      $radicado = new Radicado;

      $radicado->tipo_solicitud      = $request->tipo_solicitud;
      $radicado->tipo_identificacion = $request->tipo_ide;
      $radicado->num_identificacion  = $request->numero_ide;
      $radicado->nombres             = $request->nombres;
      $radicado->apellidos           = $request->apellidos;
      $radicado->nomina              = $request->nomina; 
      $radicado->direccion           = $request->direccion; 
      $radicado->correo              = $request->email;
      $radicado->telefono            = $request->telefono;
      $radicado->celular             = $request->celular;
      $radicado->whatsapp            = $request->whatsapp;
      $radicado->agencia             = $request->agencia;
      $radicado->mensaje             = $request->mensaje;
      $radicado->fecha               = $fecha_hora->format('Y-m-d');
      $radicado->hora                = $fecha_hora->format('H:i:s');
      $radicado->estado              = 1;
      $radicado->conforme            = 0;
      $radicado->fecha_conforme      = null;
      
      
      if($radicado->save()){

        $files = $request->file('soporte');
        if(isset($files)){
          foreach($files as $file){
            
            $soporte = new Soporte;

            $soporte->radicado    = $radicado->id;
            $file_name            = $radicado->id.'_'.$file->getClientOriginalName();
            $soporte->archivo_url = 'soportes/'.$file_name;
            $soporte->nombre      = $file_name;
            $soporte->tipo        = 1;
            $soporte->fecha       = $fecha_hora; 
            $file->move(public_path().'/soportes/', $file_name);
            $soporte->save();
          }
        }

        $info = DB::select("
          SELECT correo, nombre_agencia, (SELECT nombre FROM des_solic WHERE id = $request->tipo_solicitud) AS solicitud 
          FROM agencia 
          WHERE cu = $request->agencia 
        ");

        $radicado  = $radicado->id;
        $nombres   = $request->nombres;
        $apellidos = $request->apellidos;  
        $nomina    = $request->nomina;
        $mensaje   = $request->mensaje; 

        $age_dpto = Agencia::select('correo')->where('cu', $request->agencia)->get();

        $credenciales = ["pqrs@coopserp.com", $age_dpto[0]->correo];

        Mail::to($request->email)->send(new pqrs_user($radicado, $nombres, $apellidos));
        Mail::to($credenciales)->send(new pqrs($info, $radicado, $nombres, $apellidos, $nomina, $mensaje));

        return back()->with('msj', $radicado);
      }
      return back()->with('msj-error', 'Problema al registrar PQRS');
    }catch (ValidationException $e) { 
      $this->assertSame($exception, $e); 
    }
  }

  public function consultar(){
    return view('consultar');
  }

  public function consultar_pqrs($data){

    $fecha_hora   = new DateTime();
    $fecha_actual = $fecha_hora->format('Y-m-d');

    $datos = explode(",", $data);

    $cedula = $datos[0];
    $pqrs   = $datos[1];

    $cons_cedula = Radicado::where('num_identificacion', $cedula)->exists();

    if ($cons_cedula == 1) {
      
      $con_pqrs = Radicado::select('num_identificacion', 'id')
        ->where('num_identificacion', $cedula)
        ->where('id', $pqrs)
        ->exists();

      if ($con_pqrs == 1) {
        $datos = DB::select("
          SELECT  des_solic.nombre AS tipo, 
                  agencia.nombre_agencia AS agencia, 
                  IFNULL(RE.fecha , radicado.fecha) AS fecha_rec, 
                  TIMESTAMPDIFF(DAY, '$fecha_actual', DATE_ADD(IFNULL(RE.fecha , radicado.fecha), INTERVAL des_solic.tiempo DAY)) AS dias_vence, 
                  DATE_ADD(IFNULL(RE.fecha , radicado.fecha), INTERVAL des_solic.tiempo DAY) AS fecha_limite, 
                  num_identificacion, celular, radicado.correo, mensaje, radicado.estado, conforme, radicado.nombres, radicado.apellidos, radicado.agencia AS num_age
          FROM radicado
          INNER JOIN des_solic ON radicado.tipo_solicitud = des_solic.id
          INNER JOIN agencia ON radicado.agencia = agencia.cu
          LEFT JOIN (
            SELECT pqrs, fecha, hora
            FROM respuesta
            WHERE id = (SELECT MAX(id) FROM respuesta WHERE pqrs = $pqrs) 
          ) AS RE ON radicado.id = RE.pqrs
          WHERE radicado.id = $pqrs
        ");

        $soportes = DB::select("
          SELECT archivo_url, nombre, respuesta_id
          FROM soporte
          WHERE radicado = $pqrs AND respuesta_id IS NULL
        ");

        $respuestas = DB::select("
          SELECT respuesta.id, (IFNULL(U.nombre, (SELECT nombres FROM radicado WHERE num_identificacion = $cedula))) AS nombre , (IFNULL(U.apellido, (SELECT apellidos FROM radicado WHERE num_identificacion = $cedula))) AS apellido, respuesta.fecha, respuesta.hora, respuesta.mensaje
          FROM respuesta 
          LEFT JOIN (
            SELECT nombre, apellido, cedula
            FROM usuario 
          ) AS U ON respuesta.usuario = U.cedula
          WHERE pqrs = $pqrs
        ");

        $soportes_resp = DB::select("
          SELECT archivo_url, nombre, respuesta_id
          FROM soporte
          WHERE radicado = $pqrs AND respuesta_id IS NOT NULL
        ");

        return Response::Json(['status' => 200, 'datos' => $datos, 'soportes' => $soportes, 'respuestas' => $respuestas, 'soportes_resp' => $soportes_resp]);
      }
      return Response::Json(['status' => 400, 'msj_error' => 'El número del PQRS no es correcto.']);
    }
    return Response::Json(['status' => 400, 'msj_error' => 'El número de identificacion ingresado no tiene registros de PQRS en el sistema.']);
  }

  public function conforme(Request $request){

    $fecha_hora   = new DateTime();
    $fecha_actual = $fecha_hora->format('Y-m-d H:m:s');

    $radicado = Radicado::where('id', $request->pqrs)
      ->update(['estado' => 2, 'conforme' => 1, 'fecha_conforme' => $fecha_actual]);

    $correos = Radicado::selectRaw('correo AS correo_rem, (SELECT correo FROM agencia AS A WHERE A.cu = agencia) AS correo_age')
      ->where('id', $request->pqrs)
      ->get();

    $pqrs = $request->pqrs;

    Mail::to($correos[0]->correo_rem)->send(new pqrs_fin_user($pqrs));
    Mail::to($correos[0]->correo_age)->send(new pqrs_fin_master($pqrs));

    return Response::Json(['status' => 200]);
  }

}
