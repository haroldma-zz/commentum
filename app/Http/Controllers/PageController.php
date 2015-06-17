<?php

namespace App\Http\Controllers;

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
}