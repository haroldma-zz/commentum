@extends('layouts.default')

@section('page')
<div class="hero">
	@include('layouts.user-header')
	@include('layouts.tags-nav')
</div>
<div class="padding">
	<div class="row">
		<div class="medium-6 medium-offset-3 columns">
			{!! Form::open(['url' => $tag->permalink() . '/settings', 'class' => 'panel small', 'id' => 'settingsForm']) !!}
				<h5>General settings</h5>
				<hr>
				{!! Form::label('title', 'Title of the tag') !!}
				{!! Form::text('title', '#' . $tag->display_title, ['disabled' => 'yes']) !!}
				{!! Form::label('cover', 'Link to cover image') !!}
				{!! Form::text('cover', $tag->hero_img) !!}
				{!! Form::label('rules', 'Rules') !!}
				{!! Form::textarea('rules', $tag->rules, ['rows' => 4]) !!}
				<br>
				<h5>Moderators</h5>
				<hr>
				<p>
					Users that are added to this list will be able to moderate submissions and subscribers of this tag. They won't be able to change these settings.
				</p>
				<ul class="no-bullet panel" id="modsList">
					<li><b>Moderators:</b></li>
					@foreach($tag->mods() as $mod)
					<li><a href="{{ $mod->user()->permalink() }}">/u/{{ $mod->user()->username . ($mod->user()->id === $tag->owner()->id ? ' (Tag owner)' : '') }}</a></li>
					@endforeach
				</ul>
				{!! Form::label('mods[]', 'Add mods') !!}
				{!! Form::text('mods[]') !!}
				<br>
				<p class="text-alert" id="submitFormError"></p>
				{!! Form::submit('Save settings', ['class' => 'btn blue']) !!}
				&nbsp;&nbsp;&nbsp;
				<img class="loader" id="submitFormLoader" src="{{ url('/img/dark-loader.svg') }}">
			{!! Form::close() !!}
		</div>
	</div>
</div>
@stop

@section('scripts')
@include('scripts.tag-settings')
@stop