<?php

namespace App\Http\Middleware;

use Session;
use Closure;
use App\Models\Tag;

class CheckIfTagOrThreadIsNSFW
{
	/**
	 * Check if an tag or thread is NSFW.
	 *
	 * @param  	Request  	$request
	 * @param  	Closure 	$next
	 * @return 	redirect
	 */
	public function handle($request, Closure $next)
	{
		if (Session::has('accepted_nsfw'))
			return $next($request);

		if (!is_null($request->input('accepted_nsfw')))
		{
			Session::put('accepted_nsfw', true);

			return redirect($request->url());
		}


		$route = $request->route();
		$tag   = $route->getParameter('tag');
		$hash  = $route->getParameter('hash');
		$slug  = $route->getParameter('slug');


		$tag = Tag::where('title', strtolower($tag))->first();

		if (!$tag)
			abort(404);

		if ($tag->nsfw == true)
		{
			Session::flash('intended', $request->url());
			Session::flash('referer', $request->server('HTTP_REFERER'));
			return view('pages.nsfw');
		}


		if (!is_null($hash) && !is_null($slug))
		{
			$thread = Thread::find(Hashids::decode($hash)[0]);

			if (!$thread)
				abort(404);

			if ($thread->nsfw == true)
			{
				Session::flash('intended', $request->url());
				Session::flash('referer', $request->server('HTTP_REFERER'));
				return view('pages.nsfw');
			}
		}
		else if (!is_null($hash) && is_null($slug))
			abort(404);


		return $next($request);
	}
}