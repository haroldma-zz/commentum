@extends('layouts.default')

@section('page')
@if (Auth::check())
<div class="hero">
	@include('layouts.user-header')
</div>
@endif
@include('layouts.tag-hero', ['tag' => $tag])
<div class="padding">
	<div class="row">
		<div class="medium-9 columns">
			<div class="panel small">
				@include('layouts.threads', ['threads' => $tag->threads()])
			</div>
		</div>
		<div class="medium-3 columns">
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
@stop