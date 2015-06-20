<div class="row">
	<div class="medium-8 columns">
		<h6>
			<a class="menu-item active">subscribed <i class="ion-arrow-down-b"></i></a>
			<a class="menu-item" href="{{ Auth::user()->permalink() }}">profile</a>
			<a class="menu-item" href="{{ url('/inbox') }}">inbox <span class="inbox-counter">1</span></a>
			<a class="menu-item" href="{{ url('/preferences') }}">preferences</a>
		</h6>
	</div>
</div>