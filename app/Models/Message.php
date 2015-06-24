<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	private $_from    = null;
	private $_to      = null;
	private $_thread  = null;
	private $_comment = null;
	private $_tag     = null;

	/**
	 * The table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'messages';

    /**
     * Retrieve the user that sent the message.
     *
     * @return 	User
     */
    public function from()
    {
    	if (!is_null($this->_from))
    		return $this->_from;

    	$cache = Cache::get("message:{$this->id}:from");

    	if (!is_null($cache))
    	{
    		$this->_from = $cache;
    		return $this->_from;
    	}

		$from        = $this->hasOne('App\Models\User', 'id', 'from_id')->first();
		$this->_from = $from;

		Cache::put("message:{$this->id}:from", $this->_from, 180);

		return $this->_from;
    }

    /**
     * Retrieve the user to whom the message was sent to.
     *
     * @return 	User
     */
    public function to()
    {
    	if (!is_null($this->_to))
    		return $this->_to;

    	$cache = Cache::get("message:{$this->id}:to");

    	if (!is_null($cache))
    	{
    		$this->_to = $cache;
    		return $this->_to;
    	}

		$to        = $this->hasOne('App\Models\User', 'id', 'to_id')->first();
		$this->_to = $to;

		Cache::put("message:{$this->id}:to", $this->_to, 180);

		return $this->_to;
    }

    /**
     * Get the thread associated with the message.
     *
     * @return 	Thread
     */
    public function thread()
    {
    	if (!is_null($this->_thread))
    		return $this->_thread;

		$cache = Cache::get("message:{$this->id}:thread");

		if (!is_null($cache))
		{
			$this->_thread = $cache;
			return $this->_thread;
		}

		$thread        = $this->hasOne('App\Models\Thread', 'id', 'thread_id')->first();
		$this->_thread = $thread;

		Cache::put("message:{$this->id}:thread", $this->_thread, 180);

		return $this->_thread;
    }

    /**
     * Get the Comment associated with this message.
     *
     * @return 	Comment
     */
    public function comment()
    {
    	if (!is_null($this->_comment))
    		return $this->_comment;

    	$cache = Cache::get("message:{$this->id}:comment");

    	if (!is_null($cache))
    	{
    		$this->_comment = $cache;
    		return $this->_comment;
    	}

		$comment        = $this->hasOne('App\Models\Comment', 'id', 'comment_id')->first();
		$this->_comment = $comment;

		Cache::put("message:{$this->id}:comment", $this->_comment, 180);

		return $this->_comment;
    }

    /**
     * Get the Tag associated with this message.
     *
     * @return 	Tag
     */
    public function tag()
    {
    	if (!is_null($this->_tag))
    		return $this->_tag;

    	$cache = Cache::get("message:{$this->id}:tag");

    	if (!is_null($cache))
    	{
    		$this->_tag = $cache;
    		return $this->_tag;
    	}

		$tag        = $this->hasOne('App\Models\Tag', 'id', 'tag_id')->first();
		$this->_tag = $tag;

		Cache::put("message:{$this->id}:tag", $this->_tag, 180);

		return $this->_tag;
    }
}
