<script>
	$('.message').click(function()
	{
		$(this).addClass('read');

		var inboxCounter = $('#inboxCounter'),
			inboxCount = parseInt(inboxCounter.text());

		inboxCounter.text(inboxCount - 1);

		if (inboxCount - 1 === 0)
			inboxCounter.hide();
	});
</script>