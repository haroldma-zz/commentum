<div class="panel small sidebar">
	<h6 class="super-header">Info</h6>
	<h4>
		<b>{{ floor($user->momentum) }}</b> point{{ (floor($user->momentum) != 1) ? 's' : '' }}
	</h4>
	<h4><small>Member since <span data-livestamp="{{ strtotime($user->created_at) }}"></span></small></h4>
	<hr>
	<h6 class="super-header">Claimed tags</h6>
	<ul class="no-bullet">
		@if (count($user->tags()) > 0)
		@foreach($user->tags() as $tag)
		<li><a href="{{ $tag->permalink() }}">/t/{{ $tag->display_title }}</a></li>
		@endforeach
		@else
		<li>-</li>
		@endif
	</ul>
	<hr>
	<h6 class="super-header">Subscriptions</h6>
	<ul class="no-bullet">
		@if (count($user->subscriptions()) > 0)
		@foreach($user->subscriptions() as $sub)
		<li><a href="{{ $sub->permalink() }}">/t/{{ $sub->display_title }}</a></li>
		@endforeach
		@else
		<li>-</li>
		@endif
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