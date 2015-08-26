<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollAnswer extends Model
{
	/**
	 * The table used by this model.
	 *
	 * @var string
	 */
    protected $table = "poll_answers";

    /**
     * Hashid of the model.
     *
     * @return string
     */
    public function hashid()
    {
        return hashID($this->id);
    }

    /**
     * User who answers this answer.
     *
     * @return App\Models\User
     */
    public function answers()
    {
    	return $this->hasMany('App\Models\UserPollAnswer', 'answer_id', 'id');
    }
}
