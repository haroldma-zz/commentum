<div class="message {{ ($message->read == true ? 'read' : '') }}" data-id="{{ Hashids::encode($message->id) }}">
	<h6>
		<b>private message from <a href="{{ $message->from()->permalink() }}">/u/{{ $message->from()->username }}</a></b>
		<br>
		<div>
			<span data-livestamp="{{ strtotime($message->created_at) }}"></span>
		</div>
	</h6>
	<section class="markdown">
		{{  $message->message }}
	</section>
	<footer>
		<a onclick="toggleReplyBox(this)">reply</a>
	</footer>
	<div class="reply-box">
		{!! Form::open(['url' => '', 'class' => 'panel', 'onsubmit' => 'sendPrivateMessage(event, this)']) !!}
		<h6 class="super-header">Reply to private message</h6>
		{!! Form::label('To:') !!}
		{!! Form::text('to', $message->from()->username) !!}
		<?php
			$theMessage = '';

			foreach(explode("\n", $message->message) as $line)
			{
				$theMessage .= '> ' . $line;
			}
		?>
		{!! Form::label('Message') !!}
		{!! Form::textarea('markdown', $theMessage, ['class' => 'markdown-input']) !!}
		<p class="text-alert" id="error"></p>
		{!! Form::submit('Send message', ['class' => 'btn blue']) !!}
		&nbsp;
		<img src="{{ url('/img/three-dots-blue.svg') }}" width="35px" class="loader">
		<br>
		<br>
		<div class="preview">
			<h6 class="super-header">Live Preview</h6>
			<div class="markdown">
				{!! $theMessage !!}
			</div>
		</div>
		{!! Form::close() !!}
	</div>
</div>
