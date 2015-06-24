<?php

namespace App\Http\Controllers;

use Auth;
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

		$subscription          = new TagSubscriber;
		$subscription->tag_id  = $tagId;
		$subscription->user_id = Auth::id();

		if ($subscription->save())
			return response("Subscribed.", 200);

		return response("Something went wrong, try again.", 500);
	}
}