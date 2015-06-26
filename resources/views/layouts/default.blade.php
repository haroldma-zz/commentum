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
				<a class="brand" href="{{ url('/') }}">Commentum</a>
				<a id="exploreListButton">Explore <i class="ion-arrow-down-b"></i></a>
				<ul class="no-bullet explore-list" id="exploreList">
					<li class="animated fadeInUp">
						<a href="{{ url('/t/all') }}">All</a>
					</li>
					@foreach(App\Models\Tag::getExploreList() as $tag)
					<li class="animated fadeInUp"><a href="{{ $tag->permalink() }}">#{{ $tag->display_title }}</a></li>
					@endforeach
				</ul>
			</div>
			<div class="medium-8 columns text-right">
				@if(!Auth::check())
				<a href="{{ url('/submit') }}">Submit</a>
				<a href="{{ url('/') }}">Register</a>
				<a href="{{ url('/login') }}">Login</a>
				@else
				<a id="showHero" class="hide"><i class="ion-chevron-down"></i></a>
				<a href="{{ Auth::user()->permalink() }}">{{ Auth::user()->username }}</a>
				<a href="{{ url('/submit') }}">Submit</a>
				<a href="{{ url('/logout') }}">Logout</a>
				@endif
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