<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPollAnswer extends Model
{
    protected $table = "user_poll_answers";

    /**
     * User belonging to the annswer.
     *
     * @return App\Models\User
     */
    public function user()
    {
    	return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
