@extends('layouts.default')

@section('page')
@if(!Auth::check())
<div class="hero">
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
			{!! Form::label('username', 'Choose a username') !!}
			{!! Form::text('username') !!}
			{!! Form::label('email', 'Your e-mail address (optional)') !!}
			{!! Form::email('email') !!}
			{!! Form::label('password', 'Password') !!}
			{!! Form::password('password') !!}
			<br>
			<p class="text-alert"></p>
			{!! Form::submit('Register') !!}
			<img id="registerFormLoader" src="{{ url('/img/loader.svg') }}">
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endif
@stop

@section('scripts')
@include('scripts.register')
@stop