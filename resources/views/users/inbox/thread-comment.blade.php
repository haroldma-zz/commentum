<div class="message {{ ($message->read == true ? 'read' : '') }}" data-id="{{ Hashids::encode($message->id) }}">
	<h6>
		<b>comment from <a href="{{ $message->from()->permalink() }}">/u/{{ $message->from()->username }}</a></b>
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
	<section id="content-embeddable" class="markdown">
		{!! $message->comment()->markdown !!}
	</section>
	<footer>
		<a onclick="toggleReplyBox(this)">reply</a>
		<a href="{{ $message->comment()->permalink() }}">permalink</a>
	</footer>
	<div class="reply-box">
		{!! Form::open(['url' => '/comment', 'class' => 'row comment-box', 'data-hierarchy' => 'child', 'onsubmit' => 'submitComment(event, this)']) !!}
			{!! Form::hidden('thread_id', Hashids::encode($message->thread()->id)) !!}
			{!! Form::hidden('parent_id', Hashids::encode($message->comment()->id)) !!}
			<div class="medium-5 columns">
				<p class="no-margin">
					You can use <a href="{{ url('/') }}">Markdown</a>.
				</p>
				{!! Form::textarea('markdown', '', ['rows' => 4]) !!}
				<p class="text-alert"></p>
				{!! Form::submit('Reply', ['class' => 'btn']) !!}
			</div>
		{!! Form::close() !!}
	</div>
</div>