<div class="panel small sidebar">
	<h6 class="super-header">New tags</h6>
	<ul class="no-bullet">
		@foreach(App\Models\Tag::getNewTags() as $tag)
		<li><a href="{{ url($tag->permalink()) }}">#{{ $tag->display_title }}</a></li>
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