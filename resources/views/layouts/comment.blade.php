<article class="comment" data-hierarchy="{{ ($indent % 2 == 0 ? 'parent' : 'child') }}">
	<section class="markdown">
		{{ $c->markdown }}
	</section>
	<footer>
		<span>{{ floor($c->momentum) }} points</span>
		&middot;
		<span data-livestamp="{{ strtotime($c->created_at) }}"></span> by <a href="{{ $c->author()->permalink() }}">{{ $c->author()->username }}</a>
		&middot;
		<a class="reply-comment" data-thread="{{ Hashids::encode($threadId) }}" data-comment="{{ Hashids::encode($c->id) }}">reply</a>
	</footer>
	<div class="reply-box">
		{!! Form::open(['url' => '/comment', 'class' => 'row comment-box', 'data-hierarchy' => ($indent % 2 == 0 ? 'child' : 'parent')]) !!}
			{!! Form::hidden('thread_id', Hashids::encode($threadId)) !!}
			{!! Form::hidden('parent_id', Hashids::encode($c->id)) !!}
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
	<div class="children">
		{!! $c->printChildren($indent + 1) !!}
	</div>
</article>