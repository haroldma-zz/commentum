@extends('layouts.default')

@section('page')
<div class="hero">
	<div class="row">
		<div class="medium-4 medium-offset-4 columns hero-page text-justify">
			<h3>Policy</h3>
			<br>
			<p>
				At Commentum, we respect and harbor freedom of speech. Freedom of speech means that you can share your opinion on anything. Freedom of speech, in our opinion, and by our policies, does not include:
			</p>
			<ol class="numbered-list">
				<li>Posting/sharing/viewing child pornography or "jailbait" (photos of young children in revealing attire posted/shared for sexual purposes).</li>
				<li>"Doxxing" -- releasing personal information about another in an attempt to inteimidate, harrass, or otherwise terrorize that individual.</li>
				<li>Rallying or calling to action support for a hate crime. While you are free to share your opinions on anything here on Commentum, we will not tolerate posts calling, literally, for the physical harm of a specific person or group of people based on race, religion, etc.</li>
			</ol>
			<p>We have an absolute zero tolerance policy for any of the above types of content.</p>
		</div>
	</div>
</div>
@include('layouts.footer')
@stop
