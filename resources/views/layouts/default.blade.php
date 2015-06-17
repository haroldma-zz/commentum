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
			<div class="medium-4 columns">
				<a class="brand" href="{{ url('/') }}">ASK</a>
				<a href="{{ url('/feed/trending') }}">Trending</a>
			</div>
			<div class="medium-8 columns text-right">
				@if(!Auth::check())
				<a href="{{ url('/submit') }}">Submit</a>
				<a href="{{ url('/login') }}">Login</a>
				@else
				<a href="{{ url('/submit') }}">Submit</a>
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