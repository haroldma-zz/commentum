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
</div>
<div class="chat-box" id="chatbox">
	<header id="chatHeader"></header>
	<section id="chatMessagesWindow">
		<ul class="no-bullet" id="chatMessages"></ul>
	</section>
	<textarea id="chatInput" placeholder="Type a message..."></textarea>
</div>