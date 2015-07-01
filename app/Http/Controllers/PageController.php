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
	 * The index page
	 *
	 * @return 	view
	 */
	public function index()
	{
		// if (!Auth::check())
			$threads = Thread::orderBy('momentum', 'DESC')->take(25)->get();

			$moreSubmissionsCount = Thread::orderBy('momentum', 'DESC')->count();
			Session::flash('moreSubmissionsCount', $moreSubmissionsCount);
		// else
			// $threads = User::getSubscribedTagsThreads();

		$data = ['threads' => $threads];

		return view('pages.index')->with($data);
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

		return view('users.saved');
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
		//$threadId = Hashids::decode($hash);
		//
		//if (!count($threadId) > 0)
		//	abort(404);
		//
		//$thread = Thread::find($threadId[0]);
		//
		//if (!$thread)
		//	abort(404);
		//
		//if ($thread->tag()->display_title != $tag)
		//	return abort(404);

		$thread = $this->getThreadById($tag, $hash);
		if (!$thread)
			abort(404);

		$ip = $request->getClientIp();

		if (is_null(Cache::get("{$ip}:thread:{$thread->id}")))
		{
			Cache::put("{$ip}:thread:{$thread->id}", true, 120);
			$thread->increment('views');
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
		//$thread = Thread::find(Hashids::decode($hash)[0]);
		//
		//if (!$thread)
		//	abort(404);
		
		$thread = $this->getThreadById($tag, $hash);
		if (!$thread)
			abort(404);

		if ($thread->author()->id !== Auth::id())
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
	 * Tag feed page
	 *
	 * @return 	view
	 */
	public function tag($tag)
	{
		$tag = Tag::where('title', strtolower($tag))->first();

		if (!$tag)
			abort(404);

		return view('tags.tag')->with(['tag' => $tag]);
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
		//$threadId = Hashids::decode($hash);
		//
		//if (!count($threadId) > 0)
		//	abort(404);
		//
		//$thread = Thread::find($threadId[0]);
		//
		//if (!$thread)
		//	abort(404);
		//
		//if ($thread->tag()->display_title != $tag)
		//	return abort(404);

		$thread = $this->getThreadById($tag, $hash);
		if (!$thread)
			abort(404);

		//$commentId = Hashids::decode($chash);
		//
		//if (!count($commentId) > 0)
		//	abort(404);
		//
		//$comment = Comment::find($commentId[0]);
		//
		//if (!$comment)
		//	abort(404);

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

	/*************************/
	/** PRIVATE FUNCTIONS **/
	/*************************/

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

		/*
		$comment = Comment::find($commentId[0]);

		if (!$comment)
			return null;

		return $comment;
		*/

		return Comment::find($commentId[0]);
	}

}







