<script>
	var submitComment = function(e, el)
	{
		e.preventDefault();

		var form = $(el),
			formData = form.serialize();

		$.post('{{ url("/comment") }}', formData)
		.done(function(res)
		{
			var markdown = form.find('textarea').val(),
				md = $.trim(markdown),
				pr = new showdown.Converter(),
				ht = pr.makeHtml(md)
				hier = (form.data('hierarchy') == 'parent') ? 'child' : 'parent';

			var commentCount = $('#threadCommentCount'),
				count = parseInt(commentCount.text()) + 1;

			if (count == 1)
				$('#threadCommentPlural').text('');
			else
				$('#threadCommentPlural').text('s');

			commentCount.text(count);

			if (form.parent().hasClass('r-b'))
				form.parent().hide();

			if (res['parentMomentum'] != 'whoopie')
			{
				if (res['parentMomentum'] == 1)
					var parentMomentum = '1 point';
				else
					var parentMomentum = res['parentMomentum'] + ' points';

				form.parent().parent().find('.comment-momentum').first().text(parentMomentum);
				console.log(parentMomentum);
			}

			var html = '<article class="comment ' + form.data('hierarchy') + '">';
				html += '<header>';
				html += '<span><a href="{{ Auth::user()->permalink() }}">{{ Auth::user()->username }}</a></span>';
				html += '&middot;';
				html += '<span>0 points</span>';
				html += '&middot;';
				html += '<span class="livestamp"></span>';
				html += '</header>';
				html += '<section class="markdown">';
				html += ht;
				html += '</section>';
				html += '<footer>';
				html += '<a onclick="toggleReplyBox(this)">reply</a>';
				html += '</footer>';
				html += '<div class="reply-box r-b">';
				html +=	'<form method="POST" action="http://ask.dev/comment" accept-charset="UTF-8" class="row comment-box" data-hierarchy="'+hier+'" onsubmit="submitComment(event, this)">';
				html += '<input name="_token" type="hidden" value="{{ csrf_token() }}">';
				html += '<input name="thread_id" type="hidden" value="' + res['threadId'] + '">';
				html += '<input name="parent_id" type="hidden" value="' + res['commentId'] + '">';
				html += '<div class="medium-5 columns">';
				html += '<p class="no-margin">';
				html += 'You can use <a href="http://ask.dev">Markdown</a>.';
				html += '</p>';
				html += '<textarea rows="4" name="markdown" cols="50"></textarea>';
				html += '<p class="text-alert"></p>';
				html += '<input class="btn" type="submit" value="Reply">';
				html += '</div>';
				html += '</form>'
				html += '</div>';
				html += '<div class="children"></div>';
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
	}

	var toggleReplyBox = function(el)
	{
		$(el).parent().parent().find('.reply-box').first().toggle();
	};
</script>