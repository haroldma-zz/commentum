<?php

namespace App\Models;

use Auth;

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
     * Check if the current has participated
     * in the poll.
     *
     * @return boolean
     */
    public function userParticipated()
    {
        if (!Auth::check())
            return false;

        if ($this->participants()->where('user_id', Auth::id())->count() > 0)
            return true;

        return false;
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

    /**
     * Get the users that participated
     * to this poll.
     *
     * @return App\Models\User
     */
    public function participants()
    {
        return $this->hasMany('App\Models\UserPollAnswer', 'poll_id', 'id');
    }
}
