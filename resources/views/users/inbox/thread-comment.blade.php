<div class="message">
	<h6>
		<a href="{{ $message->from()->permalink() }}">
			{{ $message->from()->username }}
		</a>
		commented on your thread
		<a href="{{ $message->thread()->permalink() }}">
			{{ $message->thread()->title }}
		</a>
	</h6>
	<div class="markdown">
		{!! $message->message !!}
	</div>
</div>