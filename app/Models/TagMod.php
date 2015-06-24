<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class TagMod extends Model
{
    protected $table = 'tag_mods';

    private $_user = null;

    /**
     * Get user profile of mods
     *
     * @return 	User
     */
    public function user()
    {
    	if (!is_null($this->_user))
    		return $this->_user;

    	$cache = Cache::get("tag:{$this->tag_id}:mod:{$this->user_id}");

    	if (!is_null($cache))
    	{
    		$this->_user = $cache;
    		return $this->_user;
    	}

		$user        = $this->hasOne('App\Models\User', 'id', 'user_id')->first();
		$this->_user = $user;

    	Cache::forever("tag:{$this->tag_id}:mod:{$this->user_id}", $this->_user);

    	return $this->_user;
    }
}
