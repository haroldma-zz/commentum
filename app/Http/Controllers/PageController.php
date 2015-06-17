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
}