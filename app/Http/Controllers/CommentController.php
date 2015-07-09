<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Thread;
use App\Models\Message;
use Auth;
use Zizaco\Entrust\EntrustFacade;

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

        if (!EntrustFacade::can('create-comment'))
            return response('You don\'t have permission to post comments.', 400);

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
				// Set the id to who a notification should be sent to
				$toId = $thread->author()->id;
			}
			else
			{
				$toId = $comment->parent()->author()->id;

				$i = 1;

				// Give momentum too each parent
				foreach($comment->grandParents() as $parentComment)
				{
					$parentComment = Comment::find($parentComment);

					// Only if the comment is not from current user
					if ($parentComment->author()->id != Auth::id())
					{
						// Calculate momentum for parent Comment
						$momentumStart = strtotime($comment->parent()->created_at);
						$momentumEnd   = strtotime("now");

						$commentMomentum = calculateCommentMomentum($momentumEnd - $momentumStart) / $i;

						$parentComment->momentum = $parentComment->momentum + $commentMomentum;
						$parentComment->save();

						if ($i > 0.1)		// Decrement the momentum divider
							$i - 0.1;
					}
				}
			}

			if ($toId != Auth::id())
			{
				// Notify author of replied-to comment
				sendMessage($toId, Auth::id(), $thread->id, $comment->id, $parentId, null, $markdown, ($parentId == null ? 1 : 2));

				// Get last comment of current user, before this one.
				$lastCommentOfCurrentUser = Comment::where('author_id', Auth::id())->where('thread_id', $thread->id)->orderBy('id', 'DESC')->skip(1)->first();

				// If there's non, or it was longer than an hour ago, give the thread momentum.
				if (!$lastCommentOfCurrentUser || strtotime($lastCommentOfCurrentUser->created_at) < strtotime("now") - 60)
				{
					$lastComment = Comment::where('thread_id', $thread->id)->orderBy('id', 'DESC')->take(1)->skip(1)->first();

					if (!$lastComment)
						$momentumStart = 0;
					else
						$momentumStart = strtotime($lastComment->created_at);

					$momentumEnd = strtotime("now");
					$difference  = $momentumEnd - $momentumStart;

					// Calculate momentum to be added
					$momentumAdd = calculateMomentum($difference);

					//$thread->momentum = $thread->momentum + $momentumAdd;

					//if ($thread->save())
					if($thread->calculateMomentum())
					{
						$parentMomentum = (!$comment->parent() ?  'whoopie' : floor($comment->parent()->momentum));
						return response(['threadId' => Hashids::encode($thread->id), 'commentId' => Hashids::encode($comment->id), 'parentMomentum' => $parentMomentum, 'permalink' => $comment->permalink(), 'context' => $comment->context()], 200);
					}
					else
						return response("Something went wrong on our end, try again.", 500);
				}
				else
				{
					$parentMomentum = (!$comment->parent() ?  'whoopie' : floor($comment->parent()->momentum));
					return response(['threadId' => Hashids::encode($thread->id), 'commentId' => Hashids::encode($comment->id), 'parentMomentum' => $parentMomentum, 'permalink' => $comment->permalink(), 'context' => $comment->context()], 200);
				}
			}
			else
			{
				$parentMomentum = (!$comment->parent() ?  'whoopie' : floor($comment->parent()->momentum));
				return response(['threadId' => Hashids::encode($thread->id), 'commentId' => Hashids::encode($comment->id), 'parentMomentum' => $parentMomentum, 'permalink' => $comment->permalink(), 'context' => $comment->context()], 200);
			}
		}
		else
		{
			return response("Something went wrong on our end, try again.", 500);
		}
	}

	/**
	 * Edit a comment
	 * @param  	Request 	$request
	 * @return 	response
	 */
	public function edit(Request $request)
	{
		if (!$request->ajax())
			abort(404);

		$hash = Hashids::decode($request->get('hashid'));

		if (!count($hash) > 0)
			return response("Can't find that comment.", 500);

		$comment = Comment::find($hash[0]);

		if (!$comment)
			return response("Can't find that comment.", 500);

		if ($comment->author_id !== Auth::id() && !EntrustFacade::can('edit-comment'))
			return response("You are not authorized to edit this comment.", 500);

		// Update the comment
		$comment->markdown = $request->get('markdown');

		if ($comment->save())
			return response("OK", 200);

		return response("Couldn't update this comment, try again.", 500);
	}

	/**
	 * Delete a comment
	 *
	 * @param  	Request 	$request
	 * @return 	response
	 */
	public function delete(Request $request)
	{
		$id = Hashids::decode($request->get('hashid'));

		if (!$id > 0)
			return response('Can\'t find the comment you want to delete.', 500);

		$comment = Comment::find($id[0]);

		if (!$comment)
			return response("Can't find that comment.", 500);

		if ($comment->author_id != Auth::id()
            && !EntrustFacade::can('remove-comment')
            && !Tag::isModOfTag($comment->thread()->tag_id))
			return response("You're not the owner of this comment.", 500);

		// we need to delete corresponding notifications.
        Message::where('comment_id', $id)->delete();

		// Soft delete the model
		$comment->delete();

		return response("OK", 200);
	}
}










