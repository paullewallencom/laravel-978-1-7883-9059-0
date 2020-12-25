@extends('layouts.app')

@section('content')
	<div class="container">
	    <div class="row">
	        <div class="col-md-8 col-md-offset-2">
	            <div class="panel panel-default">
	                <div class="panel-heading">Account Settings</div>

	                <div class="panel-body">
	                    <form action="/account" method="post">
							{!! csrf_field() !!}

							<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
								<label>Name</label>
								<input type="text" name="name" value="{{ old('name') ? old('name') : $user->name }}" id="name" class="form-control">
								@if ($errors->has('name'))
                                    <div class="help-block">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
							</div>
							<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
								<label>Email</label>
								<input type="text" name="email" value="{{ old('email') ? old('email') : $user->email }}" id="email" class="form-control">
								@if ($errors->has('email'))
                                    <div class="help-block">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
							</div>
							<div class="form-group">
								<label>Password</label>
								<input type="password" name="password" class="form-control">
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-default pull-right">Update Account Settings</button>
							</div>

						</form>
			
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@stop