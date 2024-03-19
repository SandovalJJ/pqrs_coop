<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PQRS</title>
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css')}}">
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>

<body style="background: #eee;">
  <div class="container-md" style="margin-top: 2%;">
    <div class="card">
      <div class="card-header" style="background: #005e56;color: white;">
        <h1>PQRS COOPSERP</h1>
        <p style="text-align:justify;">Para nosotros es muy importante su opinión. En procura de mejorar nuestros servicios hemos diseñado este espacio a través de la cual usted podrá registrar sus peticiones, quejas, reclamos y/o sugerencias.</p>
      </div>
      <div class="card-body">
        <br>
        @if(session('msj'))
          <div class="alert alert-success">
            <span>El PQRS se radico con exito. Para poder consultar cualquier novedad por favor ingrese con este codigo <strong>{{ session('msj') }}</strong> al siguiente <a href="{{ url('/consultar')}}"><strong>LINK</strong></a></span>
          </div>
        @endif
        @if(session('msj-error'))
          <div class="alert alert-danger">
            <p>{{ session('msj-error') }}</p>
          </div>
        @endif
        <form class="row g-3" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="col-md-4">
            <select name="tipo_solicitud" class="form-select" required>
              <option selected disabled value="0">Tipo Solicitud</option>
              <option value="1" {{ old('tipo_solicitud') == 1 ? 'selected' : '' }}>Peticion</option>
              <option value="2" {{ old('tipo_solicitud') == 2 ? 'selected' : '' }}>Queja</option>
              <option value="3" {{ old('tipo_solicitud') == 3 ? 'selected' : '' }}>Reclamo</option>
              <option value="4" {{ old('tipo_solicitud') == 4 ? 'selected' : '' }}>Sugerencia</option>
            </select>
            @if($errors->has('tipo_solicitud'))
              <span class="badge text-bg-danger">{!! $errors->first('tipo_solicitud') !!}</span>
            @endIf
          </div>
          <div class="col-md-4">
            <select name="tipo_ide" class="form-select">
              <option selected disabled value="0">Tipo de Identificación</option>
              <option value="1" {{ old('tipo_ide') == 1 ? 'selected' : '' }}>Cedula de Ciudadania</option>
              <option value="2" {{ old('tipo_ide') == 2 ? 'selected' : '' }}>Nit</option>
              <option value="3" {{ old('tipo_ide') == 3 ? 'selected' : '' }}>Tarjeta de identidad</option>
              <option value="4" {{ old('tipo_ide') == 4 ? 'selected' : '' }}>Cedula de exttanjeria</option>
              <option value="5" {{ old('tipo_ide') == 5 ? 'selected' : '' }}>Pasaporte</option>
            </select>
            @if($errors->has('tipo_ide'))
              <span class="badge text-bg-danger">{!! $errors->first('tipo_ide') !!}</span>
            @endIf
          </div>
          <div class="col-md-4">
            <input name="numero_ide" type="number" class="form-control" value="{!! old('numero_ide') !!}" placeholder="Número de Identificación">
            @if($errors->has('numero_ide'))
              <div class="badge text-bg-danger">{!! $errors->first('numero_ide') !!}</div>
            @endIf
          </div>
          <div class="col-md-4">
            <input name="nombres" type="text" class="form-control" value="{!! old('nombres') !!}" placeholder="Nombres">
            @if($errors->has('nombres'))
              <div class="badge text-bg-danger">{!! $errors->first('nombres') !!}</div>
            @endIf
          </div>
          <div class="col-md-4">
            <input name="apellidos" type="text" class="form-control" value="{!! old('apellidos') !!}" placeholder="Apellidos">
            @if($errors->has('apellidos'))
              <div class="badge text-bg-danger">{!! $errors->first('apellidos') !!}</div>
            @endIf
          </div>
          <div class="col-md-4">
            <input name="nomina" type="text" class="form-control" value="{!! old('nomina') !!}" placeholder="Nómina a la que pertenece">
            @if($errors->has('nomina'))
              <div class="badge text-bg-danger">{!! $errors->first('nomina') !!}</div>
            @endIf
          </div>
          <div class="col-md-4">
            <input name="direccion" type="text" class="form-control" value="{!! old('direccion') !!}" placeholder="Dirección Residencia">
            @if($errors->has('direccion'))
              <div class="badge text-bg-danger">{!! $errors->first('direccion') !!}</div>
            @endIf
          </div>
          <div class="col-md-4">
            <input name="email" type="email" class="form-control" value="{!! old('email') !!}" placeholder="Email">
            @if($errors->has('email'))
              <div class="badge text-bg-danger">{!! $errors->first('email') !!}</div>
            @endIf
          </div>
          <div class="col-md-4">
            <input name="telefono" type="number" class="form-control" value="{!! old('telefono') !!}" placeholder="Teléfono Fijo">
            @if($errors->has('telefono'))
              <div class="badge text-bg-danger">{!! $errors->first('telefono') !!}</div>
            @endIf
          </div>
          <div class="col-md-4">
            <input name="celular" type="number" class="form-control" value="{!! old('celular') !!}" placeholder="Celular">
            @if($errors->has('celular'))
              <div class="badge text-bg-danger">{!! $errors->first('celular') !!}</div>
            @endIf
          </div>
          <div class="col-md-4">
            <input name="whatsapp" type="number" class="form-control" value="{!! old('whatsapp') !!}" placeholder="Línea Whatsapp">
            @if($errors->has('whatsapp'))
              <div class="badge text-bg-danger">{!! $errors->first('whatsapp') !!}</div>
            @endIf
          </div>
          <div class="col-md-4">
            <select name="agencia" class="form-select">
              <option selected disabled value="0">Agencia</option>
              @foreach($agencias as $agencia)
                <option value="{!! $agencia->cu !!}" {{ old('agencia') == $agencia->cu ? 'selected' : '' }}>{!! mb_strtoupper($agencia->nombre_agencia) !!}</option>
              @endForeach
            </select>
            @if($errors->has('agencia'))
              <span class="badge text-bg-danger">{!! $errors->first('agencia') !!}</span>
            @endIf
          </div>
          <div class="col-md-8">
            <textarea name="mensaje" class="form-control" rows="3" placeholder="Escriba aqui el detalle de su petición, queja, reclamo y/o sugerencia.">{{ old('mensaje') }}</textarea>
            @if($errors->has('mensaje'))
              <div class="badge text-bg-danger">{!! $errors->first('mensaje') !!}</div>
            @endIf
          </div>
          <div class="col-md-4">
            <input name="soporte[]" class="form-control" type="file" value="{!! old('soporte') !!}" multiple>
            @if($errors->has('soporte'))
              <div class="badge text-bg-danger">{!! $errors->first('soporte') !!}</div>
            @endIf
          </div>
          <div class="col-md-4">
            <input name="check_uno" class="form-check-input" type="checkbox" value="1" id="check_uno" style="border: 2px solid #005e56;">
            <label class="form-check-label" for="check_uno" style="width:90%; text-align:justify">
              He leído y estoy de acuerdo con los términos y condiciones de uso de datos, implementados por COOPSERP. <a href="http://www.coopserp.com/politicas-de-datos/">Ver Politica de Datos</a>
            </label>
            @if($errors->has('check_uno'))
              <div class="badge text-bg-danger">{!! $errors->first('check_uno') !!}</div>
            @endIf
          </div>
          <div class="col-md-4">
            <input name="check_dos" class="form-check-input" type="checkbox" value="1" id="check_dos" style="border: 2px solid #005e56;">
            <label class="form-check-label" for="check_dos" style="width:90%; text-align:justify">
              Certifico que el correo electrónico Y mi número celular ingresado en mis datos personales se encuentran vigente, de igual manera autorizo a COOPSERP para el envío de la respuesta a mi solicitud por correo electrónico o contactandome a mi número celular.
            </label>
            @if($errors->has('check_dos'))
              <div class="badge text-bg-danger">{!! $errors->first('check_dos') !!}</div>
            @endIf
          </div>
          <div class="col-md-4" style="text-align: center;">
            <button type="submit" class="btn btn-primary" style="font-size: 21px;">RADICAR PQRS</button>
            <div id="upload" class="spinner-border text-success" role="status" hidden>
              <span class="visually-hidden">Loading...</span>
            </div>
            <br><br>
            <a href="{!! url('/consultar') !!}" class="btn btn-secondary" style="font-size: 21px;">CONSULTAR PQRS</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="{!! asset('js/jquery.min.js') !!}"></script>
  <script>
    $('button[type=submit]').click(function() {
      $(this).prop('hidden', true);
      $('#upload').prop('hidden', false);
    });
  </script>
</body>
</html>

