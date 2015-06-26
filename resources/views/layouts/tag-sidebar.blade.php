<div class="panel small sidebar">
	<h6 class="super-header">Info</h6>
	<h4>
		<b>{{ floor($tag->momentum) }}</b> point{{ (floor($tag->momentum) != 1 ? 's' : '') }}
	</h4>
	<h5>
		<b>{{ $tag->threadCount() }}</b> thread{{ ($tag->threadCount() != 1 ? 's' : '') }}
	</h5>
	<h4><small>Claimed by <a href="{{ $tag->owner()->permalink() }}">/u/{{ $tag->owner()->username }}</a></small></h4>
	@if (Auth::check() && Auth::id() === $tag->owner()->id)
	<hr>
	<p>
		<a href="{{ $tag->permalink() }}/settings"><b>Tag settings</b></a>
	</p>
	@endif
	@if(Auth::check() && !Auth::user()->isSubscribedToTag($tag->id))
	{!! Form::open(['url' => '/t/' . $tag->display_title . '/subscribe', 'id' => 'subscribeForm']) !!}
	{!! Form::hidden('tag-id', Hashids::encode($tag->id)) !!}
	<button id="subscribeButton" class="btn success" type="submit">Subscribe</button>
	{!! Form::close() !!}
	@elseif(Auth::check())
	<button id="subscribeButton" disabled="true" class="btn small inactive" type="submit">Subscribed</button>
	@else
	<a href="{{ url('/login') }}" class="btn success small">Subscribe</a>
	@endif
	<hr>
	<h6 class="super-header">Rules</h6>
	<ul class="no-bullet">
		<li>Some rule</li>
		<li>Some rule</li>
		<li>Some rule</li>
		<li>Some rule</li>
	</ul>
	<hr>
	<h6 class="super-header">Moderators</h6>
	<ul class="no-bullet">
		@foreach($tag->mods() as $mod)
		<li><a href="{{ $mod->user()->permalink() }}">/u/{{ $mod->user()->username }}</a></li>
		@endforeach
	</ul>
	<hr>
	<h6 class="super-header">Sponsored</h6>
	<ul class="no-bullet">
		<li><a href=""><i class="ion-help"></i> Sponsored question?</a></li>
		<li><a href=""><i class="ion-pound"></i> SponsoredTag</a></li>
		<li><a href=""><i class="ion-link"></i> Unsplash - Royalty free HD images</a></li>
		<li><a href=""><i class="ion-play"></i> Advertisment video</a></li>
		<li><a href=""><i class="ion-headphone"></i> Link to a song</a></li>
		<li><a href=""><i class="ion-image"></i> Image</a></li>
		<li><a href=""><i class="ion-person-add"></i> Jobs</a></li>
	</ul>
</div>