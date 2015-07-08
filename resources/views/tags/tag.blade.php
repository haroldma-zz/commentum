@extends('layouts.default')
@section('title', '#'.$tag->display_title)

@section('page')
@if (Auth::check())
<div class="hero">
	@include('layouts.user-header')
	@include('layouts.tags-nav')
</div>
@endif
@include('layouts.tag-hero', ['tag' => $tag])
<div class="padding">
	<div class="row small-collapse">
		<div class="medium-9 large-10 columns">
			<div class="panel small min-height">
				@include('layouts.threads', ['threads' => $threads, 'limit' => $limit])
			</div>
		</div>
		<div class="medium-3 large-2 columns">
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