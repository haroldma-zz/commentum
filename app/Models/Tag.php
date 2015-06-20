<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	private $_owner     = null;
	private $_permalink = null;

	/**
	 * The database table used by this model.
	 *
	 * @var string
	 */
    protected $table = 'tags';

    /**
     * Return a url to this tag's page
     *
     * @return 	string
     */
    public function permalink()
    {
    	if (!is_null($this->_permalink))
    		return $this->_permalink;

    	$this->_permalink = url("/t/{$this->display_title}");
    	return $this->_permalink;
    }

    /**
     * The owner relation
     *
     * If the model is cached return it, otherwise cache it
     * Uses "lazy" loading
     *
     * @return 	User
     */
    public function owner()
    {
    	if (!is_null($this->_owner))
    		return $this->_owner;

    	$cache = Cache::get("tag:{$this->id}:owner");

    	if (!is_null($cache))
    	{
    		$this->_owner = $cache;
    		return $this->_owner;
    	}

    	$this->_owner = $this->hasOne('App\Models\User', 'owner_id', 'id')->first();
    	Cache::forever("tag:{$this->id}:owner", $this->_owner);

    	return $this->_owner;
    }
}
