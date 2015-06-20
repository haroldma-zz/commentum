<script>
	var markdown = $('body').find('.markdown');

	$.each(markdown, function(i, el)
	{
		var md = $.trim($(el).text()),
			pr = new showdown.Converter(),
			ht = pr.makeHtml(md);

		$(el).html(ht);
	});
</script>