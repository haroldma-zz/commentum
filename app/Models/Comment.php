<?php

namespace App\Models;

use Cache;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    private $_thread   = null;
    private $_author   = null;
    private $_parent   = null;

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

        $this->_thread = $this->hasOne('App\Models\Thread', 'id', 'thread_id')->withTrashed()->first();
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

        $this->_parent = $this->hasOne('App\Models\Comment', 'id', 'parent_id')->first();

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

        $this->_children = $this->hasMany('App\Models\Comment', 'parent_id', 'id')->withTrashed()->orderBy('momentum', 'DESC')->get();

        return $this->_children;
    }


    /**
     * Print comment's children.
     *
     * @return  view
     */
    public function printChildren($i)
    {
        $html      = '';

        foreach ($this->children()->where('parent_id', $this->id) as $c)
        {
            $html .= view('layouts.comment')->with(['c' => $c, 'threadId' => $this->thread()->id, 'indent' => $i])->render();
        }

        return $html;
    }

    /**
     * Fetch all parents of a comment.
     *
     * @return  array
     */
    public function grandParents()
    {
        $grandParents = [];

        if (!is_null($this->parent()))
        {
            $grandParents[] = $this->parent()->id;

            $i       = true;
            $current = $this->parent();

            while ($i == true)
            {
                if (!is_null($current->parent()))
                {
                    $grandParents[] = $current->parent()->id;
                    $current = $current->parent();
                }
                else
                {
                    $i = false;
                }
            }
        }

        return $grandParents;
    }

    /**
     * Return permalink of a comment.
     *
     * @return  string
     */
    public function permalink()
    {
        return url("/t/{$this->thread()->tag()->display_title}/" . Hashids::encode($this->thread()->id) . "/{$this->thread()->slug}/" . Hashids::encode($this->id));
    }

    /**
     * Return context link of a comment.
     *
     * @return  string
     */
    public function context()
    {
        return url("/t/{$this->thread()->tag()->display_title}/" . Hashids::encode($this->thread()->id) . "/{$this->thread()->slug}/" . Hashids::encode($this->id)) . "?context=true";
    }
}








