<?php

namespace App\Http\Controllers;

use Auth;
use Cache;
use Session;
use App\Models\Tag;
use App\Models\Thread;
use Cocur\Slugify\Slugify;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\Request;
use App\Models\User;

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
			if (in_array($tag, ['all', 'front']))
				return response("You can't claim this tag.", 500);

			if (strlen($tag) < 2 || strlen($tag) > 30)
				return response('A tag can\'t be longer than 30 characters and must be at least 2 characters long.' , 500);

			$check = Tag::where('title', strtolower($tag))->first();

			if (!$check)
			{
				$usersLatestTag = Tag::where('owner_id', Auth::id())->orderBy('id', 'DESC')->first();

				if ($usersLatestTag)
				{
					// Check if last tag of current user was claimed less than 15 mins ago.
					if (strtotime($usersLatestTag->created_at) > strtotime("now") - 900)
						return response("You can only claim one tag per 15 minutes.", 500);
				}

				$newTag = new Tag;

				$newTag->title         = strtolower($tag);
				$newTag->display_title = $tag;
				$newTag->owner_id      = Auth::id();

				if ($newTag->save())
				{
					// auto subscribe the owner
					$newTag->subscribe();
					sendMessage(Auth::id(), null, null, null, null, $newTag->id, null, 5);
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

		if (in_array($tag->id, [2, 3]) && !isModOfTag($tag->id))
			return response("This is an official Commentum tag. You can't submit entries in it.", 500);

		if ($tag->privacy == 1 || $tag->privacy == 2) 	// Tag is semi-private or private
		{
			if (!Auth::user()->isSubscribedToTag($tag->id))
			{
				if ($tag->privacy == 1)
					return response("This tag is semi-private. You have to subscribe to it before you can submit entries to this tag.", 500);
				else
					return response("This tag is private.", 500);
			}
		}

		if (empty($nsfw))
			$nsfw = false;

		if (empty($serious))
			$serious = false;

		$slugify   = new Slugify();
        $slugify->addRule('+', 'plus');

        if ($request->get('thread_id') != "")
        	$new = Thread::find(Hashids::decode($request->get('thread_id'))[0]);
    	else
			$new = new Thread;

		$new->user_id  = Auth::id();
		$new->tag_id   = $tag->id;
		$new->title    = $title;
		$new->slug     = truncateSlug($slugify->slugify($title, "-"));
		$new->nsfw     = $nsfw || $tag->nsfw;
		$new->serious  = $serious;
		$new->link     = $link;
		$new->markdown = $description;

		if ($new->save())
			return response($new->permalink(), 200);
		else
			return response('Something went wrong on our side, try again.', 500);
	}

	/**
	 * Delete a submission
	 *
	 * @param  	Request 	$request
	 * @return 	response
	 */
	public function delete(Request $request)
	{
		$id = Hashids::decode($request->get('hashid'));

		if (!$id > 0)
			return response('Can\'t find the submission you want to delete.', 500);

		$thread = Thread::find($id[0]);

		if ($thread->user_id != Auth::id())
			return response("You're not the owner of this submission.", 500);

		// Soft delete the model
		$thread->delete();

		return response("OK", 200);
	}

	/**
	 * Direct link redirection
	 */
	public function out($hashid, Request $request)
	{
		$hashed = Hashids::decode($hashid);

		if(!count($hashed) > 0)
			abort(404);

		$thread = Thread::find($hashed[0]);

		if(!$thread)
			abort(404);

		if(empty($thread->link))
			return redirect($thread->permalink());

		$ip = $request->getClientIp();

		if (is_null(Cache::get("{$ip}:thread:{$thread->id}:view")))
		{
			Cache::put("{$ip}:thread:{$thread->id}:view", true, 120);
			//$thread->increment('views');
			$thread->addView();
		}

		return redirect($thread->link);
	}
}









