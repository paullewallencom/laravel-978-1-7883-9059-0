@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Videos</div>

                    <div class="panel-body">
                        @if ($videos->count())
                            @foreach ($videos as $video)
                                <div class="well">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <a href="/videos/{{ $video->uid }}">
                                                <img src="{{ $video->getThumbnail() }}" alt="{{ $video->name }} thumbnail" class="img-responsive">
                                            </a>
                                        </div>
                                        <div class="col-sm-9">
                                            <a href="/videos/{{ $video->uid }}">{{ $video->name }}</a>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <p class="muted">
                                                       
                                                    </p>
                                                    
                                                    <form action="/videos/{{ $video->uid }}" method="post">
                                                        <a href="/videos/{{ $video->uid }}/edit" class="btn btn-default">Edit</a>

                                                        <button type="submit" class="btn btn-default">Delete</button>

                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                    </form>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p>{{ ucfirst($video->visibility) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{ $videos->links() }}
                        @else
                            <p>You have no videos.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop