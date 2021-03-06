<div class="panel small sidebar">
	@if (isset($poll))
	<h6 class="super-header">Poll</h6>
	@include('layouts.poll', $poll)
	@endif
	<h6 class="super-header">New tags</h6>
	<ul class="no-bullet">
		@foreach(App\Models\Tag::getNewTags() as $tag)
			<li><a href='{{ url("/t/{$tag->display_title}") }}'>#{{ $tag->display_title }}</a></li>
		@endforeach
	</ul>
	<hr>
	<h6 class="super-header">Announcements</h6>
	<ul>
		@foreach(App\Models\Thread::where('tag_id', 3)->orderBy('id', 'DESC')->get() as $thread)
		<li><a href="{{ $thread->permalink() }}">{{ $thread->title }}</a></li>
		@endforeach
	</ul>
	<hr>
	<a href="/p/donate" class="btn success fill-width text-center">Make a Donation</a>
</div>