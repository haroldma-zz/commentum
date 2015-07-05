<script>
	$('#deleteThread').click(function()
	{
		if (confirm("Are you sure you want to delete this submission?"))
		{
			$.post("{{ url('/me/delete/thread') }}", {_token: "{{ csrf_token() }}", hashid: "{{ Hashids::encode($thread->id) }}"})
			.done(function()
			{
				window.location.href = "{{ Auth::user()->permalink() }}";
			})
			.fail(function(res)
			{
				alert(res.responseText);
			});
		}
	});
</script>