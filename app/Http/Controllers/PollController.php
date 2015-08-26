<?php

namespace App\Http\Controllers;

use Auth;

use App\Models\Poll;
use App\Models\PollAnswer;
use App\Models\UserPollAnswer;

use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class PollController extends Controller
{
	/**
	 * Process a user's answer.
	 *
	 * @return response
	 */
	public function answer($hashid, Request $request)
	{
		if (!$request->ajax())
			abort(404);

		if (!$request->has('answer'))
			return response(["Select an answer."], 500);

		$answerHashes = Hashids::decode($request->get('answer'));
		$pollHashes   = Hashids::decode($hashid);

		if (!count($pollHashes) > 0)
			return response(["This poll does not exist (anymore)."], 500);

		if (!count($answerHashes) > 0)
			return response(["Something went wrong. Refresh the page, then try again."], 500);

		$poll = Poll::find($pollHashes[0]);

		if (!$poll)
			return response(["This poll does not exist (anymore)."], 500);

		if ($poll->userParticipated() == true)
			return response(["You already participated in this poll."], 500);

		$userAnswer            = new UserPollAnswer;
		$userAnswer->user_id   = Auth::id();
		$userAnswer->poll_id   = $poll->id;
		$userAnswer->answer_id = $answerHashes[0];

		if ($userAnswer->save())
			return response("Thanks for participating!", 200);

		return response(["Couldn't connect to the server, try again. Sorry."], 500);
	}
}