@extends('layouts.admin')

@section('content')
<div class="login">
    <div class="card">
        <div class="card-header sr-only">Login</div>
        <div class="card-body">
            <h1 class="login__logo"><img src="{{ secure_asset('images/logo.png') }}" alt="{{ config('app.name', 'Participa') }}" /></h1>
            <form class="form-horizontal" role="form" method="POST" action="{{ secure_url('/login') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="username" class="sr-only">Username</label>
                    <div class="input-group input-group-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="user-icon"><i class="far fa-user fa-fw" aria-hidden="true"></i></span>
                        </div>
                        <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" placeholder="Usuari" required autofocus aria-describedby="user-icon">
                    </div>

                    @if ($errors->has('username'))
                        <span class="invalid-feedback d-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password" class="sr-only">Password</label>
                    <div class="input-group input-group-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="key-icon"><i class="far fa-lock fa-fw" aria-hidden="true"></i></span>
                        </div>
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Contrasenya" required>
                    </div>

                    @if ($errors->has('password'))
                        <span class="invalid-feedback d-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        Entra
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
