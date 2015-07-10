<div class="subscriptions-list" id="tagsNav">
	<div class="row">
		<div class="medium-3 columns">
			<ul class="no-bullet">
				<li class="subscriptions-list-item animated fadeInUp"><a href="{{ url('/') }}">Front</a></li>
				<li class="subscriptions-list-item animated fadeInUp"><a href="{{ Auth::user()->permalink() }}/saved">Saved</a></li>
				<hr>
				@foreach(Auth::user()->subscriptions() as $s)
				<li class="subscriptions-list-item animated fadeInUp"><a href="{{ $s->tag()->permalink() }}">#{{ $s->tag()->display_title }}</a></li>
				@endforeach
			</ul>
		</div>
	</div>
</div>