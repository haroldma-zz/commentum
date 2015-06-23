<script>
	$(document).ready(function()
	{
		$.each($('.comment'), function(index, element)
		{
			var hierarchy = $(element).data('hierarchy');

			if (hierarchy == "parent")
				$(this).addClass('parent');
			else
				$(this).addClass('child');
		});

		$('.comment-box').submit(function(e)
		{
			e.preventDefault();

			var form = $(this),
				formData = form.serialize();

			$.post('{{ url("/comment") }}', formData)
			.done(function(res)
			{
				var markdown = form.find('textarea').val(),
					md = $.trim(markdown),
					pr = new showdown.Converter(),
					ht = pr.makeHtml(md);

				var html = '<article class="comment ' + form.data('hierarchy') + '">';
					html += '<section class="markdown">';
					html += ht;
					html += '</section>';
					html += '<footer>';
					html += '<span>0 points</span>';
					html += '&middot;';
					html += '<span class="livestamp"></span> by <a href="{{ Auth::user()->permalink() }}">{{ Auth::user()->username }}</a>';
					html += '&middot;';
					html += '<a class="reply-comment">reply</a>';
					html += '</footer>';
					html += '<div class="children"></div>';
					html += '<div class="reply-box">';
// <form method="POST" action="http://ask.dev/comment" accept-charset="UTF-8" class="row comment-box" data-hierarchy="child"><input name="_token" type="hidden" value="lRRRUF0b2duqS6TJNVrA1HS6tGVlJWvzZU2OF58s">
// 	<input name="thread_id" type="hidden" value="Ydk">
// 	<input name="parent_id" type="hidden" value="Ydk">
// 	<div class="medium-5 columns">
// 		<p class="no-margin">
// 			You can use <a href="http://ask.dev">Markdown</a>.
// 		</p>
// 		<textarea rows="4" name="markdown" cols="50"></textarea>
// 		<p class="text-alert"></p>
// 		<input class="btn" type="submit" value="Reply">
// 	</div>
// </form>
					html += '</div>';
					html += '</article>';

				form.parent().parent().find('.children').first().prepend(html);
				form.trigger('reset');

				var livestamp = $('.livestamp').first();
				livestamp.livestamp(new Date());
				livestamp.removeClass('livestamp');
			})
			.fail(function(res)
			{
				form.parent().find('.text-alert').first().text(res.responseText);
			});
		});

		$('.reply-comment').click(function()
		{
			$(this).parent().parent().find('.reply-box').first().toggle();
		});
	});
</script>