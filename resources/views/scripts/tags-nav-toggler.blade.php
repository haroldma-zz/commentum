<script>
	$('#tagsNavToggler').click(function()
	{
		$('#tagsNav').toggleClass('open');
	});

	$('.tag-nav-item').click(function()
	{
		window.location.href = $(this).find('a').attr('href');
	});
</script>