@extends('layouts.default')

@section('page')
<div class="hero">
	@if(!Auth::check())
	@include('layouts.hero')
	@else
	@include('layouts.user-header')
	@endif
</div>
@if(Auth::check())
@include('layouts.tags-nav')
@endif
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
@if (!Auth::check())
@include('scripts.register')
@endif
@stop