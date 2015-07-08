@extends('layouts.default')

@section('page')
<div class="hero">
	<div class="row">
		<div class="medium-4 medium-offset-4 columns">
			{!! Form::open(['url' => '/login', 'class' => 'register-form', 'autocomplete' => 'off', 'id' => 'loginForm']) !!}
			<h3>Login</h3>
			<hr>
			{!! Form::label('username', 'Username') !!}
			{!! Form::text('username') !!}
			{!! Form::label('password', 'Password') !!}
			{!! Form::password('password') !!}
			<br>
			<p class="text-info"></p>
			{!! Form::submit('Login') !!}
			<img id="loginFormLoader" src="{{ url('/img/loader.svg') }}">
			{!! Form::close() !!}
		</div>
	</div>
</div>
@include('layouts.footer')
@stop

@section('scripts')
@include('scripts.register')
@stop