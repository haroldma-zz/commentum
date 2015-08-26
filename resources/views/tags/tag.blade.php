@extends('layouts.default')
@section('title', '#'.$tag->display_title)

@section('page')
@if (Auth::check())
<div class="hero">
	@include('layouts.user-header')
	@include('layouts.subscriptions-list')
</div>
@endif
@include('layouts.tag-hero', ['tag' => $tag])
<div class="padding">
	<div class="row small-collapse">
		<div class="medium-8 large-9 xlarge-10 columns">
			<div class="panel small min-height">
				@include('layouts.threads', ['threads' => $threads, 'limit' => $limit])
			</div>
		</div>
		<div class="medium-4 large-3 xlarge-2 columns">
			@include('layouts.tag-sidebar', ['tag' => $tag])
		</div>
	</div>
</div>
@stop

@section('scripts')
{!! HTML::script('/bower_components/marked/marked.min.js') !!}
{!! HTML::script('/bower_components/livestamp/moment.min.js') !!}
{!! HTML::script('/bower_components/livestamp/livestamp.min.js') !!}
@include('scripts.markdown-parser')
@include('scripts.load-more-submissions')
@if (Auth::check())
@include('scripts.subscriber', ['tag' => $tag])
@endif
@stop