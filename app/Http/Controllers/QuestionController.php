<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Tag;
use App\Models\Question;
use App\Helpers\Parsedown;
use Cocur\Slugify\Slugify;
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

		$question    = trim($request->get('question'));
		$tag         = preg_replace("/[^a-z0-9]+/i", "", $request->get('tag'));
		$nsfw        = $request->get('nsfw');
		$serious     = $request->get('serious');
		$description = $request->get('description');

		if (empty($question))
			return response('You can\'t leave the question field empty.', 500);

		if (strlen($question) < 10 || strlen($question) > 300)
			return response('Your question can\'t be longer than 300 characters and must be at least 10 characters long.', 500);

		if (empty($tag))
		{
			$tag = Tag::find(1);
		}
		else
		{
			if (strlen($tag) < 2 || strlen($tag) > 30)
				return response('A tag can\'t be longer than 30 characters and must be at least 2 characters long.' , 500);

			$check = Tag::where('title', strtolower($tag))->first();

			if (!$check)
			{
				$newTag = new Tag;

				$newTag->title         = strtolower($tag);
				$newTag->display_title = $tag;
				$newTag->owner_id      = Auth::id();

				if ($newTag->save())
					$tag = $newTag;
				else
					return response('We couldn\'t claim that tag for you right now, try again.', 500);
			}
			else
			{
				$tag = $check;
			}
		}

		if (empty($nsfw))
			$nsfw = false;

		if (empty($serious))
			$serious = false;

		$slugify   = new Slugify();
        $slugify->addRule('+', 'plus');

		$new = new Question;
		$new->user_id  = Auth::id();
		$new->tag_id   = $tag->id;
		$new->question = $question;
		$new->slug     = $slugify->slugify($question, "-");
		$new->nsfw     = $nsfw;
		$new->serious  = $serious;

		if (!empty($description))
		{
			$parsedown        = new Parsedown();
			$new->markdown    = $description;
			$new->description = $parsedown->text($description);
		}

		if ($new->save())
			return response(url("/tag/{$tag->display_title}/" . Hashids::encode($new->id)), 200);
		else
			return response('Something went wrong on our side, try again.', 500);
	}
}