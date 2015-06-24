<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Tag;
use App\Models\Thread;
use Cocur\Slugify\Slugify;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
	/**
	 * Submit a thread
	 *
	 * @param  	Request $request
	 * @return 	response
	 */
	public function submit(Request $request)
	{
		if (!$request->ajax())
			abort(404);

		$title    	 = trim($request->get('title'));
		$link 		 = trim($request->get('link'));
		$tag         = preg_replace("/[^a-z0-9]+/i", "", $request->get('tag'));
		$nsfw        = $request->get('nsfw');
		$serious     = $request->get('serious');
		$description = $request->get('description');

		if (empty($title))
			return response('You can\'t leave the title field empty.', 500);

		if (strlen($title) < 10 || strlen($title) > 300)
			return response('Your title can\'t be longer than 300 characters and must be at least 10 characters long.', 500);

		if (!empty($link) && !filter_var($link, FILTER_VALIDATE_URL))
			return response('The link you submitted is not a valid URL.', 500);

		if (!empty($link) && strlen($link) > 350)
			return response('The link you submitted is too long.', 500);

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
				{
					sendMessage(Auth::id(), null, null, null, $newTag->id, null, 5);
					makeModOfTag($newTag->id, Auth::id());

					$tag = $newTag;
				}
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

		$new = new Thread;
		$new->user_id  = Auth::id();
		$new->tag_id   = $tag->id;
		$new->title    = $title;
		$new->slug     = $slugify->slugify($title, "-");
		$new->nsfw     = $nsfw;
		$new->serious  = $serious;

		if (!empty($link))
			$new->link = $link;

		if (!empty($description))
			$new->markdown    = $description;

		if ($new->save())
			return response($new->permalink(), 200);
		else
			return response('Something went wrong on our side, try again.', 500);
	}
}