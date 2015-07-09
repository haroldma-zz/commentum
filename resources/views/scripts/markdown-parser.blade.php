<script>
	var markdown = $('body').find('.markdown');

	$.each(markdown, function(i, el)
	{
		var md = $.trim($(el).text()),
			ht = marked(md.replace({{ \REGEXES::PAGES }}, "{{ \REGEXES::PAGES_RP }}").replace({{ \REGEXES::USERNAMES }}, "{{ \REGEXES::USERNAMES_RP }}").replace({{ \REGEXES::TAGS }}, "{{ \REGEXES::TAGS_RP }}"));

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
