@extends('layouts.default')

@section('page')
<div class="hero">
	<div class="row">
		<div class="medium-4 medium-offset-4 columns">
			<h3>Donate</h3>
			
			<hr>
			
			<p class="text-info">Running Commentum costs money -- a lot more than you'd think!</p>
			<p class="text-info">We're hard at work, trying to make Commentum an even greater place to be.</p>
			<p class="text-info">Help us pay our server costs by making a donation. Whatever amount you can help with is enough.</p>
			<p class="text-info">We appreciate all the help we get. Thanks!</p>
			
			<hr>

			<h5>{{ Session::get('donation_message') }}</h5>

			<input id="stripeAmount" type="text" value="10.00">
			<button id="stripeDonate">Donate</button>
			
			<br>

			<p class="text-info">If you'd rather send BitCoin, please send to: <strong>1PmmrahDdE2ZRya3XSuAGK9HhPVriYYBs4</strong></p>
			
		</div>
	</div>
</div>
@stop

@section('scripts')
{!! HTML::script('https://checkout.stripe.com/checkout.js') !!}
@include('scripts.stripe-donation')
@stop
