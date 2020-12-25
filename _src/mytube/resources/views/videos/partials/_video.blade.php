<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-thumbnail">
            <a href="/videos/{{ $video->uid }}">
                <img src="{{ $video->getThumbnail() }}" alt="" style="width: 100%; height: auto;">
            </a>
        </div>
        <div class="panel-body">
            <h4><a href="/videos/{{ $video->uid }}">{{ $video->name }}</a></h4>
            @if ($video->description)
                <p>{{ $video->description }}</p>
            @else
                <p class="muted">No description</p>
            @endif
            
            <ul class="list-inline">
                <li><a href="/channel/{{ $video->channel->slug }}">{{ $video->channel->name }}</a></li>
                <li>{{ $video->created_at->diffForHumans() }}</li>
                <li>{{ $video->viewCount() }} {{ str_plural('view', $video->viewCount()) }}</li>
            </ul>
        </div>
    </div>
</div>