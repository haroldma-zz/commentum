<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{{ (Auth::check() && Auth::user()->messageCount() > 0 ? "[" . Auth::user()->messageCount() . "] " : "") }}@yield('title', 'Commentum')</title>
	{!! HTML::style('/stylesheets/app.css') !!}
</head>
<body>
	@if(!App::isLocal())
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-64657642-1', 'auto');
	  ga('send', 'pageview');

	</script>
	@endif
	<div class="top-nav">
		<div class="row">
			<div class="medium-12 columns">
				<div class="left">
					<ul class="inline-list">
						<li><a class="brand" href="{{ url('/') }}">Commentum</a></li>
						<li id="exploreListButton" class="hide-for-small-only">
							<a>Explore <i class="ion-arrow-down-b"></i></a>
							<ul class="no-bullet explore-list" id="exploreList">
								@foreach(App\Models\Tag::getExploreList() as $tag)
								<li><a href="{{ $tag->permalink() }}">#{{ $tag->display_title }}</a></li>
								@endforeach
							</ul>
						</li>
					</ul>
				</div>
				<div class="right text-right">
					<ul class="inline-list right hide-for-small-only">
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
					<ul class="inline-list right show-for-small-only">
						<li id="hamburgerIcon"><a><i class="ion-navicon"></i></a></li>
					</ul>
				</div>
				@if (Auth::check())
				<div class="notification-box">
					yo.
				</div>
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