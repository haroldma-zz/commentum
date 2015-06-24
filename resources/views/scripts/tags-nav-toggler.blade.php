<script>
	$('#tagsNavToggler').click(function(e)
	{
		e.stopPropagation();
		$('#tagsNav').toggleClass('open');
	});

	$('.tag-nav-item').click(function()
	{
		window.location.href = $(this).find('a').attr('href');
	});

	$('body').click(function()
	{
		if ($('#tagsNav').hasClass('open'))
			$('#tagsNav').removeClass('open');
	});
</script>