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
</script>