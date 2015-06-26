<script>
	$('#exploreListButton').click(function(e)
	{
		e.stopPropagation();
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

		$('#tagsNav').find('li').hide();
		$('#exploreList').find('li').hide();
	});
</script>