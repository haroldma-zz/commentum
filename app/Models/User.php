<?php

namespace App\Models;

use Cache;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    private $_threads       = null;
    private $_permalink     = null;
    private $_messages      = null;
    private $_messageCount  = null;
    private $_tags          = null;
    private $_subscriptions = null;
    private $_isSubscribed  = null;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Return a permalink to a user's profile.
     *
     * @return  string
     */
    public function permalink()
    {
        if (!is_null($this->_permalink))
            return $this->_permalink;

        $this->_permalink = url("/u/{$this->username}");
        return $this->_permalink;
    }

    /**
     * Thread relation
     *
     * @return  Thread    collection
     */
    public function threads()
    {
        if (!is_null($this->_threads))
            return $this->_threads;

        $threads = $this->hasMany('App\Models\Thread', 'user_id', 'id')->orderBy('id', 'DESC')->get();
        $this->_threads = $threads;

        return $this->_threads;
    }

    /**
     * Message (inbox) relation
     *
     * @return  Message
     */
    public function messages()
    {
        if (!is_null($this->_messages))
            return $this->_messages;

        $messages = $this->hasMany('App\Models\Message', 'to_id', 'id')->orderBy('id', 'DESC')->get();
        $this->_messages = $messages;

        return $this->_messages;
    }

    /**
     * Retrieve the count of unread messages.
     *
     * @return  integer
     */
    public function messageCount()
    {
        if (!is_null($this->_messageCount))
            return $this->_messageCount;

        $this->_messageCount = $this->messages()->where('read', false)->count();

        return $this->_messageCount;
    }

    /**
     * Retrieve tags claimed by user.
     *
     * @return  Tag
     */
    public function tags()
    {
        if (!is_null($this->_tags))
            return $this->_tags;

        $this->_tags = $this->hasMany('App\Models\Tag', 'owner_id', 'id')->get();

        return $this->_tags;
    }

    /**
     * Retrieve subscribed tags
     *
     * @return  Tag
     */
    public function subscriptions()
    {
        if (!is_null($this->_subscriptions))
            return $this->_subscriptions;

        $subscriptions        = $this->hasMany('App\Models\TagSubscriber', 'user_id', 'id')->get();
        $this->_subscriptions = $subscriptions;

        return $this->_subscriptions;
    }

    /**
     * Subscriptions relation without "lazy" loading or Caching.
     *
     * @return  TagSubscriber   Collection
     */
    public function subscriptionsAlt()
    {
        return $this->hasMany('App\Models\TagSubscriber', 'user_id', 'id');
    }

    /**
     * Check if a user is subscribed to a tag.
     *
     * @param   integer  $tagId
     * @return  boolean
     */
    public function isSubscribedToTag($tagId)
    {
        if (!is_null($this->_isSubscribed))
            return $this->_isSubscribed;

        $check = $this->subscriptionsAlt()->where('tag_id', $tagId)->first();

        if (!$check)
            $this->_isSubscribed = false;
        else
            $this->_isSubscribed = true;

        return $this->_isSubscribed;
    }

    /**
     * Get threads of tags that the user is subscribed to.
     *
     * @return  thread    collection
     */
    static function getSubscribedTagsThreads()
    {
        $threads = [];

        // Custom SQL function

        return $threads;
    }

}