@extends('layouts.app')

@section('content')
	<div class="container">
	    <div class="row">
	        <div class="col-md-8 col-md-offset-2">
	            <div class="panel panel-default">
	            	<div class="panel-thumbnail">
	            		<img src="{{ $channel->getCoverImage() }}" style="width: 100%;" alt="">
	            	</div>
	                <div class="panel-body">
	                   
						<div class="media">
							<div class="media-left">
								<img src="{{ $channel->getAvatarImage() }}" alt="" width="40" height="40" class="media-object">
							</div>
							<div class="media-body">
								{{ $channel->name }}
								
								<ul class="list-inline">
								     <li>
								        <subscribe-button channel-slug="{{ $channel->slug }}"></subscribe-button>
								    </li>
								</ul>

                                @if ($channel->description)
                                    <hr>
                                    <p>{{ $channel->description }}</p>
                                @endif
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<h3>Videos</h3>
							</div>
						</div>
	                </div>
	            </div>
	        </div>
	    </div>
	    <div class="row">
	    	<div class="col-md-8 col-md-offset-2">
	    		<div class="row">
					
	    		</div>
	    	</div>
	    </div>
	</div>
@stop