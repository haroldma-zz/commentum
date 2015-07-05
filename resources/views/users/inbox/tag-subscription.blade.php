<div class="message {{ ($message->read == true ? 'read' : '') }}" data-id="{{ Hashids::encode($message->id) }}">
	<h6>
		<b>new subscriber</b>
		<br>
		<div>
			<span data-livestamp="{{ strtotime($message->created_at) }}"></span>
		</div>
	</h6>
	<div class="markdown">
		/u/{{ $message->from()->username }} subscribed to your tag #{{ $message->tag()->display_title }}.
	</div>
</div>