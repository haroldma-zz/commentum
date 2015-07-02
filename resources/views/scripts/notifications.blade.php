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
			if (data != "none")
			{
				var box = $('.notification-box');

				if (data["multiple"] == true)
				{
					box.html("<center><a href='{{ url('/inbox') }}'><span>You have " + data["count"] + " unread message" + (data["count"] != 1 ? "s." : ".") + "</span></a></center>");
					document.title = "Commentum [" + data["total"] + "]";
					$('#inboxCounter').text(data["total"]);

					if ($('#inboxCounter').hasClass('hide'))
						$('#inboxCounter').removeClass('hide');
				}
				else
				{
					box.html("<center><a href='{{ url('/inbox') }}'><span>" + data["message"] + "</span></a></center>");
					document.title = "Commentum [" + data["total"] + "]";
					$('#inboxCounter').text(data["total"]);

					if ($('#inboxCounter').hasClass('hide'))
						$('#inboxCounter').removeClass('hide');
				}

				box.fadeIn("fast");
			}

			setTimeout(function()
			{
				checkNotifications();
			}, 5500);
		});
	}
</script>