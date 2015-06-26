<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Session;
use App\Models\User;
use App\Models\TagSubscriber;
use Illuminate\Http\Request;

class UserController extends Controller
{
	/**
	 * Register a user
	 *
	 * @param  	Request $request
	 * @return 	response
	 */
	public function register(Request $request)
	{
		if (!$request->ajax())
			abort(404);

		foreach ($request->except('email') as $input)
		{
			if (empty($input))
				return response('Fill in all fields.', 500);
		}

		$username = $request->get('username');
		$password = $request->get('password');
		$email    = null;

		if (!empty($request->get('email')))
			$email = $request->get('email');

		if (strlen($username) < 3 || strlen($username) > 15)
			return response('Your username must be between 3 and 15 characters long.', 500);

		if (!preg_match('/^[A-Za-z][A-Za-z0-9_]+$/', $username))
			return response('Your username may only contain the characters A-Z (case-insensitive), 0-9 and _, but may not start with an underscore.', 500);

		if (strlen($password) < 6 || strlen($password) > 21)
			return response('Your password must be between 6 and 21 characters long.', 500);

		if (!is_null($email) && !preg_match('/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/', $email))
			return response('Make sure your e-mail address is in the format name@example.com.', 500);

		$check = User::where('username', $username)->first();

		if (!$check)
		{
			$user = new User;
			$user->username = $username;
			$user->password = Hash::make($password);
			if (!is_null($email))
				$user->email = $email;

			if ($user->save())
			{
				Auth::loginUsingId($user->id);

				$i = 1;

				while ($i < 4)
				{
					$tagSub          = new TagSubscriber;
					$tagSub->user_id = $user->id;
					$tagSub->tag_id  = $i;

					$tagSub->save();

					$i++;
				}

				return response('Registered!', 200);
			}
			else
			{
				return response('Something went wrong on our side, try again.', 500);
			}
		}
		else
		{
			return response('The username you chose is already registered.', 500);
		}
	}

	/**
	 * Login a user
	 *
	 * @param  	Request $request
	 * @return 	response
	 */
	public function login(Request $request)
	{
		if (!$request->ajax())
			abort(404);

		foreach ($request->except('_token') as $input)
		{
			if (empty($input))
				return response('Fill in all fields.', 500);
		}

		$username = $request->get('username');
		$password = $request->get('password');

		if (strlen($username) < 3 || strlen($username) > 15)
			return response('Your username must be between 3 and 15 characters long.', 500);

		if (!preg_match('/^[A-Za-z][A-Za-z0-9_]+$/', $username))
			return response('Your username may only contain the characters A-Z (case-insensitive), 0-9 and _, but may not start with an underscore.', 500);

		if (strlen($password) < 6 || strlen($password) > 21)
			return response('Your password must be between 6 and 21 characters long.', 500);

		if (Auth::attempt(['username' => $username, 'password' => $password]))
			return response('Logged in!', 200);
		else
			return response('Wrong username/password combination.', 500);
	}

	/**
	 * Log a user out
	 *
	 * @return  redirect
	 */
	public function logout()
	{
		Auth::logout();

		if (Session::has('accepted_nsfw'))
			Session::forget('accepted_nsfw');

		return redirect('/');
	}
}