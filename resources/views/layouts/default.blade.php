<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Commentum</title>
	{!! HTML::style('/stylesheets/app.css') !!}
</head>
<body>
	<div class="top-nav">
		<div class="row">
			<div class="medium-4 columns">
				<ul class="inline-list">
					<li><a class="brand" href="{{ url('/') }}">Commentum</a></li>
					<li id="exploreListButton">
						<a>Explore <i class="ion-arrow-down-b"></i></a>
						<ul class="no-bullet explore-list" id="exploreList">
							@foreach(App\Models\Tag::getExploreList() as $tag)
							<li><a href="{{ $tag->permalink() }}">#{{ $tag->display_title }}</a></li>
							@endforeach
						</ul>
					</li>
				</ul>
			</div>
			<div class="medium-8 columns text-right">
				<ul class="inline-list right">
					@if(!Auth::check())
					<li><a href="{{ url('/submit') }}">Submit</a></li>
					<li><a href="{{ url('/') }}">Register</a></li>
					<li><a href="{{ url('/login') }}">Login</a></li>
					@else
					<li id="showHero" class="hide"><a><i class="ion-chevron-down"></i></a></li>
					<li><a href="{{ Auth::user()->permalink() }}">{{ Auth::user()->username }}</a></li>
					<li><a href="{{ url('/submit') }}">Submit</a></li>
					<li><a href="{{ url('/logout') }}">Logout</a></li>
					@endif
				</ul>
			</div>
		</div>
	</div>
	@yield('page')
	{!! HTML::script('/bower_components/jquery/dist/jquery.min.js') !!}
	{!! HTML::script('/bower_components/jquery.cookie/jquery.cookie.js') !!}
	{!! HTML::script('/bower_components/nicescroll/jquery.nicescroll.js') !!}
	{!! HTML::script('/bower_components/wow/wow.min.js') !!}
	{!! HTML::script('/js/app.js') !!}
	@include('scripts.explore-list')
	@yield('scripts')
	@if(Auth::check())
	@include('scripts.tags-nav-toggler')
	@endif
</body>
</html>