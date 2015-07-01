<div class="comments-list children" id="commentsList">
	@foreach($comments as $c)
	<article class="comment saved wow fadeInUp" data-wow-duration="0.25s" data-wow-delay="0.25s" data-hierarchy="parent">
		<header>
			<span>
				<a href="{{ $c->author()->permalink() }}">
					{!! ($c->author()->id == $c->thread()->user_id ? '<span class="username-tag op">OP</span> ' : '') !!}
					{{ $c->author()->username }}
				</a>
			</span>
			&middot;
			<span class="comment-momentum">{{ floor($c->momentum) }} points</span>
			&middot;
			<span data-livestamp="{{ strtotime($c->created_at) }}"></span>
		</header>
		<div class="body">
			<section class="markdown">
				{{ $c->markdown }}
			</section>
			<footer>
				<a href="{{ $c->permalink() }}">permalink</a>
				@if (!is_null($c->parent()))
				<a href="{{ $c->context() }}">context</a>
				@endif
				<a class="save-comment" data-hashid="{{ Hashids::encode($c->id) }}">{{ (Auth::user()->savedComment($c->id) == true ? "un" : "") }}save</a>
			</footer>
		</div>
	</article>
	@endforeach
</div>