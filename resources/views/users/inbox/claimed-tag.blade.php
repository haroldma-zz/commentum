<div class="message">
	<h6>You claimed a tag</h6>
	<p>
		Nice! You claimed <a href="{{ $message->tag()->permalink() }}">#{{ $message->tag()->display_title }}</a>. You can change the tag's settings <a href="{{ url('/t/' . $message->tag()->display_title . '/settings') }}">here</a>.
	</p>
</div>