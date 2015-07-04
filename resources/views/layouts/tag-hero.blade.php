<div class="tag-hero" style="background-image:url('{{ $tag->hero_img }}');">
	<div class="row">
		<div class="medium-8 medium-offset-2 text-center columns">
			<h2><b>#{{ $tag->display_title }}</b></h2>
			<div class="markdown">
				{{ $tag->description }}
			</div>
		</div>
	</div>
</div>