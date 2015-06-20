@extends('layouts.default')

@section('page')
<div class="hero">
	@if(!Auth::check())
	<div class="row">
		<div class="medium-7 columns" style="padding-top:7.5em;">
			<h1>
				You ask,
				<br>
				the internet answers.
			</h1>
		</div>
		<div class="medium-5 columns">
			{!! Form::open(['url' => '/register', 'class' => 'register-form', 'autocomplete' => 'off', 'id' => 'registerForm']) !!}
			<h3>Register, it's free.</h3>
			<hr>
			<input type="password" style="display:none;">
			{!! Form::label('username', 'Choose a username') !!}
			{!! Form::text('username') !!}
			{!! Form::label('email', 'Your e-mail address (optional)') !!}
			{!! Form::email('email') !!}
			{!! Form::label('password', 'Password') !!}
			{!! Form::password('password') !!}
			<br>
			<p class="text-info"></p>
			{!! Form::submit('Register') !!}
			<img id="registerFormLoader" src="{{ url('/img/loader.svg') }}">
			{!! Form::close() !!}
		</div>
	</div>
	@else
	<div class="row">
		<div class="medium-8 columns">
			<h6>
				<a class="menu-item active">subscribed <i class="ion-arrow-down-b"></i></a>
				<a class="menu-item" href="{{ Auth::user()->permalink() }}">profile</a>
				<a class="menu-item" href="{{ url('/inbox') }}">inbox <span class="inbox-counter">1</span></a>
				<a class="menu-item" href="{{ url('/preferences') }}">preferences</a>
			</h6>
		</div>
	</div>
	@endif
</div>
<div class="padding">
	<div class="row">
		<div class="medium-9 columns">
			<div class="panel small">
				<table class="questions-list">
					@if (!count($questions) > 0)

					@else
					@foreach ($questions as $q)
					<tr>
						<td>
							<a>2</a>
						</td>
						<td>
							<a href="{{ $q->permalink() }}">{{ $q->question }}</a>
							<br>
							<span>
								<a href="{{ $q->tag()->permalink() }}">
									<span data-livestamp="{{ strtotime($q->created_at) }}"></span> in
									#{{ $q->tag()->display_title }}
								</a>
								&middot;
								<a href="{{ url('/u/' . $q->author()->username) }}">{{ $q->author()->username }}</a>
								&middot;
								<a href="{{ $q->permalink() }}">{{ $q->commentCount() }} comment{{ ($q->commentCount() > 1 || $q->commentCount() === 0 ? 's' : '') }}</a>
							</span>
						</td>
					</tr>
					@endforeach
					@endif
				</table>
			</div>
		</div>
		<div class="medium-3 columns">
			<div class="panel small sidebar">
				<h6 class="super-header">Trending</h6>
				<ul class="no-bullet">
					<li><a href="">#GameOfThrones</a></li>
					<li><a href="">#GrandTheftAutoV</a></li>
					<li><a href="">#ps4</a></li>
					<li><a href="">#FruityLoops</a></li>
					<li><a href="">#FruityLoops</a></li>
					<li><a href="">#FruityLoops</a></li>
					<li><a href="">#FruityLoops</a></li>
				</ul>
				<hr>
				<h6 class="super-header">Sponsored</h6>
				<ul class="no-bullet">
					<li><a href=""><i class="ion-help"></i> Sponsored question?</a></li>
					<li><a href=""><i class="ion-pound"></i> SponsoredTag</a></li>
					<li><a href=""><i class="ion-link"></i> Unsplash - Royalty free HD images</a></li>
					<li><a href=""><i class="ion-play"></i> Advertisment video</a></li>
					<li><a href=""><i class="ion-headphone"></i> Link to a song</a></li>
					<li><a href=""><i class="ion-image"></i> Image</a></li>
					<li><a href=""><i class="ion-person-add"></i> Jobs</a></li>
				</ul>
				<hr>
				<h6 class="super-header">New tags</h6>
				<ul class="no-bullet">
					<li><a href="">#FiftyFifty</a></li>
					<li><a href="">#CharlestonMassacre</a></li>
					<li><a href="">#movies</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
{!! HTML::script('/bower_components/livestamp/moment.min.js') !!}
{!! HTML::script('/bower_components/livestamp/livestamp.min.js') !!}
@if (!Auth::check())
@include('scripts.register')
@endif
@stop