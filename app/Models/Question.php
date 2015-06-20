<?php

namespace App\Models;

use Cache;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    private $_tag          = null;
    private $_author       = null;
    private $_comments     = null;
    private $_commentCount = null;

	/**
	 * The database table used by this model.
	 *
	 * @var string
	 */
    protected $table = 'questions';

    /**
     * Return the permalink to a question.
     *
     * Uses "lazy" loading.
     *
     * @return 	string
     */
    public function permalink()
    {
        if (!is_null($this->_permalink))
            return $this->_permalink;

        $this->_permalink = url("/t/{$this->tag()->display_title}/" . Hashids::encode($this->id) . "/{$this->slug}");

		return $this->_permalink;
    }

    /**
     * Tag relation
     *
     * If the relation is cached return it, otherwise cache it.
     * Uses "lazy" loading.
     *
     * @return 	Tag
     */
    public function tag()
    {
        if (!is_null($this->_tag))
            return $this->_tag;

        $cache = Cache::get("question:{$this->id}:tag");

        if (!is_null($cache))
        {
            $this->_tag = $cache;
            return $this->_tag;
        }

        $this->_tag = $this->hasOne('App\Models\Tag', 'id', 'tag_id')->first();
        Cache::put("question:{$this->id}:tag", $this->_tag, 120);

    	return $this->_tag;
    }

    /**
     * Author relation
     *
     * If the relation is cached return it, otherwise cache it.
     * Uses "lazy" loading.
     *
     * @return  User
     */
    public function author()
    {
        if (!is_null($this->_author))
            return $this->_author;

        $cache = Cache::get("question:{$this->id}:author");

        if (!is_null($cache))
        {
            $this->_author = $cache;
            return $this->_author;
        }

        $this->_author = $this->hasOne('App\Models\User', 'id', 'user_id')->first();
        Cache::put("question:{$this->id}:author", $this->_author, 120);

        return $this->_author;
    }

    /**
     * Comments
     *
     * Uses "lazy" loading.
     *
     * @return  Comment
     */
    public function comments()
    {
        if (!is_null($this->_comments))
            return $this->_comments;

        $this->_comments = $this->hasMany('App\Models\Comment', 'question_id', 'id')->get();

        return $this->_comments;
    }

    /**
     * Return the comment count.
     *
     * Uses "lazy" loading.
     *
     * @return  integer
     */
    public function commentCount()
    {
        if (!is_null($this->_commentCount))
            return $this->_commentCount;

        $this->_commentCount = $this->comments()->count();
        return $this->_commentCount;
    }
}
