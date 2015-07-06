<div class="panel small sidebar">
	
	<!--//////// BEGIN INFO SECTION ////////-->
	<h6 class="super-header">Info</h6>
	
	<!-- HIDING TAG MOMENTUM FOR NOW AS IT IS NOT YET IMPLEMENTED
	<h5>
		<b>{{ floor($tag->momentum) }}</b> point{{ (floor($tag->momentum) != 1 ? 's' : '') }}
	</h5>
	-->

    @if($tag->id > 0)
	<h6>
		<b>{{ $tag->subscriberCount() }}</b> subscriber{{ $tag->subscriberCount() != 1 ? 's' : '' }}
	</h6>
    @endif
	
	@if (Auth::check() && Auth::id() === $tag->owner()->id)
		<hr>
		<p>
			<a href="{{ $tag->permalink() }}/settings"><b>Tag settings</b></a>
		</p>
	@endif

    @if($tag->id == 0)
            <!-- Can't sub to all -->
    @endif
	@elseif(Auth::check() && !Auth::user()->isSubscribedToTag($tag->id))
		{!! Form::open(['url' => '/t/' . $tag->display_title . '/subscribe', 'id' => 'subscribeForm']) !!}
		{!! Form::hidden('tag-id', Hashids::encode($tag->id)) !!}
		<button id="subscribeButton" class="btn success" type="submit">Subscribe</button>
		{!! Form::close() !!}
	@elseif(Auth::check())
		<button id="subscribeButton" disabled="true" class="btn small inactive" type="submit">Subscribed</button>
	@else
		<a href="{{ url('/login') }}" class="btn success small">Subscribe</a>
	@endif
	<!--//////// END INFO SECTION ////////-->

	<!--//////// BEGIN RULES SECTION ////////-->
	@if(isset($tag->rules))
		<hr>
		<h6 class="super-header">Rules</h6>
		<div class="markdown">
			{{ $tag->rules }}
		</div>
	@endif
	<!--//////// END RULES SECTION ////////-->
	
	<!--//////// BEGIN MODERATORS SECTION ////////-->
	<hr>
	<h6 class="super-header">Moderators</h6>
	<ul class="no-bullet">
		@foreach($tag->mods() as $mod)
			<li>
				<a href="{{ $mod->user()->permalink() }}">
					/u/{{ $mod->user()->username }}
				</a>
				{{ $mod->user()->id == $tag->owner()->id ? '(owner)' : '' }}
			</li>
		@endforeach
	</ul>
	<!--//////// END MODERATORS SECTION ////////-->
	
	<!--//////// BEGIN SPONSORED LINKS SECTION ////////-->
	<!-- HIDING SPONSORED LINKS AS IT LOOKS TACKY TO OUR USERS AND IS NOT YET IMPLEMENTED
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
	-->
	<!--//////// END SPONSORED LINKS SECTION ////////-->
	
</div>