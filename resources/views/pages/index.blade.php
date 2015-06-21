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
	@include('layouts.user-header')
	@endif
</div>
<div class="padding">
	<div class="row">
		<div class="medium-9 columns">
			<div class="panel small">
				<table class="questions-list">
					@if (!count($threads) > 0)
					<tr>
						<td>
							Nothing here...
						</td>
					</tr>
					@else
					@foreach ($threads as $t)
					<tr>
						<td>
							<a>{{ floor($t->momentum) }}</a>
						</td>
						<td>
							{!! (!empty($t->link) ? '<i class="ion-link"></i>' : '') !!}
							<a href="{{ $t->titlePermalink() }}">{{ $t->title }}</a>
							<br>
							<span>
								<a href="{{ $t->tag()->permalink() }}">
									<span data-livestamp="{{ strtotime($t->created_at) }}"></span> in
									#{{ $t->tag()->display_title }}
								</a>
								&middot;
								<a href="{{ url('/u/' . $t->author()->username) }}">{{ $t->author()->username }}</a>
								&middot;
								<a href="{{ $t->permalink() }}">{{ $t->commentCount() }} comment{{ ($t->commentCount() > 1 || $t->commentCount() === 0 ? 's' : '') }}</a>
							</span>
						</td>
					</tr>
					@endforeach
					@endif
				</table>
			</div>
		</div>
		<div class="medium-3 columns">
			@include('layouts.general-sidebar')
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