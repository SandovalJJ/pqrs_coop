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
        <h4 align="center" style="color:green">TOTAL TRASLADOS</h4>
        <div class="col-md-12 cont_primary" style="margin-top:5%">
          <div class="card-body" style="margin-bottom: 15%;padding: 3%;box-shadow: 0px 0px 7px 0px #145469; color:black; overflow: auto;">
            <table id="pqrs_table" class="cell-border" style="width:100%; padding: 1%;color: #111 !important; font-size: 15px;">
              <thead align="center" style="background: #046f7e;color: white;">
                <tr>
                  <th>Radicado</th>
                  <th>Agencia Anterior</th>
                  <th></th>
                  <th>Agencia Nueva</th>
                  <th>Fecha Traslado</th>
                </tr>
              </thead>
              <tbody align="center">
                @foreach($reenviados as $dato)
                  <tr>
                    <td>
                      <div class="badge bg-primary text-wrap" style="width: 4rem;font-size: 19px;">
                        {!! $dato->radicado !!}
                      </div>
                    </td>
                    <td>{!! $dato->ag_anterior !!}</td>
                    <td>
                      <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" color="#005e56" fill="currentColor" class="bi bi-forward-fill" viewBox="0 0 16 16">
                        <path d="m9.77 12.11 4.012-2.953a.647.647 0 0 0 0-1.114L9.771 5.09a.644.644 0 0 0-.971.557V6.65H2v3.9h6.8v1.003c0 .505.545.808.97.557z"/>
                      </svg>
                    </td>
                    <td>{!! $dato->ag_nueva !!}</td>
                    <td>{!! $dato->fecha_traslado !!}</td>
                  </tr>
                @endForeach
              </tbody>
            </table>
          </div>
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

  <script>
    $(document).ready(function() {
      $('#pqrs_table').DataTable(
        {
          "processing": true,
          "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
          "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
          },
          "order": [[ 4, "desc" ]]
        }
      );
    });
  </script>
