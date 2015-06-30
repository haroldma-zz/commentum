<script>
	$('#saveThread').click(function()
	{
		$.post('{{ url("/me/save/thread") }}', {_token: "{{ csrf_token() }}", hashid: "{{ Hashids::encode($thread->id) }}"})
		.done(function()
		{
			$('#saveThread').text('saved');
			$('#saveThread').css('pointer', 'disabled');
			$('#saveThread').removeAttr('id');
		})
		.fail(function(res)
		{
			$('#saveThread').text('unsave');
			console.log(res.responseText);
		});
	});
</script>