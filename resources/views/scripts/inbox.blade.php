<script>
	$('.message').click(function()
	{
		if (!$(this).hasClass('read'))
		{
			$(this).addClass('read');

			var inboxCounter = $('#inboxCounter'),
				inboxCount = parseInt(inboxCounter.text());

			inboxCounter.text(inboxCount - 1);

			if (inboxCount - 1 === 0)
				inboxCounter.hide();

			$.post('{{ url("/me/unread/") }}/' + $(this).data('id'), {_token: "{{ csrf_token() }}", id: $(this).data('id')})
			.done(function(count)
			{
				if (count > 0)
					document.title = "Commentum [" + count + "]";
				else
					document.title = "Commentum";
			})
			.fail(function(res)
			{
				alert('Something went wrong.');
			});
		}
	});

	$('.markdown-input').keyup(function()
	{
		var previewer = $(this).parent().find('.markdown').first(),
			md 		  = $(this).val();

		previewer.html(marked(md));
	});

	function sendPrivateMessage(e, el)
	{
		e.preventDefault();

		var form   = $(el),
			loader = form.find('.loader').first(),
			button = form.find('.btn').first(),
			data   = form.serialize(),
			error  = form.find('.text-alert').first();

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
	}
</script>