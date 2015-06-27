<script>
	var hideExploreListItems;

	$('#exploreListButton').click(function(e)
	{
		e.stopPropagation();

		clearTimeout(hideExploreListItems);

		$('#exploreList').toggleClass('open');
		$('#exploreList').find('li').show();
		$('#exploreList').niceScroll({zindex:-99});
	});

	$('body').click(function()
	{
		if ($('#tagsNav').hasClass('open'))
			$('#tagsNav').removeClass('open');

		if ($('#exploreList').hasClass('open'))
			$('#exploreList').removeClass('open');

		hideExploreListItems = setTimeout(function()
		{
			$('#exploreList').find('li').hide();
		}, 300);
	});

	$('#exploreList > li').click(function()
	{
		window.location.href = $(this).find('a').first().attr('href');
	});
</script>