<script>
	setTimeout(function()
	{
		if ($.cookie('hideUserHeader') != undefined)
		{
			$('.hero').removeClass('closed');
			$('#showHero').addClass('hide');
		}
	});
</script>