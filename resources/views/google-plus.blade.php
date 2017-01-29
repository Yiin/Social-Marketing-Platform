@extends('layouts.app')

@section('content')

    <div class="col-md-6">
        <div class="card">
            <div class="header">
                <h4 class="title">Manage Google Plus Accounts</h4>
                {{--<a href="{{ route('google-plus.add-account') }}">Add new one</a>--}}
            </div>
            <div class="content">
                <google-plus-accounts-table></google-plus-accounts-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="header">
                <h4 class="title">Passport</h4>
            </div>
            <div class="content">

            </div>
        </div>
    </div>

@endsection