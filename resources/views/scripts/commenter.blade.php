<script>
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

			var html = '<article>';
				html += '<section class="markdown">';
				html += ht;
				html += '</section>';
				html += '<footer>';
				html += '<span data-livestamp="{{ strtotime(time()) }}"></span> by <a href="{{ Auth::user()->permalink() }}">{{ Auth::user()->username }}</a>';
				html += '&middot;';
				html += '<a>reply</a>';
				html += '</footer>';
				html += '<div class="children"></div>';
				html += '</article>';

			$('#commentsList').prepend(html);
			form.trigger('reset');
		})
		.fail(function(res)
		{
			console.log(res);
		});
	});
</script>