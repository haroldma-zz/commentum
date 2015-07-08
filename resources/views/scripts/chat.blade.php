{!! HTML::script('/js/stanzaio.bundle.min.js') !!}
<script>
	$('#chatToggler').click(function()
	{
		if ($('#chatbox').hasClass('open'))
		{
			$('#chatbox').removeClass('open');

			setTimeout(function()
			{
				$('.chat-bar').toggleClass('open');
				$('#chatChevron').toggleClass('ion-chevron-up ion-chevron-down');
			}, 200);
		}
		else
		{
			$('.chat-bar').toggleClass('open');
			$('#chatChevron').toggleClass('ion-chevron-up ion-chevron-down');
		}
	});

	var currentUser = null;

	$('.chat-list > li').click(function()
	{
		var item = $(this),
			user = item.data('user'),
			chbx = $('#chatbox');

		if (user == currentUser)
		{
			currentUser = null;
			chbx.removeClass('open');
		}
		else
		{
			currentUser = user;

			$('#chatHeader').text('Chatting with /u/' + currentUser);

			if (chbx.hasClass('open'))
			{
				chbx.removeClass('open');
				setTimeout(function()
				{
					chbx.addClass('open');
				}, 200);
			}
			else
			{
				chbx.addClass('open');
			}
		}
	});

	var client;

	(function()
	{
		client = XMPP.createClient({
		    jid: 'sharif@commentum.io',
		    password: '_Commentum2734',
		    transport: 'websocket',
		    wsURL: 'ws://chat.commentum.io:5280/websocket'
		});

		client.on('session:started', function () {
		    client.getRoster();
		    client.sendPresence();
		    console.log('fuck');
		});

		client.on('chat', function (msg) {
		    client.sendMessage({
		      to: msg.from,
		      body: 'You sent: ' + msg.body
		    });
		});

		client.connect();
	})();
</script>