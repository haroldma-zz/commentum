<script>
	$('#submitForm').submit(function(e)
	{
		e.preventDefault();

		var loader = $('#submitFormLoader'),
			form = $(this),
			data = form.serialize(),
			button = form.find('input[type=submit]').first(),
			error = $('#submitFormError');

		error.text('');
		loader.addClass('open');
		button.attr('disabled', true);

		$.post('{{ (isset($thread) ? url("/edit/thread") : url("/submit")) }}', data)
		.done(function(res)
		{
			window.location.href = res;
		})
		.fail(function(res)
		{
			error.text(res.responseText);
			loader.removeClass('open');
			button.attr('disabled', false);
		})
	});
</script>