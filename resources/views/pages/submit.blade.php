@extends('layouts.default')

@section('page')
{!! Form::open(['url' => '/submit', 'id' => 'submitForm']) !!}
<div class="hero">
	@include('layouts.user-header')
	@include('layouts.tags-nav')
	<hr>
	<div class="row">
		<div class="medium-6 medium-offset-3 columns">
			<div class="register-form">
				{!! Form::label('title', 'Choose a title', ['class' => 'white-font']) !!}
				{!! Form::textarea('title', '', ['rows' => 1]) !!}
			</div>
		</div>
	</div>
</div>
<div class="padding">
	<div class="row">
		<div class="medium-6 medium-offset-3 columns">
			<div class="panel">
				<h5>
					Link
				</h5>
				<hr>
				<p>
					If you provide a link, your submission will redirect to the link you provided. Users can still comment on your submission and build up momentum.
				</p>
				{!! Form::text('link', '', ['placeholder' => 'https://']) !!}
				<br>
				<h5>
					Description
				</h5>
				<hr>
				<p>
					You can use <a href="{{ url('/') }}">Markdown</a>.
				</p>
				{!! Form::textarea('description', '', ['rows' => 5]) !!}
				<br>
				<h5>
					More details
				</h5>
				<hr>
				{!! Form::label('tag', 'Tag your submission') !!}
				<br>
				<ul>
					<li>Tags are claimed by users.</li>
					<li>If the tag isn't claimed, it will be claimed by you.</li>
					<li>You can only claim one tag per day.</li>
					<li>You cannot claim more than 15 tags per year.</li>
					<li>If you don't submit a tag, your submission will be submitted to <a href="{{ url('/tag/random') }}">#random</a>.</li>
					<li>You can use one tag per submission.</li>
				</ul>
				{!! Form::text('tag', '', ['placeholder' => '#ask']) !!}
				<br>
				<h5>
					Submission settings
				</h5>
				<hr>
				<label for="nsfw" class="checkbox-label">
					{!! Form::checkbox('nsfw', 1, null, ['id' => 'nsfw']) !!}
					This submission is <b>Not Safe For Work</b>
				</label>
				<label for="serious" class="checkbox-label">
					{!! Form::checkbox('serious', 1, null, ['id' => 'serious']) !!}
					This is a <b>serious</b> submission.
				</label>
				<br>
				<p class="text-alert" id="submitFormError"></p>
				{!! Form::submit('Submit', ['class' => 'btn blue']) !!}
				&nbsp;&nbsp;&nbsp;
				<img class="loader" id="submitFormLoader" src="{{ url('/img/dark-loader.svg') }}">
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@stop

@section('scripts')
{!! HTML::script('/bower_components/autosize/autosize.min.js') !!}
@include('scripts.autosizer')
@include('scripts.submit')
@include('scripts.threads-user-header')
@stop