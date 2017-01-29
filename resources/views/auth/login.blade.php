@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
                <form role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}

                    <div class="card">
                        <div class="header text-center">Login</div>
                        <div class="content">
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label>Email address</label>
                                <input type="email" placeholder="Enter email" name="email" class="form-control"
                                       value="{{ old('email') }}" autofocus>

                                @if ($errors->has('email'))
                                    <label class="error">{{ $errors->first('email') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" placeholder="Password" name="password" class="form-control">

                                @if ($errors->has('password'))
                                    <label class="error">{{ $errors->first('password') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="checkbox">
                                    <input type="checkbox" data-toggle="checkbox"
                                           name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="footer text-center">
                            <button type="submit" class="btn btn-fill btn-warning btn-wd">Login</button>
                            <div>
                                <a class="btn-link" href="{{ url('/password/reset') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
