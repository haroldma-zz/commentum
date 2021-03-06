@extends('layouts.default')
@section('title', 'overview for '.$user->username)

@section('page')
<div class="hero">
	@if (Auth::check())
	@include('layouts.user-header')
	@include('layouts.subscriptions-list')
	<hr>
	@endif
	<div class="row">
		<div class="medium-12 columns">
			<h4 class="white-font">{{ $user->username }}'s profile</h4>
		</div>
	</div>
</div>
<div class="padding">
	<div class="row small-collapse">
		<div class="medium-8 large-9 xlarge-10 columns">
			<div class="panel small">
				<table class="threads-list">
					@if (!count($user->threads()) > 0)
					<tr>
						<td>
							Nothing here...
						</td>
					</tr>
					@else
					@foreach ($user->threads() as $t)
					<tr>
						<td>
							<a>{{ floor($t->calculateMomentum()) }}</a>
						</td>
						<td>
							{!! (!empty($t->link) ? '<i class="ion-link"></i>' : '') !!}
							<a href="{{ $t->titlePermalink() }}">{{ $t->title }}</a>
							<br>
							<span>
								<a href="{{ $t->tag()->permalink() }}">
									<span data-livestamp="{{ strtotime($t->created_at) }}"></span> in
									#{{ $t->tag()->display_title }}
								</a>
								&middot;
								<a href="{{ $user->permalink() }}">{{ $user->username }}</a>
								&middot;
								<a href="{{ $t->permalink() }}">{{ $t->commentCount() }} comment{{ ($t->commentCount() > 1 || $t->commentCount() === 0 ? 's' : '') }}</a>
							</span>
						</td>
					</tr>
					@endforeach
					@endif
				</table>
			</div>
		</div>
		<div class="medium-4 large-3 xlarge-2 columns">
			@include('layouts.user-sidebar', ['user' => $user])
		</div>
	</div>
</div>
@stop

@section('scripts')
{!! HTML::script('/bower_components/livestamp/moment.min.js') !!}
{!! HTML::script('/bower_components/livestamp/livestamp.min.js') !!}
@stop