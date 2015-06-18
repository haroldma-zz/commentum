<?php

namespace App\Models;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
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
		return url("/t/{$this->tag->display_title}/" . Hashids::encode($this->id));
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
}
