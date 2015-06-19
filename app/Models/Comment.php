<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	/**
	 * The table used by this model.
	 *
	 * @var string
	 */
    protected $table = 'comments';

    /**
     * Question relation
     *
     * @return 	Question
     */
    public function question()
    {
    	return $this->hasOne('App\Models\Question', 'id', 'question_id');
    }

    /**
     * Author relation
     *
     * @return 	User
     */
    public function author()
    {
    	return $this->hasOne('App\Models\User', 'id', 'author_id');
    }

    /**
     * Parent comment (if any).
     *
     * @return 	Comment
     */
    public function parent()
    {
    	return $this->hasOne('App\Models\Comment', 'id', 'parent_id');
    }

    /**
     * Child comments (if any)
     *
     * @return 	Comment
     */
    public function children()
    {
    	return $this->hasMany('App\Models\Comment', 'parent_id', 'id');
    }
}