@extends('layouts.default')

@section('page')
<div class="hero">
	@if(!Auth::check())
	@include('layouts.hero')
	@else
	@include('layouts.user-header')
	@include('layouts.subscriptions-list')
	@endif
</div>
<div class="padding">
	@include('layouts.trending-bar')
	<div class="row small-collapse">
		<div class="medium-8 large-9 xlarge-10 columns">
			<div class="panel small">
				@include('layouts.threads', ['threads' => $threads])
			</div>
		</div>
		<div class="medium-4 large-3 xlarge-2 columns">
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