<script>
	$('#subscribeForm').submit(function(e)
	{
		e.preventDefault();

		var form = $(this),
			data = form.serialize();

		$.post('/t/{{ $tag->display_title }}/subscribe', data)
		.done(function()
		{
			$('#subscribeButton').text('Subscribed').addClass('inactive').attr('disabled', true);
		})
		.fail(function(res)
		{
			alert(res.responseText);
		});
	});
</script>