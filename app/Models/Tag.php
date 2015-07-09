<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Tag extends Model
{
    private $_owner					= null;
    private $_permalink				= null;
    private $_threads				= null;
    private $_threadCount			= null;
    private $_mods					= null;
    private $_subscriberCount		= null;
    private static $_exploreList	= null;
    private static $_newTags		= null;
	private static $_trendingTags	= null;

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
     * If the model is cached return it, otherwise cache it.
     * Uses "lazy" loading.
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

    /**
     * Get tags for the explore list.
     *
     * @return  Tag
     */
    static function getExploreList()
    {
        if (!is_null(self::$_exploreList))
            return self::$_exploreList;

        $cache = Cache::get("explorelist");

        if (!is_null($cache))
        {
            self::$_exploreList = $cache;
            return self::$_exploreList;
        }

        $list               = self::where('nsfw', false)->orderBy('momentum', 'DESC')->take(100)->get();
        self::$_exploreList = $list;

        Cache::put("explorelist", self::$_exploreList, 60);

        return self::$_exploreList;
    }

    /**
     * Get newest tags list.
     *
     * @return  Tag
     */
    static function getNewTags()
    {
        if (!is_null(self::$_newTags))
            return self::$_newTags;

        // $cache = Cache::get("newtags");

        // if (!is_null($cache))
        // {
        //     self::$_newTags = $cache;
        //     return self::$_newTags;
        // }

        $list = self::where('nsfw', false)->orderBy('id', 'DESC')->take(10)->get();
        self::$_newTags = $list;

        // Cache::put("newtags", self::$_newTags, 10);

        return self::$_newTags;
    }
	
	/**
     * Get trending tags list.
     *
     * @return  Tag
     */
	public static function getTrendingTags($start_time = null, $end_time = null, $max_results = null)
	{
		//if(!is_null(self::$_trendingTags))
		//	return self::$_trendingTags;
		
		/*********************************************/
		/***** YO YO YO IMPLEMENT CACHING MAN ********/
		/*********************************************/
		
		if(is_null($start_time))
			$start_time = Carbon::now()->subDay();
		if(is_null($end_time))
			$end_time = Carbon::now();
		if(is_null($max_results))
			$max_results = 10;
		
		return \DB::select("CALL calculateTrendingTags ('" . $start_time . "', '" . $end_time . "', " . $max_results . ");");
	}

    /**
     * Subscribes a user to a tag.
     *
     * @param  integer  $userId
     * @return boolean
     */
    function subscribe($userId = null)
    {
        $subscription          = new TagSubscriber;
        $subscription->tag_id  = $this->id;
        $subscription->user_id = ($userId != null ? $userId : Auth::id());

        return $subscription->save();
    }

    /**
     * Unubscribes a user from a tag.
     *
     * @param  integer  $userId
     * @return boolean
     */
    function unsubscribe($userId = null)
    {
        $user = User::find($userId != null ? $userId : Auth::id());
        if(!$user)
            return null;

        $subscription = $user->subscriptionsAlt->where('tag_id', $this->id)->first();
        if(!$subscription)
            return null;

        return $subscription->delete();
    }

    /**
     * Gets tag's threads, sort by hotness.
     *
     * @param  integer  $offset
     * @param  integer  $limit
     * @param  integer  $nsfw_mode
     * @return Thread[]
     */
    function threadsByHotness($offset = 0, $limit = 20, $nsfw_mode = 0)
    {
        return Tag::getThreadsByHotness($this->id, $offset, $limit, $nsfw_mode);
    }

    /**
     * Gets threads for a tag, sort by hotness.
     *
     * @param  integer  $tagId
     * @param  integer  $offset
     * @param  integer  $limit
     * @param  integer  $nsfw_mode
     * @return Thread[]
     */
    static function getThreadsByHotness($tagId, $offset = 0, $limit = 20, $nsfw_mode = 0)
    {
        $key = generateCacheKeyWithId('Tag', "getThreadsByHotness_{$offset}_{$limit}_{$nsfw_mode}", $tagId);

        if (hasCache($key, $cache)){
            return $cache;
        }

        $threads = Thread::hydrateRaw('call getThreadsByHotness(?, ?, ?, ?)', [$tagId, $nsfw_mode, $offset, $limit]);

        return setCacheWithSeconds($key, $threads, \CACHE_SECONDS::FRONT_PAGE);
    }

    /**
     * Check if the logged in user is a mod of a tag.
     *
     * @param  	integer 	$userId
     * @return 	boolean
     */
    public function isMod($userId = null)
    {
        return Tag::isModOfTag($this->id, $userId);
    }

    public static function isModOfTag($tagId, $userId = null)
    {
        if ($userId == null)
            $userId = Auth::id();
        return TagMod::where('tag_id', $tagId)->where('user_id', $userId)->exists();
    }
}















