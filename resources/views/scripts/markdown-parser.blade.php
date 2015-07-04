<script>
	var markdown = $('body').find('.markdown');

	var usernameRegex = /(?:^|)(\/u\/\w+)(?!\w)/g,
		tagRegex      = /(?:^|)#(\w+)(?!\w)/g;

	$.each(markdown, function(i, el)
	{
		var md = $.trim($(el).text()),
			ht = marked(md.replace(usernameRegex, "[$1]($1)").replace(tagRegex, "[#$1](/t/$1)"));

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