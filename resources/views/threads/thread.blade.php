@extends('layouts.default')

@section('page')
<div class="hero">
	@if (Auth::check())
	@include('layouts.user-header')
	<hr>
	@endif
	<div class="row">
		<div class="medium-12 columns">
			<h1 class="thread-title">
				{!! (!empty($thread->link) ? '<i class="ion-link"></i>' : '') !!}
				<a href="{{ $thread->titlePermalink() }}">{{ $thread->title }}</a>
			</h1>
			<div class="markdown thread-description">
				{{ $thread->markdown }}
			</div>
		</div>
	</div>
</div>
<div class="padding">
	<div class="row">
		<div class="medium-12 columns">
			{!! Form::open(['url' => '/comment', 'class' => 'row comment-box']) !!}
				{!! Form::hidden('thread_id', Hashids::encode($thread->id)) !!}
				{!! Form::hidden('parent_id', 0) !!}
				<div class="medium-5 columns">
					{!! Form::label('markdown', 'Post a comment') !!}
					<p class="no-margin">
						You can use <a href="{{ url('/') }}">Markdown</a>.
					</p>
					{!! Form::textarea('markdown', '', ['rows' => 4]) !!}
					{!! Form::submit('Submit', ['class' => 'btn']) !!}
				</div>
			{!! Form::close() !!}
			<hr>
			<p class="super-header light">{{ $thread->commentCount() }} comment{{ ($thread->commentCount() > 1 || $thread->commentCount() === 0 ? 's' : '') }}</p>
			<div class="comments-list" id="commentsList">
				@foreach($thread->comments() as $c)
					<article class="parent">
						<section class="markdown">
							{{ $c->markdown }}
						</section>
						<footer>
							<span data-livestamp="{{ strtotime($c->created_at) }}"></span> by <a href="{{ $c->author()->permalink() }}">{{ $c->author()->username }}</a>
							&middot;
							<a>reply</a>
						</footer>
						<div class="children"></div>
					</article>
				@endforeach
			</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
{!! HTML::script('/bower_components/marked/marked.min.js') !!}
{!! HTML::script('/bower_components/livestamp/moment.min.js') !!}
{!! HTML::script('/bower_components/livestamp/livestamp.min.js') !!}
@include('scripts.markdown-parser')
@include('scripts.commenter')
@stop