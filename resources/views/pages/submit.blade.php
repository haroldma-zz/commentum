@extends('layouts.default')

@section('page')
{!! Form::open(['url' => '/submit', 'id' => 'submitForm']) !!}
<div class="hero">
	@include('layouts.user-header')
	@include('layouts.tags-nav')
	<hr>
	<div class="row">
		<div class="medium-6 medium-offset-3 large-4 large-offset-4 columns">
			<div class="register-form">
				{!! Form::label('title', 'Choose a title', ['class' => 'white-font']) !!}
				{!! Form::textarea('title', (isset($thread) ? $thread->title : ''), ['rows' => 1]) !!}
			</div>
		</div>
	</div>
</div>
<div class="padding">
	<div class="row">
		<div class="medium-6 medium-offset-3 large-4 large-offset-4 columns">
			<div class="panel">
				<h5>
					Link
				</h5>
				<hr>
				<p>
					If you provide a link, your submission will redirect to the link you provided. Users can still comment on your submission and build up momentum.
				</p>
				{!! Form::text('link', (isset($thread) ? $thread->link : ''), ['placeholder' => 'https://']) !!}
				<br>
				<h5>
					Description
				</h5>
				<hr>
				<p>
					You can use <a href="{{ url('/') }}">Markdown</a>.
				</p>
				{!! Form::textarea('description', (isset($thread) ? $thread->markdown : ''), ['rows' => 5]) !!}
				<div class="preview">
					<div class="markdown {{ (isset($thread) ? '' : 'hide') }}" id="preview"></div>
				</div>
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
					<li>If you don't enter a tag, your submission will be submitted to <a href="{{ url('/tag/random') }}">#random</a>.</li>
					<li>You can use one tag per submission.</li>
				</ul>
				{!! Form::text('tag', (isset($thread) ? '#' . $thread->tag()->display_title : ''), ['placeholder' => '#ask']) !!}
				<br>
				<h5>
					Submission settings
				</h5>
				<hr>
				<label for="nsfw" class="checkbox-label">
					{!! Form::checkbox('nsfw', 1, (isset($thread) ? $thread->nsfw : ''), ['id' => 'nsfw']) !!}
					This submission is <b>Not Safe For Work</b>
				</label>
				<label for="serious" class="checkbox-label">
					{!! Form::checkbox('serious', 1, (isset($thread) ? $thread->serious : ''), ['id' => 'serious']) !!}
					This is a <b>serious</b> submission.
				</label>
				<br>
				<p class="text-alert hide" id="submitFormError"></p>
				<p class="text-dark-info hide" id="submitReadyText"></p>
				@if (isset($thread))
				{!! Form::hidden('thread_id', Hashids::encode($thread->id)) !!}
				@endif
				<a id="submitCheck" class="btn blue">Submit</a>
				{!! Form::submit('Yes, I\'m sure', ['class' => 'btn blue hide', 'id' => 'submitButton']) !!}
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
{!! HTML::script('/bower_components/marked/marked.min.js') !!}
@include('scripts.autosizer')
@include('scripts.submit')
@include('scripts.threads-user-header')
@stop