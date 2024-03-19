<?php

namespace App\Http\Controllers;

use App\Mail\resp_user;
use App\Mail\resp_master;

use App\Models\Agencia;
use App\Models\Radicado;
use App\Models\Des_Estado;
use App\Models\Respuesta;
use App\Models\Soporte;

use App\Services\PayUService\Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use Response;
use DateTime;
use DB;
date_default_timezone_set('America/Bogota');

class gestionController extends Controller
{
  public function informacion(Request $request, $id){
    
    $fecha_hora   = new DateTime();
    $fecha_actual = $fecha_hora->format('Y-m-d');

    $pqrs = DB::select("
      SELECT  des_solic.nombre AS tipo, 
              agencia.nombre_agencia AS agencia, 
              IFNULL(RE.fecha , radicado.fecha) AS fecha_rec, 
              TIMESTAMPDIFF(DAY, '$fecha_actual', DATE_ADD(IFNULL(RE.fecha , radicado.fecha), INTERVAL des_solic.tiempo DAY)) AS dias_vence, 
              DATE_ADD(IFNULL(RE.fecha , radicado.fecha), INTERVAL des_solic.tiempo DAY) AS fecha_limite, 
              num_identificacion, celular, radicado.correo, mensaje, radicado.estado, conforme
      FROM radicado
      INNER JOIN des_solic ON radicado.tipo_solicitud = des_solic.id
      INNER JOIN agencia ON radicado.agencia = agencia.cu
      LEFT JOIN (
        SELECT pqrs, fecha, hora
        FROM respuesta
        WHERE id = (SELECT MAX(id) FROM respuesta WHERE pqrs = $id) 
      ) AS RE ON radicado.id = RE.pqrs
      WHERE radicado.id = $id
    ");

    $soportes = DB::select("
      SELECT archivo_url, nombre, respuesta_id
      FROM soporte
      WHERE radicado = $id AND respuesta_id IS NULL
    ");

$respuestas = DB::select("
    SELECT respuesta.id, 
        (IFNULL(U.nombre, (SELECT nombres FROM radicado WHERE num_identificacion = respuesta.usuario LIMIT 1))) AS nombre, 
        (IFNULL(U.apellido, (SELECT apellidos FROM radicado WHERE num_identificacion = respuesta.usuario LIMIT 1))) AS apellido, 
        respuesta.fecha, respuesta.hora, respuesta.mensaje
    FROM respuesta 
    LEFT JOIN (
        SELECT nombre, apellido, cedula
        FROM usuario 
    ) AS U ON respuesta.usuario = U.cedula
    WHERE pqrs = $id
");


    $soportes_resp = DB::select("
      SELECT archivo_url, nombre, respuesta_id
      FROM soporte
      WHERE radicado = $id AND respuesta_id IS NOT NULL
    ");

    return Response::Json(['status' => true, 'datos' => $pqrs, 'soportes' => $soportes, 'respuestas' => $respuestas, 'soportes_resp' => $soportes_resp]);
  }

  public function respuesta(Request $request){

    try {
      $datos = request()->validate([
        'mensaje' => 'required'
      ],[
        'required' => 'Este campo es obligatorio.'
      ]);

      $fecha_hora   = new DateTime();
      $fecha        = $fecha_hora->format('Y-m-d');
      $hora         = $fecha_hora->format('H:i:s');

      $respuesta = new Respuesta;

      $respuesta->usuario = $request->usuario;
      $respuesta->fecha   = $fecha;
      $respuesta->hora    = $hora;
      $respuesta->pqrs    = $request->pqrs;
      $respuesta->mensaje = $request->mensaje;
      
      if($respuesta->save() && !empty($request->file('soporte'))){

        $file = $request->file('soporte');

        for($i = 0; $i < sizeof($file); $i++){
          $soporte = new Soporte;
          $soporte->radicado     = $request->pqrs;
          $soporte->respuesta_id = $respuesta->id;
          $file_name             = $respuesta->id.'_'.$file[$i]->getClientOriginalName();
          $soporte->archivo_url  = 'soportes/'.$file_name;
          $soporte->nombre       = $file_name;
          $soporte->tipo         = 1;
          $soporte->fecha        = $fecha_hora->format('Y-m-d H:i:s'); 
          $file[$i]->move(public_path().'/soportes/', $file_name);
          $soporte->save();      
        }
      }else{
        $soportes_resp = [];
      }

      $respuestas = DB::select("
        SELECT respuesta.id, IFNULL(usuario.nombre, 'Tu') AS nombre, IFNULL(usuario.apellido, ' ') AS apellido, respuesta.fecha, respuesta.hora, respuesta.mensaje, respuesta.usuario
        FROM respuesta
        LEFT JOIN usuario ON respuesta.usuario = usuario.cedula
        WHERE pqrs = $request->pqrs AND id = $respuesta->id;
      ");

      $soportes_resp = DB::select("
        SELECT archivo_url, nombre, respuesta_id
        FROM soporte
        WHERE radicado = $request->pqrs AND respuesta_id IS NOT NULL
      ");

      if ($request->cedula == 0) {
        $usuario = DB::select("
          SELECT nombres, apellidos, correo
          FROM radicado
          WHERE id = ".$request->pqrs."
        ");
        $pqrs = $request->pqrs;
        Mail::to($usuario[0]->correo)->send(new resp_user($usuario, $pqrs));
      }else{
        $usuario = DB::select("
          SELECT nombre AS nombres, apellido AS apellidos, agencia.correo
          FROM usuario
          JOIN agencia ON usuario.agencia = agencia.cu
          WHERE agencia = ".$request->cedula."
        ");

        $remitente = DB::select("
          SELECT nombres, apellidos, num_identificacion
          FROM radicado
          WHERE id = ".$request->pqrs."
        ");
        $pqrs = $request->pqrs;

        $credenciales = [$usuario[0]->correo, "pqrs@coopserp.com"];

        Mail::to($credenciales)->send(new resp_master($usuario, $pqrs, $remitente));
      }

      return Response::Json(['status' => true, 'respuestas' => $respuestas, 'soportes_resp' => $soportes_resp]);
    }catch (ValidationException $e) { 
      $this->assertSame($exception, $e); 
    }
  }
}
