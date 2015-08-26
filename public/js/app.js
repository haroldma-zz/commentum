// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
// $(document).foundation();

/**
 * To submit a form via AJAX, set up the form as you normally would
 * with Blade and give the form a class '.ajax-form'.
 *
 * - Make sure there's a <ul> element with the class '.text-alert'
 * 	 WITHIN the form.
 * - This works for multiple forms on the same page.
 * - You can add a loader with <img src="path/to/loader" class="loader">
 *
 * Things that should be added to this script:
 *    - Stay on the same page after successful submission
 *    - <p class="text-success"> element ^
 *    - ...
 */
$('.ajax-form').submit(function(e)
{
	e.preventDefault();

	var form  		   = $(this),
		data  		   = form.serialize(),
		href  		   = form.attr("action"),
		error 		   = form.find('.text-alert'),
		errorContainer = form.find('.error-container'),
		success 	   = form.find('.text-success'),
		successContainer = form.find('.success-container'),
		loader 		   = form.find('.loader');

	errorContainer.addClass('hide');
	error.text("");
	successContainer.addClass('hide');
	success.text("");
	loader.css('opacity', '1');

	$.post(href, data)
	.done(function(res)
	{
		successContainer.removeClass('hide');
		success.text(res);
		loader.css('opacity', 0);
		form.find('input[type="submit"]').attr('disabled', 'disabled').addClass('disabled');
	})
	.fail(function(res)
	{
		if (res.status == 401)
		{
			error.append('<li>' + res.responseText + '</li>');
			errorContainer.removeClass('hide');
			loader.css('opacity', '0');
		}
		else
		{
			$.each(res.responseJSON, function(k, v)
			{
				error.append('<li>' + v + '</li>');
			});

			errorContainer.removeClass('hide');
			loader.css('opacity', '0');
		}
	});
});