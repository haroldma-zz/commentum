<div class="panel">
	<p>
		<b>{{ $poll->question }}</b>
	</p>
	{!! Form::open(['url' => '/poll/' . $poll->hashid(), 'class' => 'ajax-form']) !!}
	@foreach ($poll->answers as $answer)
	<label>
		{!! Form::radio('answer', $answer->hashid()) !!} {{ $answer->answer }} ({{ $answer->answers()->count() }})
	</label>
	@endforeach
	{!! Form::submit('Submit', ['class' => 'btn primary']) !!} &nbsp;&nbsp;&nbsp; <img src="{{ asset('img/three-dots-blue.svg') }}" class="loader" width="35px">
	<div class="error-container">
		<ul class="text-alert"></ul>
	</div>
	{!! Form::close() !!}
</div>