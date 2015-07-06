<table class="questions-list">
	@if (!count($threads) > 0)
	<tr>
		<td>
			Nothing here...
		</td>
	</tr>
	@else
	@foreach ($threads as $t)
	<tr>
		<td>
			<a>{{ floor($t->calculateMomentum()) }}</a>
		</td>
		<td>
			{!! (!empty($t->link) ? '<i class="ion-link"></i>' : '') !!}
			<a href="{{ $t->titlePermalink() }}">{{ $t->title }}</a>
			<br>
			@if ($t->nsfw)
			<span class="thread-tag nsfw">nsfw</span>
			@endif
			@if ($t->serious)
			<span class="thread-tag serious">serious</span>
			@endif
			@if ($t->nsfw || $t->serious)
			<br>
			@endif
			<span>
				<a href="{{ $t->tag()->permalink() }}">
					<span data-livestamp="{{ strtotime($t->created_at) }}"></span> in
					#{{ $t->tag()->display_title }}
				</a>
				&middot;
				<a href="{{ url('/u/' . $t->author()->username) }}">{{ $t->author()->username }}</a>
				&middot;
				<a href="{{ $t->permalink() }}">{{ $t->commentCount() }} comment{{ ($t->commentCount() > 1 || $t->commentCount() === 0 ? 's' : '') }}</a>
			</span>
		</td>
	</tr>
	@endforeach
	@endif
</table>
@if (Session::get('moreSubmissionsCount') > 20)
<div id="loadMoreSubmissions">Load more submissions</div>
@endif