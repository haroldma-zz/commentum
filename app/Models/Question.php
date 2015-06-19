<?php

namespace App\Models;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
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
     * @return 	string
     */
    public function permalink()
    {
		return url("/t/{$this->tag->display_title}/" . Hashids::encode($this->id) . "/{$this->slug}");
    }

    /**
     * Tag relation
     *
     * @return 	Tag
     */
    public function tag()
    {
    	return $this->hasOne('App\Models\Tag', 'id', 'tag_id');
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
