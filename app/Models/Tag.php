<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    private $_owner           = null;
    private $_permalink       = null;
    private $_threads         = null;
    private $_threadCount     = null;
    private $_mods            = null;
    private $_subscriberCount = null;

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

    	$this->_owner = $this->hasOne('App\Models\User', 'id', 'owner_id')->first();
    	Cache::forever("tag:{$this->id}:owner", $this->_owner);

    	return $this->_owner;
    }

    /**
     * Retrieve threads associated with a tag
     *
     * @return  Thread
     */
    public function threads()
    {
        if (!is_null($this->_threads))
            return $this->_threads;

        $threads        = $this->hasMany('App\Models\Thread', 'tag_id', 'id')->get();
        $this->_threads = $threads;

        return $this->_threads;
    }

    /**
     * Get count of threads associated with this tag.
     *
     * @return  integer
     */
    public function threadCount()
    {
        if (!is_null($this->_threadCount))
            return $this->_threadCount;

        $this->_threadCount = count($this->threads());

        return $this->_threadCount;
    }

    /**
     * Retrieve mods associated with a tag.
     *
     * @return  User
     */
    public function mods()
    {
        if (!is_null($this->_mods))
            return $this->_mods;

        $this->_mods = $this->hasMany('App\Models\TagMod', 'tag_id', 'id')->get();

        return $this->_mods;
    }

    /**
     * Return count of users subscribed to tag.
     *
     * @return  integer
     */
    public function subscriberCount()
    {
        if (!is_null($this->_subscriberCount))
            return $this->_subscriberCount;

        $this->_subscriberCount = $this->hasMany('App\Models\TagSubscriber', 'tag_id', 'id')->count();

        return $this->_subscriberCount;
    }
}















