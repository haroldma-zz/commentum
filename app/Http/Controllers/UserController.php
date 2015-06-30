<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Session;
use App\Models\User;
use App\Models\Message;
use App\Models\TagSubscriber;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

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

		if (strlen($username) < 3 || strlen($username) > 21)
			return response('Your username must be between 3 and 21 characters long.', 500);

		if (!preg_match('/^[A-Za-z][A-Za-z0-9_]+$/', $username))
			return response('Your username may only contain the characters A-Z (case-insensitive), 0-9 and _, but may not start with an underscore.', 500);

		if (strlen($password) < 6 || strlen($password) > 100)
			return response('Your password must be between 6 and 100 characters long.', 500);

		if (!is_null($email) && !preg_match('/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/i', $email))
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

	/**
	 * Unread a message.
	 *
	 * @param  string 	$hashid
	 * @return response
	 */
	public function unreadMessage($hashid, Request $request)
	{
		if (!$request->ajax())
			abort(404);

		$pid = Hashids::decode($hashid)[0];
		$iid = Hashids::decode($request->get('id'))[0];

		if ($pid !== $iid)
			abort(404);

		$message = Message::find($pid);

		if (!$message)
			abort(404);

		if ($message->to_id !== Auth::id())
			abort(404);

		$message->read = true;

		if ($message->save())
			return response('Success.', 200);

		return response('Fail.', 500);
	}

	/**
	 * Check if there are any notifications.
	 *
	 * @return 	mixed
	 */
	public function checkNotifications(Request $request)
	{
		if (!$request->ajax())
			abort(404);

		$start = time();
		$end   = $start + 30;

		$uMessages = Auth::user()->altMessages()->where('read', false)->where('notified', false)->get();
		$count     = count($uMessages);
		$check     = $count > 0;

		while ($check == false && $start < $end)
		{
			sleep(5);

			$uMessages = Auth::user()->altMessages()->where('read', false)->where('notified', false)->get();
			$count     = count($uMessages);
			$check     = $count > 0;

			$start = time();
		}

		if ($check)
		{
			foreach($uMessages as $m)
			{
				$m->notified = true;
				$m->save();
			}

			if ($count > 1)
				return response(["multiple" => true, "count" => Auth::user()->altMessages()->where('read', false)->count()]);

			$message = $uMessages[0];

			if ($message->type == 1)
				$message = $message->from()->username . ' commented on your submission.';
			else if ($message->type == 2)
				$message = $message->from()->username . ' replied to your comment.';
			else if ($message->type == 5)
				$message = 'Nice! You claimed #' . $message->tag()->display_title . '.';
			else if ($message->type == 6)
				$message = $message->from()->username . " subscribed to #" . $message->tag()->display_title;

			return response(["multiple" => false, "message" => $message]);
		}
		else
		{
			return response("none");
		}
	}

	/**
	 * Save a thread.
	 *
	 * @param  	Request 	$request
	 * @return 	response
	 */
	public function saveThread(Request $request)
	{
		$id = Hashids::decode($request->get('hashid'))[0];

		// Check if the thread is already saved and if it is, delete it. Otherwise save it.
	}
}











