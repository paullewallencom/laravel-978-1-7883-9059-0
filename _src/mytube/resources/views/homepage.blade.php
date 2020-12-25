@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
        <div class="col-md-10 col-md-offset-1">
        	<h3>Latest Videos</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
			<div class="row">
                @if($videos->count())
    				@foreach($videos as $video)
    	
    			
                        @include ('video.partials._video', [
                            'video' => $video
                        ])
                        
    		    	@endforeach
                @endif			
			</div>		
        </div>
    </div>
</div>
@endsection
