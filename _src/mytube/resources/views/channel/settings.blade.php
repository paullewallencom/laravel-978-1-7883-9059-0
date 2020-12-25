@extends('layouts.app')

@section('content')
	<div class="container">
	    <div class="row">
	        <div class="col-md-8 col-md-offset-2">
	            <div class="panel panel-default">
	                <div class="panel-heading">Channel Settings</div>

	                <div class="panel-body">
	                    <form action="/channel/{{ $channel->slug }}/settings" method="post">
							{!! csrf_field() !!}

							<div class="form-group">
								<label>Channel Name</label>
								<input type="text" name="name" value="{{ $channel->name }}" id="name" class="form-control">
							</div>
							<div class="form-group">
								<label>Channel Slug</label>
								<input type="text" name="slug" value="{{ $channel->slug }}" id="slug" class="form-control">
							</div>
							<div class="form-group">
								<label>Channel Description</label>
								<textarea name="description" id="description" cols="30" rows="10" class="form-control">{{ $channel->description }}</textarea>
							</div>
							<div class="form-group">
								<label>Channel Avatar</label>
								<input type="file" name="avatar" id="avatar" class="form-control" />
							</div>
							<div class="form-group">
								<label>Channel Cover</label>
								<input type="file" name="cover" id="cover" class="form-control" />
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-default pull-right">Update Channel</button>
							</div>

						</form>
			
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@stop
