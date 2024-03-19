<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.dataTables.min.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Inicio</title>
  @include('layout.head')
</head>
<body>
  <style type="text/css">
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
      background: linear-gradient(to bottom, #fbf6f6 0%, #f1eeee 100%) !important;
      border: 1px solid #ebe2e2;
    }
  </style>
  <div id="wrapper">
    @include('layout.nav')
    @include('layout.sidebar')
    <div id="page-content-wrapper" style="padding: 2%;">
      <button type="button" class="hamburger animated fadeInLeft is-closed" data-toggle="offcanvas">
        <span class="hamb-top"></span>
        <span class="hamb-middle"></span>
        <span class="hamb-bottom"></span>
      </button>
      <div class="container" style="background:white;padding: 2%;">
        @php
          date_default_timezone_set('America/Bogota');
          $fecha_hora = new DateTime();
          $año_actual = $fecha_hora->format('Y');
        @endphp
        <h3 align="center" style="color:green">TOTAL PQRS <strong id="title_anno">AÑO {!! $año_actual !!}</strong></h3>
        <br>
        <div class="row">
          @if(session()->get('perfil') == 1)
            <div class="col-md-6 col-12">
              <select id="agencia" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                <option selected disabled value="0">Selecciona la agencia</option>
                @foreach($agencias as $agencia)
                  <option value="{!! $agencia->cu !!}">{!! $agencia->nombre_agencia !!}</option>
                @endforeach
              </select>
            </div>
          @endIf
          <div class="col-md-6 col-12">
            <select id="anno" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
              <option selected disabled value="0">Selecciona el año</option>
              @for($año = 2020; $año <= $año_actual; $año++)
                <option value="{!! $año !!}">{!! $año !!}</option>
              @endfor
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-3 mb-3 mb-sm-0">
            <div class="card text-bg-primary">
              <div class="card-header" style="font-size: 50px;text-align: center;">P</div>
              <div class="card-body" align="center">
                <h5 class="card-title" id="pet_total">PETICIONES:
                  {!! $peticiones[0]->total + $peticiones[1]->total !!}
                </h5>
                <span class="card-text" id="pet_resp">Respondidos: {!! $peticiones[1]->total !!}</span> | 
                <span class="card-text" id="pet_sresp">Sin Responder: {!! $peticiones[0]->total !!}</span>
              </div>
            </div>
          </div>
          <div class="col-sm-3 mb-3 mb-sm-0">
            <div class="card text-bg-danger">
              <div class="card-header" style="font-size: 50px;text-align: center;">Q</div>
              <div class="card-body" align="center">
                <h5 class="card-title" id="que_total">QUEJAS:
                  {!! $quejas[0]->total + $quejas[1]->total !!}
                </h5>
                <span class="card-text" id="que_resp">Respondidos: {!! $quejas[1]->total !!}</span> | 
                <span class="card-text" id="que_sresp">Sin Responder: {!! $quejas[0]->total !!}</span>
              </div>
            </div>
          </div>
          <div class="col-sm-3 mb-3 mb-sm-0">
            <div class="card text-bg-warning">
              <div class="card-header" style="font-size: 50px;text-align: center;">R</div>
              <div class="card-body" align="center">
                <h5 class="card-title" id="rec_total">RECLAMOS:
                  {!! $reclamos[0]->total + $reclamos[1]->total !!}
                </h5>
                <span class="card-text" id="rec_resp">Respondidos: {!! $reclamos[1]->total !!}</span> | 
                <span class="card-text" id="rec_sresp">Sin Responder: {!! $reclamos[0]->total !!}</span>
              </div>
            </div>
          </div>
          <div class="col-sm-3 mb-3 mb-sm-0">
            <div class="card text-bg-success">
              <div class="card-header" style="font-size: 50px;text-align: center;">S</div>
              <div class="card-body" align="center">
                <h5 class="card-title" id="sug_total">SUGERENCIAS:
                  {!! $sugerencias[0]->total + $sugerencias[1]->total !!}
                </h5>
                <span class="card-text" id="sug_resp">Respondidos: {!! $sugerencias[1]->total !!}</span> | 
                <span class="card-text" id="sug_sresp">Sin Responder: {!! $sugerencias[0]->total !!}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12 cont_primary" style="margin-top:5%">
          <div class="card-body" style="margin-bottom: 15%;padding: 3%;box-shadow: 0px 0px 7px 0px #145469; color:black; overflow: auto;">
            <table id="pqrs_table" class="cell-border" style="width:100%; padding: 1%;color: #111 !important; font-size: 15px;">
              <thead align="center" style="background: #046f7e;color: white;">
                <tr>
                  <th>#</th>
                  <th>TIPO</th>
                  <th>AGENCIA</th>
                  <th>RECIBIDO</th>
                  <th>CEDULA</th>
                  <th>NOMBRES Y APELLIDOS</th>
                  <th>FECHA LIMITE</th>
                  <th>ESTADO</th>
                  <th>OPCIONES</th>
                </tr>
              </thead>
              <tbody align="center">
                @foreach($table_pqrs as $dato)
                    <tr>
                      <td>{!! $dato->id !!}</td>
                      <td>
                        @if($dato->tipo_sol == "Petición")
                          <span style="width:100%" class="btn text-bg-primary">{!! $dato->tipo_sol !!}</span>
                        @elseIf($dato->tipo_sol == "Queja")
                          <span style="width:100%" class="btn text-bg-danger">{!! $dato->tipo_sol !!}</span>
                        @elseIf($dato->tipo_sol == "Reclamo")
                          <span style="width:100%" class="btn text-bg-warning">{!! $dato->tipo_sol !!}</span>
                        @else
                          <span style="width:100%" class="btn text-bg-success">{!! $dato->tipo_sol !!}</span>
                        @endIf
                      </td>
                      <td>{!! $dato->nom_age !!}</td>
                      <td>{!! $dato->fecha !!}</td>
                      <td>{!! $dato->num_identificacion !!}</td>
                      <td>{!! $dato->nombres !!} {!! $dato->apellidos !!}</td>
                      <td id="fecha_lim_td_{!! $dato->id !!}">
                          @if($dato->estado == 2)
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16" color="green">
                              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                              <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                            </svg>
                          @elseIf($dato->tiempo >= 0)
                            @if(sizeOf($respuestas) != 0)
                              @foreach($respuestas as $respuesta)
                                @php
                                  $i = 0;
                                  $p = 0;
                                @endphp
                                @if($respuesta->id_pqrs == $dato->id && $respuesta->total != 0)
                                  @php
                                   $p = 1;
                                  @endphp
                                  <div class="intermitente" style="background: #3eaec5;color: white;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
                                      <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                      <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                                    </svg> Respuesta Enviada 
                                  </div>
                                @else
                                  @php
                                    $i = $i+1;
                                  @endphp
                                @endIf
                              @endForeach 
                              @if($i == 1 && $p != 1)
                               <div class="intermitente">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
                                  <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                  <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                                 </svg> {!! $dato->tiempo !!} Dias
                               </div>
                              @endIf
                            @else
                              <div class="intermitente">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
                                  <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                  <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                                </svg> {!! $dato->tiempo !!} Dias
                              </div>
                            @endIf
                          @else
                            <div class="intermitente" style="background: #dc3545;color: white;">
                              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-envelope-paper" viewBox="0 0 16 16">
                                <path d="M4 0a2 2 0 0 0-2 2v1.133l-.941.502A2 2 0 0 0 0 5.4V14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5.4a2 2 0 0 0-1.059-1.765L14 3.133V2a2 2 0 0 0-2-2H4Zm10 4.267.47.25A1 1 0 0 1 15 5.4v.817l-1 .6v-2.55Zm-1 3.15-3.75 2.25L8 8.917l-1.25.75L3 7.417V2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v5.417Zm-11-.6-1-.6V5.4a1 1 0 0 1 .53-.882L2 4.267v2.55Zm13 .566v5.734l-4.778-2.867L15 7.383Zm-.035 6.88A1 1 0 0 1 14 15H2a1 1 0 0 1-.965-.738L8 10.083l6.965 4.18ZM1 13.116V7.383l4.778 2.867L1 13.117Z"/>
                              </svg> Remitido
                            </div>
                          @endIf
                      </td>
                      <td>
                        @if($dato->estado == 2)
                          <div class="btn text-bg-success">Finalizado</div>
                        @elseIf($dato->tiempo >= 0)
                          <div class="btn text-bg-warning">Pendiente</div>
                        @else
                          <div class="btn text-bg-danger">Gerencia</div>
                        @endIf
                      </td>
                      <td>
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                            <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                          </svg>
                        </button>
                        <ul class="dropdown-menu">
                          <li>
                            <button class="dropdown-item" onclick="ver({!! $dato->id !!})">
                              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" color="blue" class="bi bi-eye" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                              </svg> Ver
                            </button>
                          </li>
                          @if($dato->estado != 2)
                              <li>
                                <button class="dropdown-item" onclick="ver({!! $dato->id !!})">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16" color="green">
                                    <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                    <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                                  </svg> Responder
                                </button>
                              </li>
                          @endIf
                            <li>
                              <button class="dropdown-item" onclick="reenviar({!! $dato->id !!})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-reply-all" viewBox="0 0 16 16">
                                  <path d="M8.098 5.013a.144.144 0 0 1 .202.134V6.3a.5.5 0 0 0 .5.5c.667 0 2.013.005 3.3.822.984.624 1.99 1.76 2.595 3.876-1.02-.983-2.185-1.516-3.205-1.799a8.74 8.74 0 0 0-1.921-.306 7.404 7.404 0 0 0-.798.008h-.013l-.005.001h-.001L8.8 9.9l-.05-.498a.5.5 0 0 0-.45.498v1.153c0 .108-.11.176-.202.134L4.114 8.254a.502.502 0 0 0-.042-.028.147.147 0 0 1 0-.252.497.497 0 0 0 .042-.028l3.984-2.933zM9.3 10.386c.068 0 .143.003.223.006.434.02 1.034.086 1.7.271 1.326.368 2.896 1.202 3.94 3.08a.5.5 0 0 0 .933-.305c-.464-3.71-1.886-5.662-3.46-6.66-1.245-.79-2.527-.942-3.336-.971v-.66a1.144 1.144 0 0 0-1.767-.96l-3.994 2.94a1.147 1.147 0 0 0 0 1.946l3.994 2.94a1.144 1.144 0 0 0 1.767-.96v-.667z"/>
                                  <path d="M5.232 4.293a.5.5 0 0 0-.7-.106L.54 7.127a1.147 1.147 0 0 0 0 1.946l3.994 2.94a.5.5 0 1 0 .593-.805L1.114 8.254a.503.503 0 0 0-.042-.028.147.147 0 0 1 0-.252.5.5 0 0 0 .042-.028l4.012-2.954a.5.5 0 0 0 .106-.699z"/>
                                </svg> Reenviar
                              </button>
                            </li>
                        </ul>
                      </td>
                    </tr>
                @endForeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal ver -->
  <div class="modal fade" id="modal_info" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" style="background: snow;">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="title_radicado"></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5 align="center">INFORMACIÒN DEL RADICADO</h5>
          <br>
          <div class="row">
            <div class="col-md-6 col-12">TIPO: <strong id="tipo"></strong></div>
            <div class="col-md-6 col-12">DPTO/AGENCIA: <strong id="dept_agen"></strong></div>
          </div>
          <div class="row">
            <div class="col-md-6 col-12">DIAS PARA VENCER: <strong id="dias_vence"></strong></div>
            <div class="col-md-6 col-12">FECHA RECIBIDO: <strong id="fecha_rec"></strong></div>
          </div>
          <div class="row">
            <div class="col-md-6 col-12">LIMITE RESPUESTA: <strong id="fecha_limite"></strong></div>
            <div class="col-md-6 col-12">CEDULA: <strong id="cedula"></strong></div>
          </div>
          <div class="row">
            <div class="col-md-6 col-12">CELULAR: <strong id="celular"></strong></div>
            <div class="col-md-6 col-12">CORREO: <strong id="correo"></strong></div>
          </div>
        </div>
        <div class="card" style="width: 96%;margin:2%;">
          <div class="card-body" style="padding-bottom: 0;">
            <h5 class="card-title">ANEXO</h5>
            <p class="card-text" id="mensaje"></p>
            <p id="soportes" style="padding-bottom: 0;"></p>
          </div>
          <ul class="list-group list-group-flush" id="respuestas" style="margin: 2%;"></ul>
          <ul class="list-group list-group-flush" id="new_resp" style="margin: 2%;"></ul>
          <div class="list-group-item" id="cont_form_resp" style="display: none; padding:1%; background: gainsboro;">
            <form id="form-resp" enctype="multipart/form-data">
              <input name="pqrs" id="pqrs" hidden>
              <textarea class="form-control" row="5" name="mensaje" id="mensaje" placeholder="Escribe aqui tu respuesta" required></textarea>
              <input name="soporte[]" id="soporte" class="form-control" type="file" multiple>
              <br>
              <button type="submit" class="btn btn-primary" id="responder">RESPONDER</button>
            </form>
          </div>
          <div id="mjs_finalizado" style="padding: 0% 2% 0% 2%;"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_reenviar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Trasladar PQRS</h1>
          <button id="btn_close" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="grid gap-3">
            <div class="row alert alert-secondary" style="margin:1%">
              <div class="col-6">N° Radicado: <strong id="radicado"></strong></div>
              <div class="col-6">Tipo: <strong id="tipo_soli"></strong></div>
            </div>
            <br>
            <div class="row">
              <div class="col-5" style="margin:auto;text-align: center;">
                <label id="ag_dpto">HOLA</label>
                <input name="cu_ag_dpto" id="cu_ag_dpto" value="" hidden>
              </div>
              <div class="col-1" style="margin:auto;">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" color="#005e56" fill="currentColor" class="bi bi-forward-fill" viewBox="0 0 16 16">
                  <path d="m9.77 12.11 4.012-2.953a.647.647 0 0 0 0-1.114L9.771 5.09a.644.644 0 0 0-.971.557V6.65H2v3.9h6.8v1.003c0 .505.545.808.97.557z"/>
                </svg>
              </div>
              <div class="col-6">
                <select class="form-select" aria-label="Default select example" id="select_age">
                  <option disabled value="0" selected>Selecciona la Agencia o Departamento</option>
                  @foreach($agencias as $agencia)
                    <option value="{!! $agencia->cu !!}">{!! mb_strtoupper($agencia->nombre_agencia) !!}</option>
                  @endForeach
                </select>
              </div>
            </div>
            <br>
            <input class="form-control" type="email" name="email" id="email" value="" placeholder="Verifica que el email sea correcto" disabled>
          </div>
        </div>
        <div class="modal-footer" id="footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary trasladar">Trasladar</button>
        </div>
      </div>
    </div>
  </div>

  @include('layout.footer')

  <!-- Page level plugins -->
  <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

  <!-- Page level custom scripts -->
  <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>

  <script type="text/javascript">

    $(document).ready(function() {
      $('#pqrs_table').DataTable(
        {
          "processing": true,
          "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
          "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
          },
          "order": [[ 0, "desc" ]]
        }
      );
    });

    $("#agencia, #anno").change(function () {
      agencia = $('#agencia').val();
      anno    = $('#anno').val();

      document.getElementById('title_anno').innerHTML = 'AÑO '+anno;
      $.get("consulta_general/"+[agencia, anno], function(data) {

        datos = data.datos;
        if(datos.length != 0){
          //Peticiones
          document.getElementById('pet_total').innerHTML = 'RETICIONES: '+datos[0].total_ts; 
          document.getElementById('pet_resp').innerHTML  = 'Respondidos: '+datos[0].resp;
          document.getElementById('pet_sresp').innerHTML = 'Sin Responder: '+datos[0].no_resp;

          //Quejas
          document.getElementById('que_total').innerHTML = 'QUEJAS: '+datos[1].total_ts; 
          document.getElementById('que_resp').innerHTML  = 'Respondidos: '+datos[1].resp;
          document.getElementById('que_sresp').innerHTML = 'Sin Responder: '+datos[1].no_resp;

          //Reclamos
          document.getElementById('rec_total').innerHTML = 'RECLAMOS: '+datos[2].total_ts; 
          document.getElementById('rec_resp').innerHTML  = 'Respondidos: '+datos[2].resp;
          document.getElementById('rec_sresp').innerHTML = 'Sin Responder: '+datos[2].no_resp;

          //Sugerencias
          document.getElementById('sug_total').innerHTML = 'SUGERENCIAS: '+datos[3].total_ts; 
          document.getElementById('sug_resp').innerHTML  = 'Respondidos: '+datos[3].resp;
          document.getElementById('sug_sresp').innerHTML = 'Sin Responder: '+datos[3].no_resp;
        }
      });
    });

    function ver(id){
      document.getElementById('new_resp').style.display = 'none';
      document.getElementById('cont_form_resp').style.display = 'none';
      document.getElementById('mjs_finalizado').style.display = 'none';

      $('#modal_info').modal('show');  
      document.getElementById('title_radicado').innerHTML = "Radicado N° "+id;

      $.get("informacion_pqrs/"+id, function(data) {
        
        datos = data.datos;
        if(datos.length != 0){
          document.getElementById('tipo').innerHTML         = datos[0].tipo; 
          document.getElementById('dept_agen').innerHTML    = datos[0].agencia;
          document.getElementById('dias_vence').innerHTML   = (datos[0].dias_vence < 0) ? 'Ya vencio el plazo' : datos[0].dias_vence;
          document.getElementById('fecha_rec').innerHTML    = datos[0].fecha_rec; 
          document.getElementById('fecha_limite').innerHTML = datos[0].fecha_limite;
          document.getElementById('cedula').innerHTML       = datos[0].num_identificacion;
          document.getElementById('celular').innerHTML      = datos[0].celular; 
          document.getElementById('correo').innerHTML       = datos[0].correo;
          document.getElementById('mensaje').innerHTML      = datos[0].mensaje;
        }
        
        respuestas = data.respuestas;
        if(datos[0].estado != 2 || datos[0].conforme != 1){
          if ((respuestas.length)%2 == 0) {
            document.getElementById('cont_form_resp').style.display = "block";
            var btn_resp = document.querySelector("#pqrs");
            btn_resp.setAttribute("value", `${id}`);
          }else{
            document.getElementById('cont_form_resp').style.display = "none";
          }
        }else{
          document.getElementById('mjs_finalizado').style.display = 'block';
          document.getElementById('mjs_finalizado').innerHTML = '<div class="alert alert-success" role="alert">Esta conversación ha finalizado.</div>'
        }

        soportes_resp = data.soportes_resp
        all_resp = respuestas.map(function(respuesta){
          total_soportes = soportes_resp.map(function(soporte){
            if (soporte.respuesta_id == respuesta.id) {
              return `<li style="margin-left:5%"><a target="_blank" href="{!! asset('${soporte.archivo_url}')!!}" class="card-link">${soporte.nombre}</a><br></li>`
            }
          }).join("");

          return `<hr>
            <div class="list-group-item list-group-item-action" aria-current="true" style="background: whitesmoke;">
              <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1" style="color: green">Respuesta: <span style="color: #5e89c9">${respuesta.nombre} ${respuesta.apellido}<span></h6>
                <small>${respuesta.fecha} / ${respuesta.hora}</small>
              </div>
              <p class="mb-1">${respuesta.mensaje}</p>
              <small>`+total_soportes+`</small>
            </div>`
        });
        document.getElementById('respuestas').innerHTML = all_resp.join("");

        anexo = data.soportes;
        anexo = anexo.map(function(soporte){
          return `<li>
              <a target="_blank" href="{!! asset('${soporte.archivo_url}')!!}" class="card-link">
                ${soporte.nombre}
              </a>
            </li>`
        });
        document.getElementById('soportes').innerHTML = '<div class="card-body" style="padding-bottom: 0;"><ul class="list-group">'+anexo.join('')+'</ul></div>';
      });
    }

    $('#form-resp').on('submit', function(e) {
      // evito que propague el submit
      e.preventDefault();

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      var respuesta = new FormData();
      
       var totalfiles = document.getElementById('soporte').files.length;
       for (var i = 0; i < totalfiles; i++) {
          respuesta.append("soporte[]", document.getElementById('soporte').files[i]);
       }

      correo = $("#correo").val();
      cedula = 0;

      respuesta.append("pqrs", document.getElementById('pqrs').value);
      respuesta.append("mensaje", $('textarea[id=mensaje]').val());
      respuesta.append("usuario", <?php echo session()->get('cedula'); ?>);
      respuesta.append("cedula", cedula);
      
      $.ajax({
        url: "{{ url('/respuesta') }}",
        method: "POST",
        data:respuesta,
        dataType: 'JSON',
        cache:false,
        contentType: false,
        processData: false,
        beforeSend: function() {
          $('#responder').prop('disabled', true);
          document.getElementById('responder').innerHTML = 'Enviando...'
        },
        success: function(data) {
          $('#responder').prop('disabled', false);
          pqrs = document.getElementById('pqrs').value;
          document.getElementById('responder').innerHTML = 'RESPONDER'
          document.getElementById('fecha_lim_td_'+pqrs).innerHTML = `<div class="intermitente" style="background: #3eaec5;color: white;">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
                <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
              </svg> Respuesta Enviada
            </div>`;
          document.getElementById('new_resp').style.display = "block";
          document.getElementById('cont_form_resp').style.display = "none";
          $('textarea[id=mensaje]').val("");
          $('#soporte').val("");
          respuestas = data.respuestas
          soportes_resp = data.soportes_resp
          document.getElementById('new_resp').innerHTML = `<div class="list-group-item list-group-item-action" aria-current="true">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  Mensaje <strong>Enviado!</strong> con exito.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="d-flex w-100 justify-content-between">
                  <h6 class="mb-1" style="color: green">Respuesta: <span style="color: #5e89c9;">${respuestas[0].nombre} ${respuestas[0].apellido}<span></h6>
                  <small>${respuestas[0].fecha} / ${respuestas[0].hora}</small>
                </div>
                <p class="mb-1">${respuestas[0].mensaje}</p>
                <small><strong>SOPORTES:</strong>`+
                  soportes_resp.map(function(soporte){
                    if (soporte.respuesta_id == respuestas[0].id) {
                      return `<li style="margin-left:5%"><a target="_blank" href="{!! asset('${soporte.archivo_url}')!!}" class="card-link">${soporte.nombre}</a><br></li>`
                    }
                  }).join("");
                +`</small>
                </div>` 
        },
        error: function(response) {
          console.log(response);
        },
      });
    });  


    function reenviar(pqrs){

      $("#select_age").change(function(){
        agencias = <?php echo json_encode($agencias); ?>;
        for(i = 0; i < agencias.length; i++){
          if (agencias[i].cu == this.value) {
            document.getElementById('email').value = agencias[i].correo
            document.getElementById('email').disabled = false;
            return 
          }
        }
      });

      $.get("trasladar/"+pqrs, function(data) {
        info = data.info_pqrs;
        document.getElementById('radicado').innerHTML = pqrs;
        document.getElementById('tipo_soli').innerHTML = info[0].tipo_sol;
        document.getElementById('ag_dpto').innerHTML = info[0].agencia;
        document.getElementById('cu_ag_dpto').value = info[0].cu;
      });

      $('#modal_reenviar').modal('show');

      $(".trasladar").click(function(){
       
        ag_dpto     = $("#cu_ag_dpto").val();
        radicado    = pqrs;
        ag_dpto_new = $("#select_age").val();
        correo      = $("#email").val();

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    
        $.ajax({
          url: "{{ url('/trasladar') }}",
          method: "POST",
          data:{
            pqrs: pqrs,
            ag_dpto: ag_dpto,
            ag_dpto_new: ag_dpto_new,
            correo: correo
          },
          dataType: 'JSON',
          cache:false,
          beforeSend: function() {
            document.getElementById('footer').innerHTML = `<button class="btn btn-primary" type="button" disabled>
              <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Enviando...</button>`
          },
          success: function(data) {
            document.getElementById('footer').innerHTML = `<div class="alert alert-success" role="alert" style="width:100%">El traslado se ha realizado de manera correcta <button type="button" class="btn btn-success" data-bs-dismiss="modal" id="confirmar">OK</button></div>`;
            document.getElementById('btn_close').style.display = "none";

            $('#confirmar').click(function(){
              location.reload();
            })
          },
          error: function(response) {
            console.log(response);
          },
        });
      });
    }
  </script>
</body>
</html>