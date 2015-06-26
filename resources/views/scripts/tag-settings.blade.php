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
			window.location.href = '{{ $tag->permalink() }}/settings';
		})
		.fail(function(res)
		{
			$('#submitFormError').text(res.responseText);
		});
	});

	$('#addModField').click(function()
	{
		var html = '{!! Form::text('mods[]') !!}';

		$('#modFields').append($.trim(html));
	});
</script>