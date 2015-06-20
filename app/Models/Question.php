<?php

namespace App\Models;

use Cache;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    private $_commentCount = null;
    private $_tag = null;

	/**
	 * The database table used by this model.
	 *
	 * @var string
	 */
    protected $table = 'questions';

    /**
     * Return the permalink to a question.
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
     * @return  User
     */
    public function author()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    /**
     * Comments
     *
     * @return  Comment
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'question_id', 'id');
    }

    /**
     * Return the comment count.
     *
     * @return  integer
     */
    public function commentCount()
    {
        if (!is_null($this->_commentCount))
            return $this->_commentCount;

        $this->_commentCount = $this->comments->count();
        return $this->_commentCount;
    }
}
