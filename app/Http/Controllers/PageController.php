<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Thread;
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
		return view('pages.index');
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
	public function thread($tag, $hash, $slug)
	{
		$threadId = Hashids::decode($hash);

		if (!count($threadId) > 0)
			abort(404);

		$thread = Thread::find($threadId[0]);

		if (!$thread)
			abort(404);

		if ($thread->tag()->display_title != $tag)
			return abort(404);

		return view('threads.thread')->with(['thread' => $thread]);
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
}