<div class="panel small sidebar">
	<h6 class="super-header">Info</h6>
	<h6><a href="{{ $user->permalink() }}">{{ $user->username }}</a></h6>
	<h4>
		<b>{{ floor($user->momentum) }}</b> point{{ (floor($user->momentum) != 1) ? 's' : '' }}
	</h4>
	<h4><small>Member since <span data-livestamp="{{ strtotime($user->created_at) }}"></span></small></h4>
	@if (Auth::check() && Auth::id() !== $user->id)
	<a href="{{ url('/inbox/new') }}?to={{ $user->username }}" class="btn blue small">Send message</a>
	@endif
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
		<li><a href="{{ $sub->tag()->permalink() }}">/t/{{ $sub->tag()->display_title }}</a></li>
		@endforeach
		@else
		<li>-</li>
		@endif
	</ul>
</div>