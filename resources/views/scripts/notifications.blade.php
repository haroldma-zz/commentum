<script>
	(function()
	{
		checkNotifications();
	})();

	function checkNotifications()
	{
		$.get('/me/notifications')
		.done(function(data)
		{
			var box = $('.notification-box');

			if (data["multiple"] == true)
			{
				box.html("<center><a href='{{ url('/inbox') }}'><span>You have " + data["count"] + " unread message" + (data["count"] != 1 ? "s." : ".") + "</span></a></center>");
			}
			else
			{
				box.html("<center><a href='{{ url('/inbox') }}'><span>" + data["message"] + "</span></a></center>");
			}

			box.fadeIn("fast");

			setTimeout(function()
			{
				checkNotifications();
			}, 5500);
		})
		.fail(function()
		{
			checkNotifications();
		});
	}
</script>