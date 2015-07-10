{!! HTML::script('/js/stanzaio.bundle.min.js') !!}
<script>

	/**
	* Nicer logging output for chat-related shit
	*/
	var chatLog = function(message, dump)
	{
		console.log('<<CHAT>> ' + message);
		if(dump)
			console.log(dump);
	}


	/**
	 * Connect with the XMPP server
	 * using WebSockets.
	 */
	var client;
	var roster;
	var loggedIn = false;

	var authenticationResult = function(success)
	{
		if(success) {
			chatLog('Authentication successful -- logged in!')
			loggedIn = true;
		} else {
			chatLog('Authentcation failed!')
			$('#roster').html("<li class='error-li'>Couldn't connect to the chat server.<br><br><a class='btn success medium' id='connectToChat'>Try again</a></li>");
		}
	}

	var sessionStarted = function()
	{
		getRoster();
		sendPresence();
	}

	var getRoster = function()
	{
		client.getRoster().then(function(data)
	    {
	    	roster = data.roster;

	    	$('#roster').html("");

	    	$.each(roster.items, function(index, user)
	    	{
	    		if(user.subscription == 'both') {												// BOTH USERS ARE SUBSCRIBE TO EACH OTHER (they're friends)
	    			$('#roster').append('<li><span class="indicator"><i class="ion-record"></i></span> ' + user.jid.local + '</li>');
	    		} else if(user.subscription == 'none' && user.subscriptionRequested) {			// CURRENT USER HAS REQUESTED OTHER USER
    				$('#roster').append('<li><span class="indicator"><i class="ion-record"></i></span> ' + user.jid.local + ' (requested)</li>');
    			} else if(user.subscription == 'to') {											// NOT SURE YET
    				chatLog("DETECTED 'to' SUBSCRIPTION!", user);
    			} else if(user.subscription == 'from') {										// NOT SURE YET
    				chatLog("DETECTED 'from' SUBSCRIPTION!", user);
	    		}
	    	});

	    	chatLog('Roster retrieved!', data);
	    });
	}

	var sendPresence = function()
	{
		client.sendPresence();
	    $('#userStatusIndicator').removeClass('error').addClass('online');

	    chatLog('Presence sent!')
	}

	var subscriptionRequest = function(data)
	{
		chatLog("Received subscription request!", data);
	}

	var subscriptionRemoved = function(data)
	{
		chatLog("Subscription removed!", data);
	}

	var receivedMessage = function(message)
	{
		chatLog('Received chat message!', message);

		$('#chatMessages').append('<li>' + message.body + '</li>');
		var cmb = $("#chatMessagesWindow");
		cmb.animate({ scrollTop: cmb.prop("scrollHeight") - cmb.height() }, 1);
	}

	var sentMessage = function(message)
	{
		$('#chatMessages').append('<li class="green">' + message.body + '</li>');
		var cmb = $("#chatMessagesWindow");
		cmb.animate({ scrollTop: cmb.prop("scrollHeight") - cmb.height() }, 1);

		chatLog('Sent chat message!', message);
	}

	var connectClient = function()
	{
		/*
		* Create the XMPP client
		*/
		client = XMPP.createClient(
		{
		    jid: '{{ Auth::user()->username }}@commentum.io',
		    password: '{{ Auth::user()->xmpp_password }}',
		    transport: 'websocket',
		    wsURL: 'wss://chat.commentum.io:8443/websocket'
		});

		/*
		* Handle authentication result
		*/
		client.on('auth:success', function()
		{
			authenticationResult(true);
		});
		client.on('auth:failed', function()
		{
			authenticationResult(false);
		});

		/*
		* Session events
		*/
		client.on('session:started', function ()
		{
			chatLog("Received 'session:started' event!");
			sessionStarted();
		});
		client.on('session:bound', function (data)
		{
			chatLog("Received 'session:bound' event!", data);
		});
		client.on('session:end', function (data)
		{
			chatLog("Received 'session:end' event!", data);
		});
		client.on('session:error', function (data)
		{
			chatLog("Received 'session:error' event!", data);
		});

		/*
		* Roster events
		*/
		client.on('roster:update', function(data)
		{
			chatLog("Received 'roster:ver' event!", data);
			getRoster();
		});
		client.on('roster:ver', function(data)
		{
			chatLog("Received 'roster:ver' event!", data);
		});

		/*
		* Subscription/unsubscription events
		*/
		client.on('subscribed', function(data)
		{
			chatLog("Received 'subscribed' event!", data);
		});
		client.on('subscribe', function(data)
		{
			subscriptionRequest(data);
		});
		client.on('unsubscribed', function(data)
		{
			chatLog("Received 'unsubscribed' event!", data);
		});
		client.on('unsubscribe', function(data)
		{
			subscriptionRemoved(data);
		});

		/*
		* On presence updates
		*/
		client.on('presence', function(data) 
		{
			chatLog("Received 'presence' event!", data);
		});
		client.on('presence:error', function(data) 
		{
			chatLog("Received 'presence:error' event!", data);
		});

		/*
		* User receives available/unable
		*/
		client.on('available', function(data)
		{
			chatLog("Received 'available' event!", data);
		});
		client.on('unavailable', function(data)
		{
			chatLog("Received 'unavailable' event!", data);
		});

		/*
		* User receives block/unblock
		*/
		client.on('block', function(data)
		{
			chatLog("Received 'block' event!", data);
		});
		client.on('unblock', function(data)
		{
			chatLog("Received 'unblock' event!", data);
		});

		/*
		* User receives/sends a carbon
		*/
		client.on('carbon:received', function(data)
		{
			chatLog("Received 'carbon:received' event!", data);
		});
		client.on('carbon:sent', function(data)
		{
			chatLog("Received 'carbon:sent' event!", data);
		});

		/*
		* User receives a message/chat state
		*/
		client.on('chat', function (msg)
		{
			receivedMessage(msg);
		});
		client.on('chat:state', function(data)
		{
			chatLog("Received 'chat:state' event!", data);
		});

		/*
		* When user's message is sent, display it
		*/
		client.on('message', function(data)
		{
			chatLog("Received 'message' event!", data);
		});
		client.on('message:sent', function (msg)
		{
			sentMessage(msg);
		});
		client.on('message:error', function(data)
		{
			chatLog("Received 'message:error' event!", data);
		});

		/*
		* Attention event
		*/
		client.on('attention', function(data)
		{
			chatLog("Received 'attention' event!", data);
		})
		
		/*
		* Finally, connect the XMPP client
		*/
		client.connect();
	}



	/**
	 * Disable new line insert
	 * on enter in the chat input
	 * area.
	 */
	$('#chatInput').keydown(function(e)
	{
		if (e.keyCode === 13)
			return false;
	});

	/**
	 * Send message on enter,
	 * if the chat input is not
	 * empty.
	 */
	$('#chatInput').keyup(function(e)
	{
		var keyCode = e.keyCode;

		if (keyCode === 13)
		{
			var input = $(this).val();

			if (input != "")
			{
				client.sendMessage(
				{
					to: currentUser + "@commentum.io",
					from: "{{ Auth::user()->username }}@commentum.io",
					body: input
				});

				$(this).val("");
			}
		}
	});

	if ($.cookie('chatPadding') != undefined)
	{
		$('.chat-bar').toggleClass('open');
		$('#chatChevron').toggleClass('ion-chevron-up ion-chevron-down');
		$('body').addClass('chat-fixed');
	}

	/**
	 * Show/hide the chat list
	 * sidebar.
	 */
	$('#chatToggler').click(function()
	{
		if ($('#chatbox').hasClass('open'))
		{
			$('#chatbox').removeClass('open');

			setTimeout(function()
			{
				$('.chat-bar').toggleClass('open');
				$('#chatChevron').toggleClass('ion-chevron-up ion-chevron-down');
				$('body').removeClass('chat-fixed');
				$.removeCookie('chatPadding');
			}, 200);
		}
		else
		{
			if ($.cookie('chatPadding') != undefined)
				$.removeCookie('chatPadding');
			else
				$.cookie('chatPadding', true);

			$('.chat-bar').toggleClass('open');
			$('#chatChevron').toggleClass('ion-chevron-up ion-chevron-down');
			$('body').toggleClass('chat-fixed');
		}
	});

	/**
	 * Show/hide the chatbox and
	 * give the user feedback of
	 * with whom he/she is chatting.
	 */
	var currentUser = null;

	$('.chat-list').on('click', 'li', function()
	{
		var item = $(this),
			user = item.text().trim(),
			chbx = $('#chatbox');

		if (item.hasClass('error-li'))
		{
			return false;
		}

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

	/**
	 * Try to connect to the server
	 * if it failed.
	 */
	$('.chat-list').on('click', '.error-li', function()
	{
		$('#roster').html('<br><center><img src="{{ url('/img/loader.svg') }}" width="30px"></center>');
		connectClient();
	});

	/**
	 * Handle click on #addUserBtn
	 */
	$('#addUserBtn').click(function()
	{
		var userInput = $('#addUserInput').val();

		if (userInput == "")
		{
			$('#addUserInput').focus();
			return false;
		}

		if (loggedIn == false)
		{
			alert("Can't connect to the chat server.");
			return false;
		}

		client.subscribe(userInput + '@commentum.io');
		$('#addUserInput').val("");
	});

	/**
	 * Connect the fucking client.
	 */
	connectClient();
</script>