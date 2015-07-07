@extends('layouts.default')

@section('page')
<div class="hero">
	<div class="row">
		<div class="medium-4 medium-offset-4 columns donate-page text-justify">
			<h3>Donate</h3>
			<br>
			<p>
				Running Commentum costs money -- a lot more than you'd think!
				We're hard at work, trying to make Commentum an even greater place to be.
				<br><br>
				You can help us pay our server costs by making a donation. Whatever amount you can help with is enough.
				We appreciate all the help we get. Thanks!
			</p>
			<br>
			<h5>{{ Session::get('donation_message') }}</h5>
			<input id="stripeAmount" type="text" value="$10.00">
			<br>
			<br>
			<center><button id="stripeDonate">Donate</button></center>
			<br>
			<br>
			<br>
			<p class="text-center">If you'd rather send BitCoin, please send to: <strong>1PmmrahDdE2ZRya3XSuAGK9HhPVriYYBs4</strong></p>
		</div>
	</div>
</div>
@stop

@section('scripts')
{!! HTML::script('https://checkout.stripe.com/checkout.js') !!}
@include('scripts.stripe-donation')
@stop
