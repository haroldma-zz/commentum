@extends('layouts.default')

@section('page')
<div class="hero">
	@include('layouts.user-header')
	@include('layouts.tags-nav')
</div>
<div class="padding">
	<div class="row">
		<div class="medium-9 columns inbox">
			@if(count(Auth::user()->messages()) > 0)
			<div class="panel small">
			@foreach(Auth::user()->messages() as $m)
				@if ($m->type == 5) <!-- Claimed a tag -->
				@include('users.inbox.claimed-tag', ['message' => $m])
				@elseif ($m->type == 1) <!-- Received comment on thread -->
				@include('users.inbox.thread-comment', ['message' => $m])
				@elseif ($m->type == 2) <!-- Received comment on comment -->
				@include('users.inbox.comment-comment', ['message' => $m])
				@endif
			@endforeach
			</div>
			@else
			<div class="panel small text-center">
				<h6>
					You have no messages.
				</h6>
			</div>
			@endif
		</div>
		<div class="medium-3 columns">
			@include('layouts.general-sidebar')
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
@stop