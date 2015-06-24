<div class="message">
	<h6>
		<a href="{{ $message->from()->permalink() }}">
			{{ $message->from()->username }}
		</a>
		commented on your <a href="{{ $message->thread()->permalink() }}">comment</a>
	</h6>
	<div class="markdown">
		{!! $message->message !!}
	</div>
	<hr>
</div>