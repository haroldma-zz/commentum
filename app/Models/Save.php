<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Save extends Model
{
    protected $table = "saves";

    /**
     * Thread relation
     *
     * @return 	Thread
     */
    public function thread()
    {
    	return $this->hasOne('App\Models\Thread', 'id', 'thread_id');
    }

    /**
     * Comment relation
     *
     * @return 	Comment
     */
    public function comment()
    {
    	return $this->hasOne('App\Models\Comment', 'id', 'comment_id');
    }
}
