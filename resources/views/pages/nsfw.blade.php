@extends('layouts.default')

@section('page')
<div class="nsfw-notice">
	<div class="content text-center">
		<h1>
			This page is <b>NSFW</b>
		</h1>
		<hr>
		<p>Are you sure you want to proceed?</p>
		<br>
		<a class="btn success medium" id="proceedButton" href="{{ Session::get('intended') }}?accepted_nsfw=true">Yes, take me there!</a>
		<a class="btn medium" id="returnButton" href="{{ Session::get('referer') }}">No, get me out of here!</a>
	</div>
</div>
@stop