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
							<a href="{{ $q->permalink() }}">{{ $q->question }}</a>
							<br>
							<span>
								<a href="{{ url('/t/' . $q->tag->display_title) }}">
									<span data-livestamp="{{ strtotime($q->created_at) }}"></span> in
									#{{ $q->tag->display_title }}
								</a>
								&middot;
								<a href="{{ url('/u/' . $q->author->username) }}">{{ $q->author->username }}</a>
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