@extends('layouts.default')
@section('page')
@section('title', 'settings for #'.$tag->display_title)

<div class="hero">
	@include('layouts.user-header')
	@include('layouts.tags-nav')
</div>
<div class="padding">
	<div class="row small-collapse">
		<div class="medium-6 medium-offset-3 columns">
			{!! Form::open(['url' => $tag->permalink() . '/settings', 'class' => 'panel small', 'id' => 'settingsForm']) !!}
				<h5>General settings</h5>
				<hr>
				{!! Form::label('title', 'Title of the tag') !!}
				{!! Form::text('title', '#' . $tag->display_title, ['disabled' => 'yes']) !!}
				{!! Form::label('cover', 'Link to cover image') !!}
				{!! Form::text('cover', $tag->hero_img) !!}
                {!! Form::label('description', 'Description') !!}
                {!! Form::textarea('description', $tag->description, ['rows' => 4]) !!}
				{!! Form::label('rules', 'Rules') !!}
				{!! Form::textarea('rules', $tag->rules, ['rows' => 4]) !!}
				<label for="nsfw">
					{!! Form::checkbox('nsfw', true, $tag->nsfw,['id' => 'nsfw']) !!}
					This tag is Not Safe For Work.
				</label>
				<br>
				<h5>Privacy</h5>
				<hr>
				<div class="panel small">
					<p>
						You can set the following privacy types:
						<ul>
							<li>
								<b>Public:</b>
								<br>
								Anyone can view and submit submissions to this tag.
							</li>
							<li>
								<b>Semi-private</b>
								<br>
								Anyone can view submissions of this tag, but only subscribed users can submit submissions.
							</li>
							<li>
								<b>Private</b>
								<br>
								Only invited people can view and submit submission to this tag.
							</li>
						</ul>
					</p>
				</div>
				<label>{!! Form::radio('privacy', 0, ($tag->privacy == 0 ? true : false)) !!} Public</label>
				<label>{!! Form::radio('privacy', 1, ($tag->privacy == 1 ? true : false)) !!} Semi-private</label>
				<label>{!! Form::radio('privacy', 2, ($tag->privacy == 2 ? true : false)) !!} Private</label>
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
				<p>
					Enter only the username of the user you want to add as mod.
				</p>
				<div id="modFields">
					{!! Form::text('mods[]') !!}
				</div>
				<a class="btn small" id="addModField"><i class="ion-plus"></i> Add another mod</a>
				<br>
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