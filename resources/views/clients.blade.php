@extends ('layouts.app')

@section('content')

    <div class="card">
        <div class="header">
            <h4 class="title">Clients List & Management</h4>
        </div>
        <div class="content">
            <clients-table data="{{ $clients }}"></clients-table>
        </div>
    </div>

@endsection