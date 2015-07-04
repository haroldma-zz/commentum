<script>
	var usernameRegex = /(?:^|)(\/u\/\w+)(?!\w)/g,
		tagRegex      = /(?:^|)#(\w+)(?!\w)/g;

	$('#input').keyup(function()
	{
		$('#preview').html(marked($(this).val().replace(usernameRegex, "[$1]($1)").replace(tagRegex, "[#$1](/t/$1)")));
	});

	$('#form').submit(function(e)
	{
		e.preventDefault();

		var loader = $('#loader'),
			button = $('#button'),
			data   = $(this).serialize(),
			error  = $('#error');

		error.text('');
		loader.addClass('open');
		button.attr('disabled', true).addClass('disabled');

		$.post("{{ url('/me/pm') }}", data)
		.done(function()
		{
			window.location.href = "{{ url('/inbox') }}";
		})
		.fail(function(res)
		{
			error.text(res.responseText);
			loader.removeClass('open');
			button.attr('disabled', false).removeClass('disabled');
		});
	});
</script>