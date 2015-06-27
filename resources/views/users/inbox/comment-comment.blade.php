<div class="message {{ ($message->read == true ? 'read' : '') }}" data-id="{{ Hashids::encode($message->id) }}">
	<h6>
		<b>reply from <a href="{{ $message->from()->permalink() }}">/u/{{ $message->from()->username }}</a></b>
		&nbsp;&nbsp;
		<a href="{{ $message->thread()->permalink() }}">
			{{ $message->thread()->title }}
		</a>
		<br>
		<div>
			in <a href="{{ $message->thread()->tag()->permalink() }}">#{{ $message->thread()->tag()->display_title }}</a>
			<span data-livestamp="{{ strtotime($message->created_at) }}"></span>
		</div>
	</h6>
	<div class="markdown">
		{!! $message->message !!}
	</div>
</div>