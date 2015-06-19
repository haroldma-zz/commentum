<?php

namespace App\Http\Controllers;

use App\Models\Thread;

class PageController extends Controller
{
	/**
	 * The index page
	 *
	 * @return 	view
	 */
	public function index()
	{
		$threads = Thread::orderBy('id', 'DESC')->take(25)->get();

		return view('pages.index')->with(['threads' => $threads]);
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
}