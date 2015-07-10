<script>
	var canLoadMore = true;
	var limit = {{ $limit }};
	var page = 0;

	$('#loadMoreSubmissions').click(function()
	{
		var button = $(this);

		if (canLoadMore)
		{
			button.removeClass('error').addClass('loading');
			button.html('<img src="{{ url("/img/dark-loader.svg") }}" width="25">');

            page++;
            var offset = limit * page;;

			$.get('?offset=' + offset)
			.done(function(data)
			{
				button.removeClass('loading');
				button.html('Load more submissions.');

				if (data == '')
				{
					button.html('No more submissions left.');
					canLoadMore = false;
				}
				else
				{
					$('.threads-list').append(data);
				}
			})
			.fail(function(res)
			{
				button.removeClass('loading').addClass('error');
				button.text(res.responseText);
			});
		}
	});
</script>