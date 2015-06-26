@extends('layouts.default')

@section('page')
<div class="nsfw-notice">
	<div class="content text-center">
		<h1>
			This tag or is set <b>Private</b>.
		</h1>
		<hr>
		<br>
		<a href="{{ URL::previous() }}" class="btn medium">Ohh okay... Take me back then.</a>
		<a class="btn blue medium" href="{{ url('/t/' . Request::segment(2)) }}/request/invite">Request invite</a>
	</div>
</div>
@stop