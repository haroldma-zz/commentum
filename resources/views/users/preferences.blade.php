@extends('layouts.default')

@section('page')
<div class="hero">
	@include('layouts.user-header')
	@include('layouts.tags-nav')
</div>
<div class="padding">
	<div class="row">
		<div class="medium-9 columns inbox">
			{!! Form::open(['url' => '/me/preferences', 'class' => 'panel']) !!}
				<h4>Account settings</h4>
				<hr>
				<div class="row">
					<div class="medium-6 columns">
						{!! Form::label('username', 'Username') !!}
						{!! Form::text('username', Auth::user()->username, ['disabled' => true]) !!}
						{!! Form::label('email', 'E-mail address') !!}
						{!! Form::email('email', Auth::user()->email) !!}
					</div>
					<div class="medium-6 columns">
						{!! Form::label('new_password', 'New password') !!}
						{!! Form::password('new_password') !!}
						{!! Form::label('old_password', 'Old password') !!}
						{!! Form::password('old_password') !!}
					</div>
				</div>
				<br>
				<h4>Notification settings</h4>
				<hr>
				<label>
					{!! Form::checkbox('notify_email', 1) !!}
					Notify me about comment replies through e-mail.
				</label>
			{!! Form::close() !!}
		</div>
		<div class="medium-3 columns">
			@include('layouts.user-sidebar', ['user' => Auth::user()])
		</div>
	</div>
</div>
@stop

@section('scripts')
{!! HTML::script('/bower_components/marked/marked.min.js') !!}
{!! HTML::script('/bower_components/livestamp/moment.min.js') !!}
{!! HTML::script('/bower_components/livestamp/livestamp.min.js') !!}
@include('scripts.markdown-parser')
@include('scripts.inbox')
@include('scripts.commenter', ['threadUserId' => null])
@stop