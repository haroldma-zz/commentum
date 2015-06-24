<div class="tag-hero" style="background-image:url('{{ $tag->hero_img }}');">
	<div class="row">
		<div class="medium-8 medium-offset-2 text-center columns">
			<h2><b>#{{ $tag->display_title }}</b></h2>
			<div class="markdown">
				{{ $tag->description }}
			</div>
			<h4>
				<small>
					<span>{{ $tag->subscriberCount() }} subscriber{{ $tag->subscriberCount() != 1 ? 's' : '' }}</span>
					&middot;
					<span>{{ $tag->threadCount() }} submission{{ ($tag->threadCount() != 1 ? 's' : '') }}</span>
				</small>
			</h4>
		</div>
	</div>
</div>