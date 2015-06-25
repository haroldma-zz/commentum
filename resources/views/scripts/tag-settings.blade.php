<script>
	$('#settingsForm').submit(function(e)
	{
		e.preventDefault();

		var form = $(this),
			data = form.serialize();

		$('#submitFormError').text('');

		$.post('{{ $tag->permalink() . "/settings" }}', data)
		.done(function()
		{
			window.location.href = '{{ $tag->permalink() }}';
		})
		.fail(function(res)
		{
			$('#submitFormError').text(res.responseText);
		});
	});
</script>