<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>CONSULTAR PQRS</title>
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css')}}">
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>
<body style="background: #eee;">
  <div class="container-sm" style="margin-top: 10%;">
    <div class="row">
      <div class="col-md-6 col-12" style="margin:auto; background: white;padding: 0%;">
        <div class="card-header" style="background: #005e56;color: white;padding: 3%;">
          <h1>CONSULTAR PQRS</h1>
          <p style="text-align:justify;">Por favor digite su numero de cedula y el numero de radicado para conocer la respuesta a su solicitud.</p>
        </div>
        
        <br>
        <div class="container text-center">
          <div class="col-12" style="text-align:center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16" color="#005e56">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
          </div>
          <br><br>
          <div class="row g-3">
            <div class="col-md-6">
              <input id="numero_ide" name="numero_ide" type="number" class="form-control" placeholder="Número de Identificación">
            </div>
            <div class="col-md-6">
              <input id="codigo_pqrs" name="codigo_pqrs" type="number" class="form-control" placeholder="Codigo de Radicado">
            </div>
            <br>
            <div class="col-md-12" style="text-align: center;">
              <button class="btn btn-primary" style="font-size: 21px;" onclick="consultar_pqrs()">CONSULTAR PQRS</button>
              <br><br>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
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
            <div class="col-md-6 col-12">DIRIGIDO: <strong id="dept_agen"></strong></div>
          </div>
          <div class="row">
            <div class="col-md-6 col-12">ENVIADO: <strong id="fecha_rec"></strong></div>
            <div class="col-md-6 col-12">NOMBRES: <strong id="nombres"></strong></div>
          </div>
          <div class="row">
            <div class="col-md-6 col-12">APELLIDOS: <strong id="apellidos"></strong></div>
            <div class="col-md-6 col-12">CEDULA: <strong id="cedula"></strong></div>
            <input type="number" name="num_age" id="num_age" hidden>
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
              <div id="div_resp">
                <button type="submit" class="btn btn-primary" id="responder">RESPONDER</button>
                <a href="#" id="btn_conforme" class="btn btn-success">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hand-thumbs-up-fill" viewBox="0 0 16 16">
                    <path d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a9.84 9.84 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733.058.119.103.242.138.363.077.27.113.567.113.856 0 .289-.036.586-.113.856-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.163 3.163 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.82 4.82 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/>
                  </svg> ESTOY CONFORME
                </a>
              </div>
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

  <div class="modal fade" tabindex="-1" id="modal_msj">
    <div class="modal-dialog">
      <div class="modal-content text-bg-danger" align="center">
        <div class="modal-body">
          <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-exclamation-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
          </svg>
          <br><br>
          <p id="mensaje_error"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  @include('layout.footer')
  <script type="text/javascript">
    
    function consultar_pqrs(){
      
      document.getElementById('cont_form_resp').style.display = 'none';
      var cedula = $("#numero_ide").val();
      var codigo_pqrs = $("#codigo_pqrs").val();

      $.get("consultar_pqrs/"+[cedula, codigo_pqrs], function(data) {
        status = data.status;
        if (status == 200) {
          datos = data.datos;
          if(datos.length != 0){
            document.getElementById('title_radicado').innerHTML = "Radicado N° "+codigo_pqrs;
            document.getElementById('tipo').innerHTML           = datos[0].tipo; 
            document.getElementById('dept_agen').innerHTML      = datos[0].agencia;
            document.getElementById('nombres').innerHTML        = datos[0].nombres;
            document.getElementById('apellidos').innerHTML      = datos[0].apellidos;
            document.getElementById('fecha_rec').innerHTML      = datos[0].fecha_rec; 
            document.getElementById('cedula').innerHTML         = datos[0].num_identificacion;
            document.getElementById('celular').innerHTML        = datos[0].celular; 
            document.getElementById('correo').innerHTML         = datos[0].correo;
            document.getElementById('mensaje').innerHTML        = datos[0].mensaje;
            document.getElementById('num_age').value            = datos[0].num_age;
          }

          anexo = data.soportes;
          anexo = anexo.map(function(soporte){
            return `<li>
                <a target="_blank" href="{!! asset('${soporte.archivo_url}')!!}" class="card-link">
                  ${soporte.nombre}
                </a>
              </li>`
          });
          document.getElementById('soportes').innerHTML = '<div class="card-body"><ul class="list-group">'+anexo.join('')+'</ul></div>';

          respuestas = data.respuestas;
          if(datos[0].estado != 2 || datos[0].conforme != 1){
            if ((respuestas.length)%2 != 0) {
              document.getElementById('cont_form_resp').style.display = "block";
              var btn_resp = document.querySelector("#pqrs");
              btn_resp.setAttribute("value", `${codigo_pqrs}`);

              var btn_conforme = document.querySelector("#btn_conforme");
              btn_conforme.setAttribute("onclick", `conforme(${codigo_pqrs})`);
              document.getElementById('mjs_finalizado').innerHTML = ''
            }else{
              document.getElementById('cont_form_resp').style.display = "none";
              document.getElementById('mjs_finalizado').innerHTML = ''
            }
          }else{
            document.getElementById('mjs_finalizado').innerHTML = '<div class="alert alert-success" role="alert">Esta conversación ha finalizado de manera exitosa. Gracias por comunicarse con COOPSERP.</div>'
          }
          document.getElementById('new_resp').innerHTML = "";

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
                  <h6 class="mb-1">Respuesta: <span style="color: #5e89c9">${respuesta.nombre} ${respuesta.apellido}<span></h6>
                  <small>${respuesta.fecha} / ${respuesta.hora}</small>
                </div>
                <p class="mb-1" style="color: green">${respuesta.mensaje}</p>
                <small><strong>SOPORTES:</strong>`+total_soportes+`</small>
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

            $('#modal_info').modal('show'); 
        }

        if (status == 400) {
          $("#modal_msj").modal('show');
          document.getElementById("mensaje_error").innerHTML = data.msj_error;
        }

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

      respuesta.append("pqrs", document.getElementById('pqrs').value);
      respuesta.append("mensaje", $('textarea[id=mensaje]').val());
      respuesta.append("usuario", $("#numero_ide").val());
      respuesta.append("cedula", $("#num_age").val());
      
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
          document.getElementById('responder').innerHTML = 'RESPONDER'
          document.getElementById('new_resp').style.display = "block";
          document.getElementById('cont_form_resp').style.display = "none";
          $('textarea[id=mensaje]').val("");
          $('#soporte').val("");
          respuestas = data.respuestas
          soportes_resp = data.soportes_resp
          document.getElementById('new_resp').innerHTML =  `<div class="list-group-item list-group-item-action" aria-current="true">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Enviado!</strong> Respuesta registrada con exito.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="d-flex w-100 justify-content-between">
                  <h6 class="mb-1">Respuesta: <span style="color: #5e89c9;">${respuestas[0].nombre} ${respuestas[0].apellido}<span></h6>
                  <small>${respuestas[0].fecha} / ${respuestas[0].hora}</small>
                </div>
                <p class="mb-1">${respuestas[0].mensaje}</p>
                <small>`+
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

    function conforme(pqrs){
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        url: "{{ url('/conforme') }}",
        method: "POST",
        data:{pqrs: pqrs},
        dataType: 'JSON',
        cache:false,
        beforeSend: function() {
          document.getElementById('div_resp').innerHTML = `<button class="btn btn-primary" type="button" disabled>
            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Enviando...</button>`
        },
        success: function(data) {
          if (data.status == 200) {
            document.getElementById('cont_form_resp').style.display = "none"; 
            document.getElementById('mjs_finalizado').innerHTML = '<div class="alert alert-success" role="alert">Esta conversación ha finalizado de manera exitosa. Gracias por comunicarse con COOPSERP.</div>'
          }
        },
        error: function(response) {
          console.log(response);
        },
      }); 
    }
  </script>
</body>
</html>

