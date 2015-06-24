<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class TagSubscriber extends Model
{
    protected $table = 'tag_subscribers';

    private $_tag = null;

    /**
     * Get the tag associated with a subscription.
     *
     * @return 	Tag
     */
    public function tag()
    {
    	if (!is_null($this->_tag))
	    	return $this->_tag;

	    $cache = Cache::get("subscription:{$this->id}:tag");

	    if (!is_null($cache))
	    {
	    	$this->_tag = $cache;
	    	return $this->_tag;
	    }

		$tag        = $this->hasOne('App\Models\Tag', 'id', 'tag_id')->first();
		$this->_tag = $tag;

		Cache::put("subscription:{$this->id}:tag", $this->_tag, 120);

		return $this->_tag;
    }
}
