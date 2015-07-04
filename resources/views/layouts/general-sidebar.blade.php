<div class="panel small sidebar">
	<h6 class="super-header">Trending tags</h6>
	<ul class="no-bullet">
		@foreach(App\Models\Tag::getTrendingTags() as $tag)
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
</div>