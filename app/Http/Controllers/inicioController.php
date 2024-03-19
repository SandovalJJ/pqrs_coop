<?php

namespace App\Http\Controllers;

use App\Mail\master_conf_auto;
use App\Mail\user_conf_auto;

use App\Models\Agencia;
use App\Models\Radicado;
use App\Models\Des_Estado;
use App\Models\Usuario;
use App\Models\Respuesta;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use Response;
use DateTime;
use DB;
date_default_timezone_set('America/Bogota');

class inicioController extends Controller
{

  public function inicio(){

    $fecha_hora   = new DateTime();
    $fecha_actual = $fecha_hora->format('Y-m-d H:i:s');
    $auth_agencia = session()->get('agencia');

    $respuestas = DB::select("
      SELECT id AS id_pqrs, (SELECT correo FROM agencia AS A WHERE A.cu = agencia) AS correo_age, nombres, apellidos, correo AS remitente, ((respuestas.total)%2) as total, respuestas.id_resp,
      TIMESTAMPDIFF(MINUTE,'$fecha_actual', DATE_ADD(CONCAT(respuestas.fecha,' ',respuestas.hora), INTERVAL 1 DAY)) as minutos 
      FROM radicado
      INNER JOIN (
        SELECT pqrs, COUNT(pqrs) AS total, 
          (SELECT MAX(id) FROM respuesta AS R WHERE R.pqrs = respuesta.pqrs) AS id_resp,
          (SELECT fecha FROM respuesta WHERE id = id_resp) AS fecha,
          (SELECT hora FROM respuesta WHERE id = id_resp) AS hora
        FROM respuesta
        GROUP BY pqrs
        ORDER BY pqrs DESC 
      ) AS respuestas 
      ON radicado.id = respuestas.pqrs
      WHERE estado = 1 AND conforme = 0
      ORDER BY id DESC
    ");

    app('App\Http\Controllers\inicioController')->validador_respuesta($respuestas); 

    $fecha_actual = $fecha_hora->format('Y');

    $agencias = Agencia::select('cu', 'nombre_agencia', 'correo')
      ->orderBy('nombre_agencia')
      ->get();

    if (session()->get('perfil') == 1) {
      $peticiones = DB::select("
        SELECT des_estado.id, descripcion, IFNULL(radicados.total, 0) AS total
        FROM des_estado
        LEFT JOIN (
          SELECT (COUNT(estado)) AS total, estado
          FROM radicado
          WHERE tipo_solicitud = 1 AND YEAR(fecha) >= $fecha_actual
          GROUP BY estado) AS radicados
          ON des_estado.id = radicados.estado");
    }else{
      $peticiones = DB::select("
        SELECT des_estado.id, descripcion, IFNULL(radicados.total, 0) AS total
        FROM des_estado
        LEFT JOIN (
          SELECT (COUNT(estado)) AS total, estado
          FROM radicado
          WHERE tipo_solicitud = 1 AND YEAR(fecha) >= $fecha_actual AND '$auth_agencia' = agencia
          GROUP BY estado) AS radicados
          ON des_estado.id = radicados.estado");
    }
      
    if (session()->get('perfil') == 1) {
      $quejas = DB::select("
      SELECT des_estado.id, descripcion, IFNULL(radicados.total, 0) AS total
      FROM des_estado
      LEFT JOIN (
        SELECT (COUNT(estado)) AS total, estado
        FROM radicado
        WHERE tipo_solicitud = 2 AND YEAR(fecha) >= $fecha_actual
        GROUP BY estado) AS radicados
        ON des_estado.id = radicados.estado");
    }else{
      $quejas = DB::select("
      SELECT des_estado.id, descripcion, IFNULL(radicados.total, 0) AS total
      FROM des_estado
      LEFT JOIN (
        SELECT (COUNT(estado)) AS total, estado
        FROM radicado
        WHERE tipo_solicitud = 2 AND YEAR(fecha) >= $fecha_actual AND '$auth_agencia' = agencia
        GROUP BY estado) AS radicados
        ON des_estado.id = radicados.estado");
    }
    
    if (session()->get('perfil') == 1) {
      $reclamos = DB::select("
        SELECT des_estado.id, descripcion, IFNULL(radicados.total, 0) AS total
        FROM des_estado
        LEFT JOIN (
          SELECT (COUNT(estado)) AS total, estado
          FROM radicado
          WHERE tipo_solicitud = 3 AND YEAR(fecha) >= $fecha_actual
          GROUP BY estado) AS radicados
          ON des_estado.id = radicados.estado");
    }else{
      $reclamos = DB::select("
        SELECT des_estado.id, descripcion, IFNULL(radicados.total, 0) AS total
        FROM des_estado
        LEFT JOIN (
          SELECT (COUNT(estado)) AS total, estado
          FROM radicado
          WHERE tipo_solicitud = 3 AND YEAR(fecha) >= $fecha_actual AND '$auth_agencia' = agencia
          GROUP BY estado) AS radicados
          ON des_estado.id = radicados.estado");
    }

    if (session()->get('perfil') == 1) {
      $sugerencias = DB::select("
        SELECT des_estado.id, descripcion, IFNULL(radicados.total, 0) AS total
        FROM des_estado
        LEFT JOIN (
          SELECT (COUNT(estado)) AS total, estado
          FROM radicado
          WHERE tipo_solicitud = 4 AND YEAR(fecha) >= $fecha_actual
          GROUP BY estado) AS radicados
          ON des_estado.id = radicados.estado");
    }else{
      $sugerencias = DB::select("
        SELECT des_estado.id, descripcion, IFNULL(radicados.total, 0) AS total
        FROM des_estado
        LEFT JOIN (
          SELECT (COUNT(estado)) AS total, estado
          FROM radicado
          WHERE tipo_solicitud = 4 AND YEAR(fecha) >= $fecha_actual AND '$auth_agencia' = agencia
          GROUP BY estado) AS radicados
          ON des_estado.id = radicados.estado");
    }

    $fecha_actual = $fecha_hora->format('Y-m-d');
    if (session()->get('perfil') == 1) {
      $table_pqrs = DB::select("
        SELECT  radicado.id,
                des_solic.nombre AS tipo_sol, 
                agencia.nombre_agencia AS nom_age, 
                radicado.fecha AS fecha, 
                num_identificacion,
                nombres,
                apellidos,
                celular,
                radicado.correo,
                conforme,
                TIMESTAMPDIFF(DAY, '$fecha_actual', DATE_ADD(IFNULL(RE.fecha , radicado.fecha), INTERVAL des_solic.tiempo DAY)) AS tiempo, 
                radicado.estado
        FROM radicado
        INNER JOIN des_solic ON radicado.tipo_solicitud = des_solic.id
        INNER JOIN agencia ON radicado.agencia = agencia.cu
        LEFT JOIN (
          SELECT pqrs, fecha, hora
          FROM respuesta
          WHERE id = (SELECT MAX(id) FROM respuesta)
        ) AS RE ON radicado.id = RE.pqrs
        ORDER BY radicado.id DESC
      ");
    }else{
      $table_pqrs = DB::select("
        SELECT  radicado.id,
                des_solic.nombre AS tipo_sol, 
                agencia.nombre_agencia AS nom_age, 
                radicado.fecha AS fecha, 
                num_identificacion,
                nombres,
                apellidos,
                celular,
                radicado.correo,
                conforme,
                TIMESTAMPDIFF(DAY, '$fecha_actual', DATE_ADD(IFNULL(RE.fecha , radicado.fecha), INTERVAL des_solic.tiempo DAY)) AS tiempo, 
                radicado.estado
        FROM radicado
        INNER JOIN des_solic ON radicado.tipo_solicitud = des_solic.id
        INNER JOIN agencia ON radicado.agencia = agencia.cu
        LEFT JOIN (
          SELECT pqrs, fecha, hora
          FROM respuesta
          WHERE id = (SELECT MAX(id) FROM respuesta)
        ) AS RE ON radicado.id = RE.pqrs
        WHERE '$auth_agencia' = agencia
        ORDER BY radicado.id DESC
      ");
    }
    return view('panel.index', compact('agencias', 'peticiones', 'quejas', 'reclamos', 'sugerencias', 'table_pqrs', 'respuestas'));
  }

  public function consulta_general($data){

    $datos   = explode(",", $data);
    $agencia = $datos[0];
    $anno    = $datos[1];
    $auth_agencia = session()->get('agencia');

    if (empty($agencia) && isset($anno)) {
      if ($auth_agencia == 1) {
        $pqrs = DB::select("
          SELECT abreviatura, IFNULL(R.tipo_sol, 0) AS total_ts, IFNULL(R.resp, 0) AS resp, IFNULL(R.no_resp, 0) AS no_resp
          FROM des_solic
          LEFT JOIN (
            SELECT tipo_solicitud, COUNT(tipo_solicitud) AS tipo_sol,
              COUNT(CASE WHEN estado = 2 THEN 1 END) AS resp,
              COUNT(CASE WHEN estado = 1 THEN 1 END) AS no_resp
            FROM radicado
            WHERE YEAR(fecha) = $anno 
            GROUP BY tipo_solicitud
          ) AS R
          ON des_solic.id = R.tipo_solicitud
        ");
      }else{
        $pqrs = DB::select("
          SELECT abreviatura, IFNULL(R.tipo_sol, 0) AS total_ts, IFNULL(R.resp, 0) AS resp, IFNULL(R.no_resp, 0) AS no_resp
          FROM des_solic
          LEFT JOIN (
            SELECT tipo_solicitud, COUNT(tipo_solicitud) AS tipo_sol,
              COUNT(CASE WHEN estado = 2 THEN 1 END) AS resp,
              COUNT(CASE WHEN estado = 1 THEN 1 END) AS no_resp
            FROM radicado
            WHERE YEAR(fecha) = $anno AND '$auth_agencia' = agencia
            GROUP BY tipo_solicitud
          ) AS R
          ON des_solic.id = R.tipo_solicitud
        ");
      }
      return Response::Json(['status' => true, 'datos' => $pqrs]);
    }

    if (isset($agencia) && empty($anno)) {
      if ($auth_agencia == 1) {
        $pqrs = DB::select("
          SELECT abreviatura, IFNULL(R.tipo_sol, 0) AS total_ts, IFNULL(R.resp, 0) AS resp, IFNULL(R.no_resp, 0) AS no_resp
          FROM des_solic
          LEFT JOIN (
            SELECT tipo_solicitud, COUNT(tipo_solicitud) AS tipo_sol,
              COUNT(CASE WHEN estado = 2 THEN 1 END) AS resp,
              COUNT(CASE WHEN estado = 1 THEN 1 END) AS no_resp
            FROM radicado
            WHERE agencia = $agencia
            GROUP BY tipo_solicitud
          ) AS R
          ON des_solic.id = R.tipo_solicitud
        ");
      }else{
        $pqrs = DB::select("
          SELECT abreviatura, IFNULL(R.tipo_sol, 0) AS total_ts, IFNULL(R.resp, 0) AS resp, IFNULL(R.no_resp, 0) AS no_resp
          FROM des_solic
          LEFT JOIN (
            SELECT tipo_solicitud, COUNT(tipo_solicitud) AS tipo_sol,
              COUNT(CASE WHEN estado = 2 THEN 1 END) AS resp,
              COUNT(CASE WHEN estado = 1 THEN 1 END) AS no_resp
            FROM radicado
            WHERE agencia = $agencia AND '$auth_agencia' = agencia
            GROUP BY tipo_solicitud
          ) AS R
          ON des_solic.id = R.tipo_solicitud
        ");
      }
      return Response::Json(['status' => true, 'datos' => $pqrs]);
    }

    if ($auth_agencia == 1) {
      $pqrs = DB::select("
        SELECT abreviatura, IFNULL(R.tipo_sol, 0) AS total_ts, IFNULL(R.resp, 0) AS resp, IFNULL(R.no_resp, 0) AS no_resp
        FROM des_solic
        LEFT JOIN (
          SELECT tipo_solicitud, COUNT(tipo_solicitud) AS tipo_sol,
            COUNT(CASE WHEN estado = 2 THEN 1 END) AS resp,
            COUNT(CASE WHEN estado = 1 THEN 1 END) AS no_resp
          FROM radicado
          WHERE agencia = $agencia AND YEAR(fecha) = $anno
          GROUP BY tipo_solicitud
        ) AS R
        ON des_solic.id = R.tipo_solicitud
      ");
    }else{
      $pqrs = DB::select("
        SELECT abreviatura, IFNULL(R.tipo_sol, 0) AS total_ts, IFNULL(R.resp, 0) AS resp, IFNULL(R.no_resp, 0) AS no_resp
        FROM des_solic
        LEFT JOIN (
          SELECT tipo_solicitud, COUNT(tipo_solicitud) AS tipo_sol,
            COUNT(CASE WHEN estado = 2 THEN 1 END) AS resp,
            COUNT(CASE WHEN estado = 1 THEN 1 END) AS no_resp
          FROM radicado
          WHERE agencia = $agencia AND YEAR(fecha) = $anno AND '$auth_agencia' = agencia
          GROUP BY tipo_solicitud
        ) AS R
        ON des_solic.id = R.tipo_solicitud
      ");
    }
    return Response::Json(['status' => true, 'datos' => $pqrs]);
  }

  public function validador_respuesta($respuestas){

    foreach($respuestas as $respuesta){
      if ($respuesta->total != 0 && $respuesta->minutos <= 0) {
        Radicado::where('id', $respuesta->id_pqrs)
          ->update(['estado' => 2, 'conforme' => 1]);
        $correos   = ['pqrs@coopserp.com', $respuesta->correo_age];
        $pqrs      = $respuesta->id_pqrs;
        $nombres   = $respuesta->nombres;
        $apellidos = $respuesta->apellidos;
        Mail::to($correos)->send(new user_conf_auto($pqrs, $nombres, $apellidos));
        Mail::to($respuesta->remitente)->send(new master_conf_auto($pqrs, $nombres, $apellidos));  
      }
    }
  }
  
}
