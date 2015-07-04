<script>
	@if(isset($thread))
	$('#preview').html(marked($('#submitForm textarea').last().val()));
	@endif


	$('#submitCheck').click(function()
	{
		$(this).addClass('hide');
		$('#submitButton').removeClass('hide');
		$('#submitFormError').addClass('hide').text('');

		var tag = $('#tag').val();

		if (tag ==  "")
		{
			$('#submitReadyText').removeClass('hide').text("Are you sure you want to submit this entry to #random?");
		}
		else
		{
			$('#submitReadyText').removeClass('hide').text("You're submitting this entry to #" + tag.replace(/(?:^|)#(\w+)(?!\w)/g, "$1") + ". If it doesn't exist, it will be claimed by you. Are you sure you want to continue?");
		}
	});

	$('#submitForm :input').keyup(function()
	{
		$('#submitCheck').removeClass('hide');
		$('#submitButton').addClass('hide');
		$('#submitFormError').addClass('hide').text('');
		$('#submitReadyText').addClass('hide').text('');
	});

	$('#submitForm textarea').last().keyup(function()
	{
		var input = $(this).val();

		$('#preview').removeClass('hide');
		$('#preview').html(marked(input));
	});

	$('#submitForm').submit(function(e)
	{
		e.preventDefault();

		var loader = $('#submitFormLoader'),
			form = $(this),
			data = form.serialize(),
			button = form.find('input[type=submit]').first(),
			error = $('#submitFormError'),
			submitReady = $('#submitReadyText');

		submitReady.text('');
		error.text('');
		loader.addClass('open');
		button.attr('disabled', true);
		button.addClass('disabled');

		$.post('{{ (isset($thread) ? url("/edit/thread") : url("/submit")) }}', data)
		.done(function(res)
		{
			window.location.href = res;
		})
		.fail(function(res)
		{
			error.removeClass('hide').text(res.responseText);
			loader.removeClass('open');
			button.attr('disabled', false);
			button.removeClass('disabled');
			$('#submitButton').addClass('hide');
			$('#submitCheck').removeClass('hide');
		})
	});
</script>