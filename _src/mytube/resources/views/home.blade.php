@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                      <div class="row">
                          <div class="col-md-12">
                              <h4>Videos</h4>
                          </div>
                      </div>
                       <div class="row">
                            @if ($videos->count())
                                @foreach ($videos as $video)
                                    
                                    @include ('video.partials._video', [
                                        'video' => $video
                                    ])
                                
                                @endforeach
                            @else
                                <div class="col-md-12">
                                    <p>No videos found.</p>
                                </div>
                            @endif
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection