<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Tag;
use App\Models\User;
use App\Models\TagMod;
use Vinkla\Hashids\Facades\Hashids;
use App\Models\TagSubscriber;
use Illuminate\Http\Request;

class TagController extends Controller
{
	/**
	 * Subscribe a user to a tag.
	 *
	 * @param  	Request 	$request
	 * @return 	response
	 */
	public function subscribe(Request $request)
	{
		if (!$request->ajax())
			abort(404);

		$tagId = Hashids::decode($request->get('tag-id'))[0];

		if (Auth::user()->isSubscribedToTag($tagId) == true)
			return response("You're already subscribed to this tag.", 200);

		$tag = Tag::find($tagId);

		if (!$tag)
			return response("That tag doesn't exist (anymore).");

		if ($tag->subscribe())
		{
			sendMessage($tag->owner()->id, Auth::id(), null, null, null, $tag->id, null, 6);
			return response("Subscribed.", 200);
		}

		return response("Something went wrong, try again.", 500);
	}

	/**
	 * Save tag settings
	 *
	 * @param  	Request 	$request
	 * @return 	response
	 */
	public function settings($tag, Request $request)
	{
		if (!$request->ajax())
			abort(404);

		$tag = Tag::where('title', strtolower($tag))->first();

		if ($tag->owner()->id !== Auth::id())
			return response('You can\'t change the settings of this tag.', 500);

		$cover   = $request->get('cover');
		$mods    = $request->get('mods');
		$privacy = $request->get('privacy');

		if (!empty($cover) && !filter_var($cover, FILTER_VALIDATE_URL))
			return response('Submit a valid URL to an image for the cover.', 500);

		$tag->hero_img = trim($cover);
		$tag->rules    = $request->get('rules');
		$tag->nsfw     = $request->get('nsfw');
		$tag->description     = $request->get('description');
		$tag->privacy  = $privacy;

		foreach($mods as $mod)
		{
			if (!empty($mod))
			{
				$mod = User::where('username', $mod)->first();

				if (!$mod)
					return response('Couldn\'t find user ' . $mod . '.', 500);

				$tagMod          = new TagMod;
				$tagMod->tag_id  = $tag->id;
				$tagMod->user_id = $mod->id;

				$tagMod->save();
			}
		}

		if ($tag->save())
			return response('Saved.', 200);

		return response('Something went wrong, try again.', 500);
	}
}














