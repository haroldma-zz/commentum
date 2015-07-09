<?php

namespace App\Http\Controllers;

use Auth;
use Cache;
use Session;
use App\Models\Tag;
use App\Models\User;
use App\Models\Thread;
use App\Models\Comment;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class PageController extends Controller
{
	/**
	 * Get thread by ID
	 *
	 * @param  	string $tag
	 * @param  	string $hash
	 * @return 	Thread
	 */
	private function getThreadById($tag, $hash)
	{
		$threadId = Hashids::decode($hash);

		if (!count($threadId) > 0)
			return null;

		$thread = Thread::find($threadId[0]);

		if (!$thread)
			return null;

		if ($thread->tag()->display_title != $tag)
			return null;

		return $thread;
	}

	/**
	 * Get comment by ID
	 *
	 * @param  	string $chash
	 * @return 	Comment
	 */
	private function getCommentById($chash)
	{
		$commentId = Hashids::decode($chash);

		if (!count($commentId) > 0)
			return null;

		return Comment::find($commentId[0]);
	}


	/**
	 * The index page
	 *
	 * @return 	view
	 */
	public function index(Request $request, $extension = null)
	{
        $offset = max((int)$request->get('offset', 0), 0);
        $limit = min((int)$request->get('limit', 20), 100);

		if (!Auth::check())
		    $threads = Tag::getThreadsByHotness(-1, $offset, $limit);
        else
            $threads = User::getSubscribedTagsThreads(Auth::id(), $offset, $limit);

		$ip = getClientIp();
		foreach($threads as $thread)
		{
			if (is_null(Cache::get("{$ip}:thread:{$thread->id}:impression")))
			{
				Cache::put("{$ip}:thread:{$thread->id}:impression", true, 120);
				//$thread->increment('views');
				$thread->addImpression();
			}
		}

        if ($extension == '.json')
            return $threads;
        else if ($request->ajax()){
            $markup = '';

            foreach ($threads as $thread)
            {
                if (is_null(Cache::get("{$ip}:thread:{$thread->id}:impression")))
                {
                    Cache::put("{$ip}:thread:{$thread->id}:impression", true, 120);
                    $thread->addImpression();
                }

                $markup .= view('threads.thread-in-list', ['t' => $thread])->render();
            }
            return $markup;
        }
        else
            return view('pages.index')->with(['threads' => $threads, 'limit' => $limit]);
	}

	/**
	 * Login page
	 *
	 * @return 	view
	 */
	public function login()
	{
		return view('pages.login');
	}

	/**
	 * Preferences page
	 *
	 * @return 	view
	 */
	public function preferences()
	{
		abort(404);
		// return view('users.preferences');
	}

	/**
	 * Saved things page
	 *
	 * @param  	string 	$username
	 * @return 	view
	 */
	public function saves($username)
	{
		if ($username != Auth::user()->username)
			abort(404);

		return view('users.saved')->with(['limit' => -1]);
	}

	/**
	 * Submit page
	 *
	 * @return 	view
	 */
	public function submit()
	{
		return view('pages.submit');
	}

	/**
	 * Thread page
	 *
	 * @param  	string $tag
	 * @param  	string $hash
	 * @param  	string $slug
	 * @return 	view
	 */
	public function thread($tag, $hash, $slug, Request $request)
	{
		$thread = $this->getThreadById($tag, $hash);
		if (!$thread)
			abort(404);

		$ip = getClientIp();

		if (is_null(Cache::get("{$ip}:thread:{$thread->id}:view")))
		{
			Cache::put("{$ip}:thread:{$thread->id}:view", true, 120);
			//$thread->increment('views');
			$thread->addView();
		}

		return view('threads.thread')->with(['thread' => $thread]);
	}

	/**
	 * Thread edit page.
	 *
	 * @param  	string $tag
	 * @param  	string $hash
	 * @param  	string $slug
	 * @return 	view
	 */
	public function editThread($tag, $hash, $slug)
	{
		$thread = $this->getThreadById($tag, $hash);
		if (!$thread)
			abort(404);

		if ($thread->author()->id !== Auth::id() && !Auth::user()->can('edit-thread'))
			abort(403);

		return view('pages.submit')->with(['thread' => $thread]);
	}

	/**
	 * User profile page
	 *
	 * @param  	string $username
	 * @return 	view
	 */
	public function profile($username)
	{
		$user = User::where('username', $username)->first();

		if (!$user)
			abort(404);

		return view('users.user')->with(['user' => $user]);
	}

	/**
	 * Inbox page
	 *
	 * @return 	view
	 */
	public function inbox()
	{
		return view('users.inbox');
	}

	/**
	 * PM view
	 *
	 * @return 	view
	 */
	public function inboxNew()
	{
		// return view('users.new-message');
		abort(404);
	}

	/**
	 * Tag feed page
	 *
	 * @return 	view
	 */
	public function tag(Request $request, $tag, $extension = null)
	{
		$tag = Tag::where('title', strtolower($tag))->first();

		if (!$tag)
			abort(404);

        $offset = max((int)$request->get('offset', 0), 0);
        $limit = min((int)$request->get('limit', 20), 100);

        $threads = $tag->threadsByHotness($offset, $limit);

        $ip = getClientIp();
        foreach($threads as $thread)
        {
            if (is_null(Cache::get("{$ip}:thread:{$thread->id}:impression")))
            {
                Cache::put("{$ip}:thread:{$thread->id}:impression", true, 120);
                //$thread->increment('views');
                $thread->addImpression();
            }
        }

        if ($extension == '.json')
            return $threads;
        else if ($request->ajax()){
            $markup = '';

            foreach ($threads as $thread)
            {
                if (is_null(Cache::get("{$ip}:thread:{$thread->id}:impression")))
                {
                    Cache::put("{$ip}:thread:{$thread->id}:impression", true, 120);
                    $thread->addImpression();
                }

                $markup .= view('threads.thread-in-list', ['t' => $thread])->render();
            }
            return $markup;
        }
        else
            return view('tags.tag')->with(['tag' => $tag, 'threads' => $threads, 'limit' => $limit]);
	}

	/**
	 * Tag settings page
	 *
	 * @param  	string 	$tag
	 * @return 	view
	 */
	public function tagSettings($tag)
	{
		$tag = Tag::where('title', strtolower($tag))->first();

		if (!$tag)
			abort(404);

		if ($tag->owner_id !== Auth::id())
			abort(403);

		return view('tags.settings')->with(['tag' => $tag]);
	}

	/**
	 * Comment permalink page
	 *
	 * @param  	string $tag
	 * @param  	string $hash
	 * @param  	string $slug
	 * @param  	string $chash
	 * @return 	view
	 */
	public function threadComment($tag, $hash, $slug, $chash, Request $request)
	{
		$thread = $this->getThreadById($tag, $hash);
		if (!$thread)
			abort(404);

		$comment = $this->getCommentById($chash);
		if (!$comment)
			abort(404);

		if ($request->input('context') != '')
		{
			if (!is_null($comment->parent()))
				$context = true;
			else
				$context = false;
		}
		else
		{
			$context = false;
		}

		return view('threads.thread')->with(['thread' => $thread, 'singleComment' => $comment, 'context' => $context]);
	}

	/**
	 * Polymorphic pages
	 *
	 * @param  	string $page
	 * @return 	view
	 */
	public function page($page, Request $request)
	{
		try {
			return view("pages." . $page);
		} catch(Exception $e) {
			abort(404);
		}
	}

	/**
	 * Donation POST from STRIPE
	 *
	 * @return 	view
	 */
	public function donation(Request $request)
	{
		try {
			\Stripe::charges()->create([
				'source' => $request->input('stripeToken'),
				'currency' => 'USD',
				'amount'   => $request->input('stripeAmount'),
			]);

			Session::flash('donation_message', 'Your donation has been received. Thank you!');

			return view('pages.donate');
		} catch(Exception $e) {
			abort(500);
		}
	}

}







