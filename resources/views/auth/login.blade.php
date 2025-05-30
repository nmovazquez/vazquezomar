@extends('layouts.app', [
'class' => 'off-canvas-sidebar',
'classPage' => 'login-page',
'activePage' => 'login',
'title' =>  "OmarVazquez",
])
 
@section('content')
<div class="container">
  <!-- <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto text-center" >
    <img src="{{asset('material/img/logotipo.png')}}" alt="" style="margin-top: -60px; margin-bottom: 30px;">
  </div> -->
  <div class="row">
    <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
      <form class="form" method="POST" id="loginForm" action="{{ route('login') }}">
        @csrf

        <div class="card card-login card-hidden">
          <div class="card-header card-header-primary text-center">
            <h4 class="card-title">{{ __('Iniciar sesión') }}</h4>

          </div>
          <div class="card-body ">
            <!-- <span class="form-group  bmd-form-group email-error {{ $errors->has('email') ? ' has-danger' : '' }}">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="material-icons">maps_home_work</i>
                  </span>
                </div>
                <input type="text" class="form-control err-email" id="name_instance" name="name_instance" placeholder="{{ __('Ingresa la instancia...') }}" value="{{session('name_instance')}}" @if(session('name_instance')) readonly @endif required>
              </div>
            </span> -->
            <span class="form-group  bmd-form-group email-error {{ $errors->has('email') ? ' has-danger' : '' }}">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="material-icons">email</i>
                  </span>
                </div>
                <input type="email" class="form-control err-email" id="exampleEmails" name="email" placeholder="{{ __('Correo o usuario...') }}" required>
              </div>
            </span>
            <span class="form-group bmd-form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="material-icons">lock_outline</i>
                  </span>
                </div>
                <input type="password" class="form-control" id="examplePassword" name="password" placeholder="{{ __('Contraseña...') }}" required>
              </div>
            </span>
            <!-- <div class="form-check mr-auto ml-3 mt-3">
                  <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember me') }}
                    <span class="form-check-sign">
                      <span class="check"></span>
                    </span>
                  </label>
                </div> -->
          </div>

          <div class="card-footer justify-content-center">

            <button type="submit" class="btn btn-primary btn-link btn-lg">{{ __('Ingresar') }}</button>
            <br>
            <hr>
            <a href="{{ route('google.redirect') }}" class="btn btn-outline-danger btn-block d-flex align-items-center justify-content-center gap-2">
    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" width="20" height="20">
    Iniciar sesión con Google
</a>

          </div>
        </div>
      </form>
      <!-- <div class="row">
          <div class="col-6">
              @if (Route::has('password.request'))
                  <a href="{{ route('password.request') }}" class="text-light">
                      <small>{{ __('Forgot password?') }}</small>
                  </a>
              @endif
          </div>
          <div class="col-6 text-right">
              <a href="{{ route('register') }}" class="text-light">
                  <small>{{ __('Create new account') }}</small>
              </a>
          </div>
        </div> -->
    </div>
  </div>
</div>
<div class="modal fade" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-dark">Verificando licencia</h4>
      </div>
      <div class="modal-body">
        <h5 class="text-center card-description" style="margin-top: -20px;">Se esta verificando tu licencia, por favor espera.</h5>
        <div class="logo text-center" id="modalImg">
          <img src="{{ asset('material') }}/img/load.gif" class="img-responsive logo-mini" style="width: 60px;">
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
  $(document).ready(function() {
    md.checkFullPageBackgroundImage();
    setTimeout(function() {
      // after 1000 ms we add the class animated to the login/register card
      $('.card').removeClass('card-hidden');
    }, 700);
  });

  $("#loginForm").submit(function(event) {
    event.preventDefault();
    var form = $(this);
    $('#modalLoading').modal('show');
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: form.serialize()
      })
      .done(function(resp) {
        setTimeout(() => {
          location.replace('{{url("/dashboard")}}')
        }, 2000);
      })
      .fail(function(resp) {
        setTimeout(() => {
          showNotification('error', 'top', 'center', 'danger', '¡ERROR!', 'Licencias incorrectas o usuario desactivado, verifica la información.')
        }, 2000);
      }).always(function() {
        setTimeout(() => {
          $('#modalLoading').modal('hide');
        }, 2000);
      })
  });
</script>
@endpush