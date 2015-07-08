<div class="panel small sidebar">
	<h6 class="super-header">Trending tags</h6>
	<ul class="no-bullet">
		@foreach(App\Models\Tag::getTrendingTags() as $tag)
			<li><a href='{{ url("/t/{$tag->display_title}") }}'>#{{ $tag->display_title }}</a></li>
		@endforeach
	</ul>
	<hr>
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
    <h6 class="super-header">Links</h6>
    <ul>
        <li><a href="/p/rules_and_policies">Rules & Policies</a></li>
        <li><a href="/p/donate">Donate</a></li>
        <li><a href="/p/contact">Contact</a></li>
    </ul>
	<p>© 2015 Commentum. <br /> All Rights Reserved.</p>
</div>