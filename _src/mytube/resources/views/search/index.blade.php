@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Search for "{{ Request::get('q') }}"</div>

                    <div class="panel-body">
                        @if ($channels->count())
                            <h4>Channels</h4>

                            <div class="well">
                                @foreach ($channels as $channel)
                                    <div class="media">
                                        <div class="media-left">
                                            <a href="/channel/{{ $channel->slug }}">
                                                <img src="{{ $channel->getAvatarImage() }}" width="40" height="40" alt="{{ $channel->name }} image" class="media-object">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <a href="/channel/{{ $channel->slug }}" class="media-heading">{{ $channel->name }}</a>

                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        @endif

  
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
