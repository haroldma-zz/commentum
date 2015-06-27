@extends('layouts.default')

@section('page')
<div class="hero">
	@if(!Auth::check())
	@include('layouts.hero')
	@else
	@include('layouts.user-header')
	@include('layouts.tags-nav')
	@endif
</div>
<div class="padding">
	<div class="row">
		<div class="medium-9 columns">
			<div class="panel small">
				@include('layouts.threads', ['threads' => $threads])
			</div>
		</div>
		<div class="medium-3 columns">
			@include('layouts.general-sidebar')
		</div>
	</div>
</div>
@stop

@section('scripts')
{!! HTML::script('/bower_components/livestamp/moment.min.js') !!}
{!! HTML::script('/bower_components/livestamp/livestamp.min.js') !!}
@include('scripts.load-more-submissions')
@if (!Auth::check())
@include('scripts.register')
@endif
@stop