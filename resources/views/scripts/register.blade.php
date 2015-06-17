<script>
	$('#registerForm').submit(function(e)
	{
		e.preventDefault();

		var loader = $('#registerFormLoader'),
			form = $(this),
			data = form.serialize(),
			error = form.find('.text-alert').first();

		error.text('');
		loader.addClass('open');

		$.post('{{ url("/register") }}', data)
		.done(function()
		{
			window.location.href = '{{ url("/") }}';
		})
		.fail(function(res)
		{
			error.text(res.responseText);
			loader.removeClass('open');
		})
	});
	$('#loginForm').submit(function(e)
	{
		e.preventDefault();

		var loader = $('#loginFormLoader'),
			form = $(this),
			data = form.serialize(),
			error = form.find('.text-alert').first();

		error.text('');
		loader.addClass('open');

		$.post('{{ url("/login") }}', data)
		.done(function()
		{
			window.location.href = '{{ url("/") }}';
		})
		.fail(function(res)
		{
			error.text(res.responseText);
			loader.removeClass('open');
		})
	});
</script>