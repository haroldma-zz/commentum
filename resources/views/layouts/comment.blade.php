<article class="comment wow fadeInUp" data-wow-duration="0.25s" data-wow-delay="0.25s" data-hierarchy="{{ ($indent % 2 == 0 ? 'parent' : 'child') }}">
	<header>
		<span class="collapser"><i class="ion-chevron-up"></i></span>
		<span>
			@if(is_null($c->deleted_at))
			<a href="{{ $c->author()->permalink() }}">
				{!! ($c->author()->id == $c->thread()->user_id ? '<span class="username-tag op">OP</span> ' : '') !!}
				{{ $c->author()->username }}
			</a>
			@else
			[deleted]
			@endif
		</span>
		&middot;
		<span class="comment-momentum">{{ floor($c->momentum) }} points</span>
		&middot;
		<span data-livestamp="{{ strtotime($c->created_at) }}"></span>
	</header>
	<div class="body">
		<div class="markdown content-embeddable">
			{{ (is_null($c->deleted_at) ? $c->markdown : "[deleted]") }}
		</div>
		@if (Auth::check() && Auth::id() === $c->author_id)
		<section class="hide comment-editor">
			{!! Form::open(['url' => '/me/edit/comment', 'class' => 'edit-comment-form']) !!}
			{!! Form::hidden('hashid', Hashids::encode($c->id)) !!}
			<p class="no-margin">
				You can use <a href="{{ url('/') }}">Markdown</a>.
			</p>
			{!! Form::textarea('markdown', $c->markdown, ['class' => 'comment-editor-textarea', 'rows' => 4]) !!}
			<div class="preview">
				<h6 class="super-header">Live Preview</h6>
				<div class="markdown">{{ $c->markdown }}</div>
			</div>
			<p class="text-alert"></p>
			{!! Form::submit('Save', ['class' => 'btn blue']) !!}
			&nbsp;
			<img src="{{ url('/img/three-dots-blue.svg') }}" width="35px" class="loader">
			{!! Form::close() !!}
			<hr>
		</section>
		@endif
		<footer>
			<a onclick="toggleReplyBox(this)" data-thread="{{ Hashids::encode($threadId) }}" data-comment="{{ Hashids::encode($c->id) }}" {{ (Auth::check() == false ? 'href=/login' : '') }}>reply</a>
			<a href="{{ $c->permalink() }}">permalink</a>
			@if (!is_null($c->parent()))
			<a href="{{ $c->context() }}">context</a>
			@endif
			@if (Auth::check() && is_null($c->deleted_at))
			<a class="save-comment" data-hashid="{{ Hashids::encode($c->id) }}">{{ (Auth::user()->savedComment($c->id) == true ? "un" : "") }}save</a>
			@endif
			@if (Auth::check() && Auth::id() === $c->author_id && is_null($c->deleted_at))
			<a class="edit-comment">edit</a>
			<a class="delete-comment" data-hashid="{{ Hashids::encode($c->id) }}">delete</a>
			@endif
		</footer>
		<div class="reply-box">
			{!! Form::open(['url' => '/comment', 'class' => 'row comment-box', 'data-hierarchy' => ($indent % 2 == 0 ? 'child' : 'parent'), 'onsubmit' => 'submitComment(event, this)']) !!}
				{!! Form::hidden('thread_id', Hashids::encode($threadId)) !!}
				{!! Form::hidden('parent_id', Hashids::encode($c->id)) !!}
				<div class="medium-5 columns">
					<p class="no-margin">
						You can use <a href="{{ url('/') }}">Markdown</a>.
					</p>
					{!! Form::textarea('markdown', '', ['rows' => 4, 'class' => 'comment-textarea']) !!}
					<div class="preview hide">
						<h6 class="super-header">Live Preview</h6>
						<div class="markdown"></div>
					</div>
					<p class="text-alert"></p>
					{!! Form::submit('Reply', ['class' => 'btn']) !!}
				</div>
			{!! Form::close() !!}
		</div>
		<div class="children">
			{!! $c->printChildren($indent + 1) !!}
		</div>
	</div>
</article>
