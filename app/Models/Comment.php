<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    private $_thread   = null;
    private $_author   = null;
    private $_parent   = null;
    private $_children = null;

	/**
	 * The table used by this model.
	 *
	 * @var string
	 */
    protected $table = 'comments';

    /**
     * Thread relation
     *
     * @return 	Thread
     */
    public function thread()
    {
        if (!is_null($this->_thread))
            return $this->_thread;

        $cache = Cache::get("comment:{$this->id}:thread");

        if (!is_null($cache))
        {
            $this->_thread = $cache;
            return $this->_thread;
        }

        $this->_thread = $this->hasOne('App\Models\Thread', 'id', 'thread_id')->first();
        Cache::put("comment:{$this->id}:thread", $this->_thread, 60);

    	return $this->_thread;
    }

    /**
     * Author relation
     *
     * @return 	User
     */
    public function author()
    {
        if (!is_null($this->_author))
            return $this->_author;

        $cache = Cache::get("comment:{$this->id}:author");

        if (!is_null($cache))
        {
            $this->_author = $cache;
            return $this->_author;
        }

        $this->_author = $this->hasOne('App\Models\User', 'id', 'author_id')->first();
        Cache::put("comment:{$this->id}:author", $this->_author, 60);

        return $this->_author;
    }

    /**
     * Parent comment (if any).
     *
     * @return 	Comment
     */
    public function parent()
    {
        if (!is_null($this->_parent))
            return $this->_parent;

        $cache = Cache::get("comment:{$this->id}:parent");

        if (!is_null($cache))
        {
            $this->_parent = $cache;
            return $this->_parent;
        }

        $this->_parent = $this->hasOne('App\Models\Comment', 'id', 'parent_id')->first();
        Cache::put("comment:{$this->id}:parent", $this->_parent, 60);

        return $this->_parent;
    }

    /**
     * Child comments (if any)
     *
     * @return 	Comment
     */
    public function children()
    {
        if (!is_null($this->_children))
            return $this->_children;

        $cache = Cache::get("comment:{$this->id}:children");

        if (!is_null($cache))
        {
            $this->_children = $cache;
            return $this->_children;
        }

        $this->_children = $this->hasMany('App\Models\Comment', 'parent_id', 'id')->get();
        Cache::put("comment:{$this->id}:children", $this->_children, 60);

        return $this->_children;
    }
}