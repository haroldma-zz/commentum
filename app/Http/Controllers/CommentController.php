<?php

namespace App\Http\Controllers;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Thread;
use Auth;

class CommentController extends Controller
{
	/**
	 * Submit a comment
	 *
	 * @return 	response
	 */
	public function submit(Request $request)
	{
		if (!$request->ajax())
			abort(404);

		// Check if message is empty first
		$markdown = $request->markdown;

		if (empty($markdown))
			return response("You can't submit a comment without content.", 500);

		if (strlen($markdown) > 5000)
			return response("Your comment can't be longer than 5000 characters.", 500);

		// Check if thread exists
		$threadId = Hashids::decode($request->thread_id);

		if (!count($threadId) > 0)
			return response("What are you doing?", 500);

		$thread = Thread::find($threadId[0]);

		if (!$thread)
			return response("Thread not found. It might have been deleted while you were commenting.", 500);

		// Check if parent is set
		$parentId = Hashids::decode($request->get('parent_id'))[0];

		if ($parentId == 0)
			$parentId = null;

		// Create new comment
		$comment = new Comment;
		$comment->thread_id = $thread->id;
		$comment->parent_id = $parentId;
		$comment->author_id = Auth::id();
		$comment->markdown  = $markdown;

		// Check momentum
		if ($comment->save())
		{
			if (is_null($parentId))
			{
				$toId = $thread->author()->id;
			}
			else
			{
				$toId = $comment->parent()->author()->id;

				// Calculate momentum for parent Comment
				$momentumStart = strtotime($comment->parent()->created_at);
				$momentumEnd   = strtotime("now");

				$commentMomentum = calculateMomentum($momentumEnd - $momentumStart);

				$comment->parent()->momentum = $comment->parent()->momentum + $commentMomentum;
				$comment->parent()->save();
			}

			if ($toId != Auth::id())
				sendMessage($toId, Auth::id(), $thread->id, $parentId, null, $markdown, ($parentId == null ? 1 : 2));

			$lastComment = Comment::where('thread_id', $thread->id)->orderBy('id', 'DESC')->take(1)->skip(1)->first();

			if (!$lastComment)
				$momentumStart = 0;
			else
				$momentumStart = strtotime($lastComment->created_at);

			$momentumEnd = strtotime("now");
			$difference  = $momentumEnd - $momentumStart;

			// Calculate momentum to be added
			$momentumAdd = calculateMomentum($difference);

			$thread->momentum = $thread->momentum + $momentumAdd;

			if ($thread->save())
			{
				$parentMomentum = (!$comment->parent() ?  'whoopie' : floor($comment->parent()->momentum));
				return response(['threadId' => Hashids::encode($thread->id), 'commentId' => Hashids::encode($comment->id), 'parentMomentum' => $parentMomentum], 200);
			}
			else
				return response("Something went wrong on our end, try again.", 500);
		}
		else
		{
			return response("Something went wrong on our end, try again.", 500);
		}
	}
}