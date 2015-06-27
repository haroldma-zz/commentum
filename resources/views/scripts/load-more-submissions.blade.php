<script>
	var canLoadMore = true;

	$('#loadMoreSubmissions').click(function()
	{
		var button = $(this);

		if (canLoadMore)
		{
			button.removeClass('error').addClass('loading');
			button.html('<img src="{{ url("/img/dark-loader.svg") }}" width="25">');

			$.get('{{ url("/more/front") }}')
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
					$('.questions-list').append(data);
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