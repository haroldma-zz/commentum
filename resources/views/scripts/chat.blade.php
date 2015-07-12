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
	var roster = [];
	var incoming_requests = [];
	var outgoing_requests = [];

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
		getChatList();
		sendPresence();
	}

/*
	var updateRoster = function()
	{
		$('#roster').html("");
		$.each(roster, function(index, user)
	    {
    		if(user.subscription == 'both') {												// BOTH USERS ARE SUBSCRIBE TO EACH OTHER (they're friends)
    			$('#roster').append('<li><span class="indicator"><i class="ion-record"></i></span> ' + user.jid.local + '</li>');
    		} else if(user.subscription == 'none' && user.subscriptionRequested) {			// CURRENT USER HAS REQUESTED OTHER USER
				$('#roster').append('<li><span class="indicator"><i class="ion-record"></i></span> ' + user.jid.local + ' (requested)</li>');
/*
			} else if(user.subscription == 'to') {		// LOOKS LIKE THIS AND FROM ARE IF ONE PERSON IS ALREADY SUBSCRIBED,
														// THEREFORE UPON RECEIVING A ROSTER ITEM WITH 'from' THAT USER SHOULD
														// AUTOMATICALLY SUBSCRIBE BACK.
				chatLog("DETECTED 'to' SUBSCRIPTION!", user);
				$('#roster').append('<li><span class="indicator"><i class="ion-record"></i></span> ' + user.jid.local + ' (requested)</li>');
			} else if(user.subscription == 'from') {	// IF TO/FROM SUBSCRIPTION IS IDENTIFIED, COMPLETE THE SUBSCRIPTION BY ACCEPTING/SENDING
														// REQUEST SO THAT IT WILL BE 'both'
				chatLog("DETECTED 'from' SUBSCRIPTION!", user);
				$('#roster').append('<li><span class="indicator"><i class="ion-record"></i></span> ' + user.jid.local + ' (accept / deny)</li>');
*//*
    		}
    	});
	}

	var updateOutgoingRequests = function()
	{
		$.each(outgoing_requests, function(index, user)
		{

		});
	}

	var updateIncomingRequests = function()
	{
		$.each(outgoing_requests, function(index, user)
		{

		});
	}
*/

	var getChatList = function()
	{
		roster = [];
		outgoing_requests = [];
		incoming_requests = [];

		client.getRoster().then(function(data)
	    {
	    	chatLog("FULL ROSTER RETRIEVED!", data);
	    	$.each(data.roster.items, function(index, user) {
	    		if(user.jid && user.jid.local) {
		    		var username = user.jid.local.trim();
		    		if(user.subscription == 'both') {																		// USER AND OTHER USER ARE FRIENDS
		    			if(roster.indexOf(username) < 0)
		    				roster.push(username);
		    		} else if((user.subscription == 'none' && user.subscriptionRequested) || user.subscription == 'to') {	// CURRENT USER HAS REQUESTED OTHER USER
		    			if(outgoing_requests.indexOf(username) < 0)
		    				outgoing_requests.push(username);
		    		} else if(user.subscription == 'from') {
		    			if(incoming_requests.indexOf(username) < 0)
		    				incoming_requests.push(username);
		    		}
		    	} else {
		    		chatLog("ROSTER ITEM HAS NO 'local' UNDER 'jid'!", user);
		    	}
	    	});

	    	updateChatList();
	    });
	}

	var updateChatList = function()
	{
		//updateRoster();
		//updateOutgoingRequests();
		//updateIncomingRequests();

		$('#roster').html('');
		if(roster.length > 0) {
			//$('#roster').append('<li class="section-title"><strong>Friends</strong></li>');
			$.each(roster, function(index, username)
			{
				$('#roster').append('<li class="chat-list-item"><span class="indicator"><i class="ion-record"></i></span> ' + username + '</li>');
			});
		}

		$('#outgoing-requests').html('');
		if(outgoing_requests.length > 0) {
			//if(roster.length > 0)
				//$('#outgoing-requests').append('<hr>');
			//$('#outgoing-requests').append('<li class="section-title"><strong>Outgoing requests</strong></li>');
			$.each(outgoing_requests, function(index, username)
			{
				$('#outgoing-requests').append('<li class="chat-list-item">' + username + ' (requested)</li>');
			});
		}

		$('#incoming-requests').html('');
		if(incoming_requests.length > 0) {
			//if(roster.length > 0 || outgoing_requests.length > 0)
				//$('#incoming-requests').append('<hr>');
			//$('#incoming-requests').append('<li class="section-title"><strong>Incoming requests</strong></li>');
			$.each(incoming_requests, function(index, username)
			{
				//var accept_link = "<a id='acceptSubscription' data-username='" + username + "' href='#'>accept</a>";
				//var deny_link = "<a id='denySubscription' data-username='" + username + "' href='#'>deny</a>";
				$('#incoming-requests').append("<li class='chat-list-item'>" + username + " (<a class='accept-subscription-link' data-username='" + username + "' href='#'>accept</a> / <a class='deny-subscription-link' data-username='" + username + "' href='#'>deny</a>)</li>");
			});
		}
	}

	var sendPresence = function()
	{
		client.sendPresence();
	    $('#userStatusIndicator').removeClass('error').addClass('online');

	    //chatLog('Presence sent!')
	}

	var presenceUpdate = function(data)
	{/*
		if(data.type == 'available') {
			chatLog("Pesence update: " + data.from.local + " now available!");
		} else if(data.type == 'unavailable') {
			chatLog("Pesence update: " + data.from.local + " now unavailable!");
		} else {
			chatLog("Received 'presence' event with UNKNOWN TYPE '" + data.type + "'!", data);
		}
	*/}

	var presenceError = function(data)
	{
		//chatLog("Received 'presence:error' event!", data);
	}

	var receiveSubscriptionRequest = function(data)
	{
		if(data.type == 'subscribe' && data.to.local != data.from.local) {
			var username = data.from.local.trim();

			chatLog("Subscription requested by user " + username + "!", data);

			if(outgoing_requests.indexOf(username) > -1) {
				acceptSubscriptionRequest(username);
			} else if(incoming_requests.indexOf(username) < 0) {
	    		incoming_requests.push(username);
			}

	    	updateChatList();

/*
			var accept_link = "<a id='acceptSubscription' data-username='" + data.from.local + "' href='#'>accept</a>";
			var deny_link = "<a id='denySubscription' data-username='" + data.from.local + "' href='#'>accept</a>";
			$('#roster').append('<li><span class="indicator"><i class="ion-record"></i></span> ' + data.from.local + ' (' + accept_link + ' / ' + deny_link + ')</li>');
*/
		}
	}

	var sendSubscriptionRequest = function(username)
	{
		var username = username.trim();
		if(outgoing_requests.indexOf(username) < 0) {
			outgoing_requests.push(username);

			client.subscribe(username + "@commentum.io");
			chatLog("Subscription requested for user " + username + "!");
		}
	}

	var acceptSubscriptionRequest = function(username)
	{
		var username = username.trim();
		if(incoming_requests.indexOf(username) > -1) {
			client.acceptSubscription(username + "@commentum.io");
			chatLog("Subscription accepted for user " + username.trim() + "!");

			sendSubscriptionRequest(username);
		}
	}

	var denySubscriptionRequest = function(username)
	{
		client.denySubscription(username.trim() + "@commentum.io");
		sendSubscriptionRemoval(username);
		chatLog("Subscription denied for user " + username.trim() + "!");
	}

	var receiveSubscriptionRemoval = function(data)
	{
		if(data.type == 'unsubscribe' && data.to.local != data.from.local) {
			var username = data.from.local.trim();

			var index = incoming_requests.indexOf(username);
			if(index > -1) {
				// REMOVE THE USER FROM THE CHAT LIST
				chatLog('USER FOUND IN incoming_requests LIST ON RECEIVE UNSUBSCRIBE: RMEOVE FROM LIST!');
			}
			sendSubscriptionRemoval(username);

			chatLog("Subscription cancelled by user " + username + "!", data);
		}
	}

	var sendSubscriptionRemoval = function(username)
	{
		var username = username.trim();

		client.unsubscribe(username.trim() + "@commentum.io");

		var index = outgoing_requests.indexOf(username);
		if(index > -1) {
			// REMOVE THE USER FROM THE CHAT LIST
			chatLog('USER FOUND IN outgoing_requests LIST ON RECEIVE UNSUBSCRIBE: RMEOVE FROM LIST!');
		}

		chatLog("Subscription cancelled for user " + username.trim() + "!");
	}

	var sendMessage = function(data)
	{
		$('#chatMessages').append('<li class="green">' + data.body + '</li>');
		var cmb = $("#chatMessagesWindow");
		cmb.animate({ scrollTop: cmb.prop("scrollHeight") - cmb.height() }, 1);

		chatLog('Sent chat message!', data);
	}

	var receiveMessage = function(data)
	{
		if(data.type == 'message') {
			chatLog('Received chat message!', message);

			$('#chatMessages').append('<li>' + message.body + '</li>');
			var cmb = $("#chatMessagesWindow");
			cmb.animate({ scrollTop: cmb.prop("scrollHeight") - cmb.height() }, 1);
		} else {
			chatLog("Received 'message' event with UNKNOWN TYPE '" + data.type + "'!", data);
		}
	}

	var messageError = function(data)
	{
		chatLog("Received 'message:error' event!", data);
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
		client.on('session:bound', function (data)
		{
			chatLog("Received 'session:bound' event!", data);
		});
		client.on('session:started', function ()
		{
			chatLog("Received 'session:started' event!");
			sessionStarted();
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
		***
		* NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF
		****************************************************************************************
		*** Not sure what 'roster:ver' event is for yet
		****************************************************************************************
		* NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF
		*/
		client.on('roster:update', function(data)
		{
			chatLog("Received 'roster:update' event!", data);
			getChatList();
		});
		client.on('roster:ver', function(data)
		{
			chatLog("Received 'roster:ver' event!", data);
		});

		/*
		* Subscription/unsubscription events
		***
		* NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF
		****************************************************************************************
		*** The 'subscribe'/'unsubscribe' events are when you've RECEIVED a message (user has
		*** subscribed/unsubscribed to/from you). Not sure what 'subscribed'/'unsubscribed' are
		*** but I believe they are when you've SENT those messages.
		****************************************************************************************
		* NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF
		*/
		client.on('subscribed', function(data)
		{
			chatLog("Received 'subscribed' event!", data);
		});
		client.on('subscribe', function(data)
		{
			receiveSubscriptionRequest(data);
		});
		client.on('unsubscribed', function(data)
		{
			chatLog("Received 'unsubscribed' event!", data);
		});
		client.on('unsubscribe', function(data)
		{
			receiveSubscriptionRemoval(data);
		});

		/*
		* On presence updates
		*/
		client.on('presence', function(data) 
		{
			if(data.type == 'error') {
				presenceError(data);
			} else {
				if(data.to.local != data.from.local) // ignore if the user is itself
					presenceUpdate(data);
			}
		});
		/*
		client.on('presence:error', function(data) 
		{
			if(data.to.local != data.from.local) // ignore if the user is itself
				chatLog("Received 'presence:error' event!", data);
		});
		*/

		/*
		* User receives available/unable presence
		***
		* NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF
		****************************************************************************************
		*** At first glance, this may not be necessary. Everytime the 'available/unavailable'
		*** event fires, a 'presence' event is also fired with the current status (available/un-
		*** available, away, etc.) --- so this may be able to be taken out. I left it for now
		*** because there may be some good reason for it being its own event that I just don't
		*** know.
		****************************************************************************************
		* NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF
		*/
		/*
		client.on('available', function(data)
		{
			if(data.to.local != data.from.local) // ignore if the user is itself
				chatLog("Received 'available' event!", data);
		});
		client.on('unavailable', function(data)
		{
			if(data.to.local != data.from.local) // ignore if the user is itself
				chatLog("Received 'unavailable' event!", data);
		});
		*/

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
		***
		* NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF
		****************************************************************************************
		*** Carbons are previous messages, received while offline/in the past. This will allow
		*** us to preserve chats while people are offline or until next time they're online,
		*** once we figure out how to make it work right.
		****************************************************************************************
		* NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF
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
		***
		* NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF
		****************************************************************************************
		*** Chat state is for when/if we want to have a 'User A is typing...' message, and also
		*** 'User A has entered text...' message. Not sure how to make it work quite yet, but
		*** looks like the functionality is there.
		****************************************************************************************
		* NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF
		*/
		/*
		client.on('chat', function (msg)
		{
			receiveMessage(msg);
		});
		*/
		client.on('chat:state', function(data)
		{
			chatLog("Received 'chat:state' event!", data);
		});

		/*
		* When user's message is sent, display it
		***
		* NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF
		****************************************************************************************
		*** Not yet sure difference between 'message' and 'message:sent'. My guess is that the
		*** 'message' event is fired for send AND received messages? We may not need it.
		****************************************************************************************
		* NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF
		*/
		client.on('message', function(data)
		{
			if(data.type == 'error') {
				messageError(data);
			} else {
				if(data.to.local != data.from.local)
					receiveMessage(data);
			}
		});
		client.on('message:sent', function (msg)
		{
			sendMessage(msg);
		});
		/*
		client.on('message:error', function(data)
		{
			chatLog("Received 'message:error' event!", data);
		});
		*/

		/*
		* Attention event
		***
		* NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF
		****************************************************************************************
		*** NO idea what 'attention' event is for yet
		****************************************************************************************
		* NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF -- NOTE TO SHARIF
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

	//$('#roster').on('click', 'li', function()
	$('#roster').on('click', 'li', function()
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

	$('#incoming-requests').on('click', '.accept-subscription-link', function() {
		chatLog("#incoming-requests .accept-subscription-link clicked (" + $(this).attr('data-username') + ")!", $(this));
		acceptSubscriptionRequest($(this).attr('data-username'));
	});

	$('#incoming-requests').on('click', '.deny-subscription-link', function() {
		chatLog("#incoming-requests .deny-subscription-link clicked (" + $(this).attr('data-username') + ")!", $(this));
		denySubscriptionRequest($(this).attr('data-username'));
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