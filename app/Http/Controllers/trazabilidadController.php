<?php

namespace App\Http\Controllers;

use DB;
use Response;
use DateTime;

use App\Mail\pqrs;
use App\Mail\trazabilidad_remitente;
use App\Mail\trazabilidad_destino;

use App\Models\Reenviado;
use App\Models\Radicado;
use App\Models\Agencia;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
date_default_timezone_set('America/Bogota');

class trazabilidadController extends Controller
{
  public function index(){

    $reenviados = DB::select("
      SELECT id, radicado, 
        (SELECT nombre_agencia FROM agencia where cu = ag_anterior) AS ag_anterior, 
        (SELECT nombre_agencia FROM agencia where cu = ag_nueva) AS ag_nueva, fecha_traslado
      FROM reenviado 
      ORDER BY id DESC
    ");
    
    return view('panel.trazabilidad', compact('reenviados'));
  }

  public function info_traslado($pqrs){

    $info_pqrs = DB::select("
      SELECT (SELECT nombre FROM des_solic WHERE tipo_solicitud = id) AS tipo_sol, (SELECT nombre_agencia FROM agencia WHERE cu = radicado.agencia) AS agencia, radicado.agencia AS cu
      FROM radicado
      WHERE id = $pqrs;
    ");

    return Response::Json(['info_pqrs' => $info_pqrs]);
  }

  public function trasladar(Request $request){

    $fecha_hora   = new DateTime();
    $fecha_actual = $fecha_hora->format('Y-m-d H:i:s');

    $radicado = Radicado::where('id', $request->pqrs)
      ->update(['agencia' => $request->ag_dpto_new, 'correo' => $request->correo]);

    $reenviado = new Reenviado;

    $reenviado->radicado       = $request->pqrs;
    $reenviado->ag_anterior    = $request->ag_dpto;
    $reenviado->ag_nueva       = $request->ag_dpto_new;
    $reenviado->fecha_traslado = $fecha_actual;
    
    if($reenviado->save()){

      $pqrs    = $request->pqrs;
      $age_ant = Agencia::select('nombre_agencia', 'correo')->where('cu', $request->ag_dpto)->get(); 
      $age_new = Agencia::select('nombre_agencia', 'correo')->where('cu', $request->ag_dpto_new)->get(); 

      Mail::to($age_ant[0]->correo)->send(new trazabilidad_remitente($pqrs, $age_ant, $age_new));
      Mail::to($age_new[0]->correo)->send(new trazabilidad_destino($pqrs, $age_ant, $age_new));
    }

    return Response::Json(['status', 200]);
  }
}
