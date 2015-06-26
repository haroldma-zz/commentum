<?php

namespace App\Http\Middleware;

use App\Models\Tag;
use Closure;
use Auth;

class CheckTagPrivacy
{
	/**
	 * Check the privacy of a tag and act accordingly.
	 *
	 * @param  Request $request
	 * @param  Closure $next
	 * @return redirect
	 */
	public function handle($request, Closure $next)
	{
		$route = $request->route();

		$tag = Tag::where('title', strtolower($route->getParameter('tag')))->first();

		if (!$tag)
			abort(404);

		if ($tag->privacy === 2)
		{
			if (!Auth::check() || !Auth::user()->isSubscribedToTag($tag->id))
				return view('pages.private');
		}

		return $next($request);
	}
}



