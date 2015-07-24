@extends('layouts.default')
@section('title', 'inbox')

@section('page')
<div class="hero">
	@include('layouts.user-header')
	@include('layouts.subscriptions-list')
</div>
<div class="padding">
	<div class="row small-collapse">
		<div class="medium-9 large-10 columns inbox">
			@if(count(Auth::user()->messages()) > 0)
			<div class="panel small">
			@foreach($messages as $m)
				@if ($m->type == 5) <!-- Claimed a tag -->
				@include('users.inbox.claimed-tag', ['message' => $m])
				@elseif ($m->type == 1) <!-- Received comment on thread -->
				@include('users.inbox.thread-comment', ['message' => $m])
				@elseif ($m->type == 2) <!-- Received comment on comment -->
				@include('users.inbox.comment-comment', ['message' => $m])
				@elseif ($m->type == 3) <!-- Received Private Message -->
				@include('users.inbox.private-message', ['message' => $m])
				@elseif ($m->type == 6) <!-- Received tag subscription -->
				@include('users.inbox.tag-subscription', ['message' => $m])
				@endif
			@endforeach
			</div>
			<div class="row small-collapse">
				<div class="small-12 columns">
					@if ($messages->currentPage() > 1)
					<div class="left" style="margin-left:1em;">
						<a href="?page={{ $messages->currentPage() - 1 }}" class="btn blue">Previous 15</a>
					</div>
					@endif
					@if($messages->hasMorePages())
					<div class="right">
						<a href="?page={{ $messages->currentPage() + 1 }}" class="btn blue">Next 15</a>
					</div>
					@endif
				</div>
			</div>
			@else
			<div class="panel small text-center">
				<h6>
					You have no messages.
				</h6>
			</div>
			@endif
		</div>
		<div class="medium-3 large-2 columns">
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
@include('scripts.commenter', ['threadUserId' => null])
@stop
