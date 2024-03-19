<!--nav-->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand">
      <img src="{{ asset('img/logoCoopserp.png')}}" style="width: 50%;margin-left: 60px;">
    </a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="dropdown-item" href="{{ url('/logout') }}" style="font-size:21px">
            {!! session()->get('nombre') !!} {!! session()->get('apellido') !!}
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>