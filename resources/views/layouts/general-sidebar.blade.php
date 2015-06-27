<div class="panel small sidebar">
	<h6 class="super-header">New tags</h6>
	<ul class="no-bullet">
		@foreach(App\Models\Tag::getNewTags() as $tag)
		<li><a href="{{ url($tag->permalink()) }}">#{{ $tag->display_title }}</a></li>
		@endforeach
	</ul>
	<hr>
	<h6 class="super-header">Trending</h6>
	<ul class="no-bullet">
		<li><a href="">#commentum</a></li>
		<li><a href="">#DefendEquality</a></li>
		<li><a href="">#amsterdam</a></li>
		<li><a href="">#news</a></li>
		<li><a href="">#NoodlesFTW</a></li>
		<li><a href="">#NoVotes</a></li>
		<li><a href="">#GrandTheftAutoV</a></li>
	</ul>
	<hr>
	<h6 class="super-header">Sponsored</h6>
	<ul class="no-bullet">
		<li><a href=""><i class="ion-pound"></i> SponsoredTag</a></li>
		<li><a href=""><i class="ion-link"></i> Unsplash - Royalty free HD images</a></li>
		<li><a href=""><i class="ion-play"></i> Advertisment video</a></li>
		<li><a href=""><i class="ion-headphone"></i> Link to a song</a></li>
		<li><a href=""><i class="ion-image"></i> Image</a></li>
		<li><a href=""><i class="ion-person-add"></i> Jobs</a></li>
	</ul>
</div>