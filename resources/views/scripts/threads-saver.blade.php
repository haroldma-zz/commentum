<script>
	$('#saveThread').click(function()
	{
		var state;

		if ($('#saveThread').text() == 'save')
			state = "save";
		else
			state = "unsave";

		$('#saveThread').html('<img src="{{ url('/img/three-dots.svg') }}" width="30px">');

		$.post('{{ url("/me/save/thread") }}', {_token: "{{ csrf_token() }}", hashid: "{{ Hashids::encode($thread->id) }}"})
		.done(function(res)
		{
			if (state == 'save')
				$('#saveThread').text('unsave');
			else
				$('#saveThread').text('save');
		})
		.fail(function(res)
		{
			$('#saveThread').text('unsave');
		});
	});
</script>