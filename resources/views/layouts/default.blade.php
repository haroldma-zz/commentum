<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Ask</title>
	{!! HTML::style('/stylesheets/app.css') !!}
</head>
<body>
	<div class="top-nav">
		<div class="row">
			<div class="medium-3 columns">
				<a class="brand" href="{{ url('/') }}">ASK</a>
			</div>
			<div class="medium-9 columns text-right">
				<a href="{{ url('/feed/trending') }}">Trending</a>
				<a href="{{ url('/feed/serious') }}">Serious</a>
				@if(!Auth::check())
				<a href="{{ url('/login') }}">Login</a>
				@else
				<a href="{{ url('/u/' . Auth::user()->username) }}">{{ Auth::user()->username }}</a>
				<a href="{{ url('/logout') }}">Logout</a>
				@endif
			</div>
		</div>
	</div>
	@yield('page')
	{!! HTML::script('/bower_components/jquery/dist/jquery.min.js') !!}
	@yield('scripts')
</body>
</html>