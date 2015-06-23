<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Thread;

class ViewDataServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		/**
		 * Bind data to index page.
		 */
		view()->composer('pages.index', function($view)
		{
			$threads = Thread::orderBy('momentum', 'DESC')->take(25)->get();
			$data    = ['threads' => $threads];

			$view->with($data);
		});
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
	    //
	}

}
