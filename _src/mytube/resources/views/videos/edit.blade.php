@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Videos</div>

                    <div class="panel-body">
                       <form action="/videos/{{ $video->uid }}" method="post">
                            {!! csrf_field() !!}
                            {{ method_field('PUT') }}
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" id="" class="form-control" value="{{ $video->name }}" />
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" cols="30" rows="10" class="form-control">{{ $video->description }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="visibility">Visibility</label>
                                <select name="visibility" class="form-control">
                                    <option @if($video->visibility == "private") selected @endif value="private">Private</option>
                                    <option @if($video->visibility == "unlisted") selected @endif value="unlisted">Unlisted</option>
                                    <option @if($video->visibility == "public") selected @endif value="public">Public</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="allow_votes">
                                    <input type="checkbox" name="allow_votes" id="allow_votes"{{ $video->votesAllowed() ? ' checked="checked"' : '' }}> Allow votes
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="allow_comments">
                                    <input type="checkbox" name="allow_comments" id="allow_comments"{{ $video->commentsAllowed() ? ' checked="checked"' : '' }}> Allow comments
                                </label>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-default">Save Changes</button>
                            </div>
                       </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop  