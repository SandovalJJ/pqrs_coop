<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css')}}">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>
<body style="background: #eee;">
  <div class="container-sm" style="margin-top: 10%;">
    <div class="row">
      <div class="col-md-5 col-12" style="margin:auto; background: white;padding: 0%;">
        <div class="card-header" style="background: #005e56;color: white;padding: 3%;">
          <h1>LOGIN PQRS</h1>
        </div>
        <br>
        <div class="container text-center">
          <div class="col-12" style="text-align:center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16" color="#005e56">
              <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
              <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
            </svg>
          </div>
          <br>
          @if(session('msj-error'))
            <div class="alert alert-danger">
              {{ session('msj-error') }}
            </div>
          @endif
          <form class="g-3" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-md-9" style="margin:auto;">
              <input name="cedula" type="number" class="form-control" placeholder="Número de Cedula o Agencia">
            </div>
            <br>
            <div class="col-md-9" style="margin:auto;">
              <input name="password" type="password" class="form-control" placeholder="Contraseña">
            </div>
            <br>
            <div class="col-md-12" style="text-align: center;">
              <button type="submit" class="btn btn-primary" style="font-size: 21px;">INGRESAR</button>
              <br><br>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

