<div class="panel poll">
	<p>
		<b>{{ $poll->question }}</b>
	</p>
	{!! Form::open(['url' => '/poll/' . $poll->hashid(), 'class' => 'ajax-form']) !!}
	@foreach ($poll->answers as $answer)
	<label>
		{!! Form::radio('answer', $answer->hashid()) !!} {{ $answer->answer }} ({{ $answer->answers()->count() }})
	</label>
	@endforeach
	{!! Form::submit('Submit', ['class' => 'btn primary' . ($poll->userParticipated() ? ' disabled' : ''), ($poll->userParticipated() ? 'disabled' : '')]) !!} &nbsp;&nbsp;&nbsp; <img src="{{ asset('img/three-dots-blue.svg') }}" class="loader" width="35px">
	<div class="success-container hide">
		<br>
		<p class="text-success no-margin"></p>
	</div>
	<div class="error-container hide">
		<br>
		<ul class="text-alert no-margin"></ul>
	</div>
	{!! Form::close() !!}
</div>