<div class="row">
	<div class="medium-7 large-6 large-offset-1 columns" style="padding-top:7.5em;">
		<h1>
			No votes,
			<br>
			just comments.
		</h1>
	</div>
	<div class="medium-5  large-3 end columns">
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
		{!! Form::label('password_confirmation', 'Confirm password') !!}
		{!! Form::password('password_confirmation') !!}
		<br>
		<p class="text-info"></p>
		{!! Form::submit('Register') !!}
		<img id="registerFormLoader" src="{{ url('/img/loader.svg') }}">
		{!! Form::close() !!}
	</div>
</div>