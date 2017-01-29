@extends ('layouts.app')

@section('content')

    <div class="col-md-6">
        <div class="card">
            <div class="header">
                <h4 class="title">Edit Profile</h4>
            </div>
            <div class="content">
                <form action="{{ route('user.update', ['user' => $user->id]) }}" method="POST">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
                                <label>Full Name</label>
                                <input type="text" class="form-control" placeholder="Full Name" name="name"
                                       value="{{ $user->name }}">
                                @if($errors->has('name'))
                                    @foreach($errors->get('name') as $error)
                                        <label class="error">{{ $error }}</label>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }}">
                                <label>Email address</label>
                                <input type="email" class="form-control" placeholder="Email" name="email"
                                       value="{{ $user->email }}">
                                @if($errors->has('email'))
                                    @foreach($errors->get('email') as $error)
                                        <label class="error">{{ $error }}</label>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-fill">Update Profile</button>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="header">
                <h4 class="title">Change password</h4>
            </div>
            <div class="content">
                <form action="{{ route('user.change-password', ['user' => $user->id]) }}" method="POST">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}

                    <div class="form-group {{ $errors->has('password') ? 'has-error has-feedback' : '' }}">
                        <label>Current Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Current Password">

                        @if($errors->has('password'))
                            @foreach($errors->get('password') as $error)
                                <label class="error">{{ $error }}</label>
                            @endforeach
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('new_password') ? 'has-error has-feedback' : '' }}">
                        <label>New Password</label>
                        <input type="password" class="form-control" name="new_password" placeholder="New Password">

                        @if($errors->has('new_password'))
                            @foreach($errors->get('new_password') as $error)
                                <label class="error">{{ $error }}</label>
                            @endforeach
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" class="form-control" name="new_password_confirmation"
                               placeholder="Confirm New Password">
                    </div>

                    <button type="submit" class="btn btn-primary btn-fill">Change Password</button>
                    <form>
            </div>
        </div>
    </div>

@endsection