<script>
	$('#saveThread').click(function()
	{
		var state,
			thread = $(this);

		if (thread.text() == 'save')
			state = "save";
		else
			state = "unsave";

		thread.html('<img src="{{ url('/img/three-dots.svg') }}" width="30px">');

		$.post('{{ url("/me/save/thread") }}', {_token: "{{ csrf_token() }}", hashid: thread.data('hashid')})
		.done(function(res)
		{
			if (state == 'save')
				thread.text('unsave');
			else
				thread.text('save');
		})
		.fail(function(res)
		{
			alert(res.responseText);
		});
	});

	$('.save-comment').click(function()
	{
		var state,
			comment = $(this);

		if (comment.text() == 'save')
			state = "save";
		else
			state = "unsave";

		comment.html('<img src="{{ url('/img/three-dots-blue.svg') }}" width="30px">');

		$.post('{{ url("/me/save/comment") }}', {_token: "{{ csrf_token() }}", hashid: comment.data('hashid')})
		.done(function(res)
		{
			if (state == 'save')
				comment.text('unsave');
			else
				comment.text('save');
		})
		.fail(function(res)
		{
			alert(res.responseText);
		});
	});
</script>