<div class="message {{ ($message->read == true ? 'read' : '') }}" data-id="{{ Hashids::encode($message->id) }}">
	<h6><b>claimed a tag</b></h6>
	<div class="markdown">
		Nice! You claimed #{{ $message->tag()->display_title }}. You can change the tag's settings [here]({{ url('/t/' . $message->tag()->display_title . '/settings') }}).
	</div>
</div>