<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
	/**
	 * The table used by the model.
	 *
	 * @var string
	 */
    protected $table = "polls";

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
     * Available answers of the poll.
     *
     * @return App\Models\PollAnswer
     */
    public function answers()
    {
    	return $this->hasMany('App\Models\PollAnswer', 'poll_id', 'id');
    }
}
