<script>
	var markdown = $('body').find('.markdown');

	$.each(markdown, function(i, el)
	{
		var md = $.trim($(el).text()),
			ht = marked(md);

		$(el).html(ht);
	});

	$.each($('.comment'), function(index, element)
	{
		var hierarchy = $(element).data('hierarchy');

		if (hierarchy == "parent")
			$(this).addClass('parent');
		else
			$(this).addClass('child');
	});
</script>