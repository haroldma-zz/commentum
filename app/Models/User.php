<?php

namespace App\Models;

use Cache;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Models\Thread;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, EntrustUserTrait;

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
     * Alt message relation
     *
     * @return  Message
     */
    public function altMessages()
    {
        return $this->hasMany('App\Models\Message', 'to_id', 'id');
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

        $this->_messageCount = $this->hasMany('App\Models\Message', 'to_id', 'id')->where('read', false)->orderBy('id', 'DESC')->count();

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
    function subscribedTagsThreads($offset = 0, $limit = 20, $nsfw_mode = 0)
    {
        return User::getSubscribedTagsThreads($this->id, $offset, $limit, $nsfw_mode);
    }

    static function getSubscribedTagsThreads($userId, $offset = 0, $limit = 20, $nsfw_mode = 0)
    {
        return Thread::hydrateRaw('call getThreadsByHotnessForSubscription(?, ?, ?, ?)',
            [$userId, $nsfw_mode, $offset, $limit]);
    }

    /**
     * Check if a user saved a specific thread by id
     *
     * @param   integer  $id
     * @return  boolean
     */
    public function savedThread($id)
    {
        $check = Save::where('user_id', $this->id)->where('thread_id', $id)->first();

        if (!$check)
            return false;

        return true;
    }

    /**
     * Check if a user saved a specific comment by id
     *
     * @param   integer  $id
     * @return  boolean
     */
    public function savedComment($id)
    {
        $check = Save::where('user_id', $this->id)->where('comment_id', $id)->first();

        if (!$check)
            return false;

        return true;
    }

    /**
     * Get saved things
     *
     * @return  array
     */
    public function saves()
    {
        $saves = $this->hasMany('App\Models\Save', 'user_id', 'id')->get();

        $threads  = [];
        $comments = [];

        foreach ($saves as $save)
        {
            if (!is_null($save->thread_id))
                $threads[] = Thread::find($save->thread_id);
            else
                $comments[] = Comment::find($save->comment_id);
        }

        return ["threads" => $threads, "comments" => $comments];
    }
}





