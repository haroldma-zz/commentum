<div class="row small-collapse trending-list">
	<div class="medium-12 columns">
		<ul class="inline-list">
			<li><a href="{{ url('/t/all') }}">#all</a></li>
			<li><i class="ion-ios-pulse-strong" title="Trending tags"></i></li>
            @foreach(App\Models\Tag::getTrendingTags() as $tag)
			<li><a href="{{ url("/t/{$tag->display_title}") }}">#{{ $tag->display_title }}</a></li>
			@endforeach
		</ul>
	</div>
</div>