@extends ('layouts.auth')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2">
                <div class="card">
                    <div class="header text-center">
                        Log. Posted to {{ $queue->statistic_groups }} groups,
                        ~ {{ number_format($queue->statistic_members) }}
                        backlinks
                    </div>
                    <div class="content">
                        @foreach($queue->log()->orderBy('id', 'desc')->get() as $log)
                            <div>
                                @if($log->log->type === 'error')
                                    <label class="error">{{ $log->log->error }}</label>
                                @elseif($log->log->type === 'log')
                                    <h3>{{ $log->log->log_message }}</h3>
                                    <p>{{ $log->log->message }}</p>
                                    <h4>Posts:</h4>
                                @else
                                    <a href="{{ $log->log->link }}">{{ $log->log->link }}</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection