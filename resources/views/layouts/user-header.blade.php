<div class="user-header" id="userHeader">
	<div class="row">
		<div class="medium-8 columns">
			<h6>
				<a id="tagsNavToggler" class="menu-item {{ (Request::segment(1) == '' ? 'active' : '') }}">subscriptions <i class="ion-arrow-down-b"></i></a>
				<a class="menu-item {{ (Request::segment(1) == 'u' && Request::segment(2) == Auth::user()->username ? 'active' : '') }}" href="{{ Auth::user()->permalink() }}">profile</a>
				<a class="menu-item {{ (Request::segment(1) == 'inbox' ? 'active' : '') }}" href="{{ url('/inbox') }}">
					inbox
					@if (Auth::user()->messageCount() > 0)
					<span class="inbox-counter">{{ Auth::user()->messageCount() }}</span>
					@endif
				</a>
				<a class="menu-item {{ (Request::segment(1) == 'preferences' ? 'active' : '') }}" href="{{ url('/preferences') }}">preferences</a>
			</h6>
		</div>
		<div class="medium-4 columns text-right">
			<h6 id="hideHero">
				<i class="ion-chevron-up"></i>
			</h6>
		</div>
	</div>
</div>