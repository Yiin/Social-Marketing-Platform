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
                                @if($log->log->type === 'log')
                                    <h4 class="title">Posting to "{{ $log->log->communityName }}"</h4>
                                    <div class="content">
                                        @if($queue->posts()->where('group', $log->log->communityId)->exists())
                                            Posts:
                                        @endif
                                        <ul>
                                            @foreach($queue->posts()->where('group', $log->log->communityId)->get() as $post)
                                                <li>
                                                    <a href="{{ $post->url }}" target="_blank">{{ $post->message }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @elseif ($log->log->type === 'error')
                                    <label class="error">{{ $log->log->error_message }}</label>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection