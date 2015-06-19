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
			<p class="text-info"></p>
			{!! Form::submit('Register') !!}
			<img id="registerFormLoader" src="{{ url('/img/loader.svg') }}">
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endif
<div class="padding">
	<div class="row">
		<div class="medium-10 medium-offset-1 columns">
			<div class="panel">
				<table class="questions-list">
					@if (!count($questions) > 0)

					@else
					@foreach ($questions as $q)
					<tr>
						<td>
							<a>2</a>
						</td>
						<td>
							<a href="{{ $q->permalink() }}">{{ $q->question }} Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Maecenas sed diam eget risus varius blandit sit amet non magna.</a>
							<br>
							<span>
								<a href="{{ url('/t/' . $q->tag->display_title) }}">#{{ $q->tag->display_title }}</a>
								&middot;
								<a href="{{ url('/u/' . $q->author->username) }}">{{ $q->author->username }}</a>
							</span>
						</td>
					</tr>
					@endforeach
					@endif
				</table>
			</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
@include('scripts.register')
@stop