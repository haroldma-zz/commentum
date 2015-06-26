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
	@yield('scripts')
	@if(Auth::check())
	@include('scripts.tags-nav-toggler')
	@endif
</body>
</html>