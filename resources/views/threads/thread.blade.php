@extends('layouts.default')

@section('page')
<div class="hero">
	@if (Auth::check())
	@include('layouts.user-header')
	<hr>
	@endif
	<div class="row">
		<div class="medium-12 columns">
			<table>
				<tr>
					<td>
						{{ floor($thread->momentum) }}
					</td>
					<td>
						<h1 class="thread-title">
							{!! (!empty($thread->link) ? '<i class="ion-link"></i>' : '') !!}
							<a href="{{ $thread->titlePermalink() }}">{{ $thread->title }}</a>
						</h1>
						<p>
							<a href="{{ $thread->tag()->permalink() }}"><span data-livestamp="{{ strtotime($thread->created_at) }}"></span> in #{{ $thread->tag()->display_title }}</a> by <a href="{{ $thread->author()->permalink() }}">{{ $thread->author()->username }}</a>
						</p>
						<br>
						<div class="markdown thread-description">
							{{ $thread->markdown }}
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
@if(Auth::check())
@include('layouts.tags-nav')
@endif
<div class="padding">
	<div class="row">
		<div class="medium-12 columns">
			{!! Form::open(['url' => '/comment', 'class' => 'row comment-box', 'data-hierarchy' => 'parent', 'onsubmit' => 'submitComment(event, this)']) !!}
				{!! Form::hidden('thread_id', Hashids::encode($thread->id)) !!}
				{!! Form::hidden('parent_id', Hashids::encode(0)) !!}
				<div class="medium-5 columns">
					{!! Form::label('markdown', 'Post a comment') !!}
					<p class="no-margin">
						You can use <a href="{{ url('/') }}">Markdown</a>.
					</p>
					{!! Form::textarea('markdown', '', ['rows' => 4]) !!}
					<p class="text-alert"></p>
					{!! Form::submit('Submit', ['class' => 'btn']) !!}
				</div>
			{!! Form::close() !!}
		</div>
		<div class="medium-12 columns">
			<hr>
			<p class="super-header light"><span id="threadCommentCount">{{ $thread->commentCount() }}</span> comment<span id="threadCommentPlural">{{ ($thread->commentCount() > 1 || $thread->commentCount() === 0 ? 's' : '') }}</span></p>
			<div class="comments-list children" id="commentsList">
				{!! $thread->printComments() !!}
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
@if (Auth::check())
@include('scripts.commenter')
@endif
@stop