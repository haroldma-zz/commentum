@extends('layouts.default')

@section('page')
<div class="error-page">
    <div class="content text-center">
        <h1>
            <b>403</b> Page not found
        </h1>
        <hr>
        <p>
        	We can't find the page you're looking for.
        </p>
        <br>
        <a href="{{ URL::previous() }}" class="btn">Oh, okay. Bring me back</a>
    </div>
</div>
@stop