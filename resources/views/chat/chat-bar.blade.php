<div class="chat-bar">
	<div class="header" id="chatToggler">
		<div class="icon-box">
			<i class="ion-ios-chatboxes-outline"></i>
			<div id="chatCount">2</div>
		</div>
		<div class="text-box">
			<span id="userStatusIndicator" class="indicator"><i class="ion-record"></i></span> {{ Auth::user()->username }}
			<i class="ion-chevron-up" id="chatChevron"></i>
		</div>
	</div>
	<ul class="chat-list no-bullet" id="roster">
		<br>
		<center><img src="{{ url('/img/loader.svg') }}" width="30px"></center>
	</ul>
	<ul class="chat-list no-bullet" id="outgoing_requests"></ul>
	<ul class="chat-list no-bullet" id="incoming_requests"></ul>
	<div class="chat-actions">
		<input type="text" class="inputter" id="addUserInput" placeholder="Add user...">
		<div class="button" id="addUserBtn"><i class="ion-ios-personadd-outline"></i></div>
	</div>
</div>
<div class="chat-box" id="chatbox">
	<header id="chatHeader"></header>
	<section id="chatMessagesWindow">
		<ul class="no-bullet" id="chatMessages"></ul>
	</section>
	<textarea id="chatInput" placeholder="Type a message..."></textarea>
</div>