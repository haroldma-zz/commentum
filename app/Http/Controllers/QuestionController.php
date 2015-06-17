<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Question;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
	/**
	 * Submit a question
	 *
	 * @param  	Request $request
	 * @return 	response
	 */
	public function submit(Request $request)
	{
		if (!$request->ajax())
			abort(404);

		$question = trim($request->get('question'));
		$tag      = $request->get('tag');
		$nsfw     = $request->get('nsfw');
		$serious  = $request->get('serious');

		if (empty($question))
			return response('You can\'t leave the question field empty.', 500);

		if (strlen($question) < 10 || strlen($question) > 300)
			return response('Your question can\'t be longer than 300 characters and must be at least 10 characters long.', 500);

		if (empty($tag))
		{
			$tag = 1;
		}
		else
		{
			//
		}

		if (empty($nsfw))
			$nsfw = false;

		if (empty($serious))
			$serious = false;

		$new = new Question;
		$new->user_id  = Auth::id();
		$new->tag_id   = $tag;
		$new->question = $question;
		$new->nsfw     = $nsfw;
		$new->serious  = $serious;

		if ($new->save())
			return response(url("/tag/random/" . Hashids::encode($new->id)), 200);
		else
			return response('Something went wrong on our side, try again.', 500);
	}
}