<?php

namespace App\Models;

use Cache;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use SoftDeletes;

    private $_tag            = null;
    private $_author         = null;
    private $_comments       = null;
    private $_commentCount   = null;
    private $_permalink      = null;
    private $_titlePermalink = null;

	/**
	 * The database table used by this model.
	 *
	 * @var string
	 */
    protected $table = 'threads';

    protected $dates = ['deleted_at'];

    /**
     * Return the permalink to a thread.
     *
     * Uses "lazy" loading.
     *
     * @return  string
     */
    public function permalink()
    {
        if (!is_null($this->_permalink))
            return $this->_permalink;

        $this->_permalink = url("/t/{$this->tag()->display_title}/" . Hashids::encode($this->id) . "/{$this->slug}");

        return $this->_permalink;
    }

    /**
     * Returns the submitted link or a permalink.
     *
     * Uses "lazy" loading.
     *
     * @return  string
     */
    public function titlePermalink()
    {
        if (!is_null($this->_titlePermalink))
            return $this->_titlePermalink;

        if (!empty($this->link))
            $this->_titlePermalink = $this->link;
        else
            $this->_titlePermalink = $this->permalink();

        return $this->_titlePermalink;
    }

    /**
     * Tag relation
     *
     * If the relation is cached return it, otherwise cache it.
     * Uses "lazy" loading.
     *
     * @return 	Tag
     */
    public function tag()
    {
        if (!is_null($this->_tag))
            return $this->_tag;

        // $cache = Cache::get("thread:{$this->id}:tag");

        // if (!is_null($cache))
        // {
        //     $this->_tag = $cache;
        //     return $this->_tag;
        // }

        $this->_tag = $this->hasOne('App\Models\Tag', 'id', 'tag_id')->first();
        // Cache::put("thread:{$this->id}:tag", $this->_tag, 120);

    	return $this->_tag;
    }

    /**
     * Author relation
     *
     * If the relation is cached return it, otherwise cache it.
     * Uses "lazy" loading.
     *
     * @return  User
     */
    public function author()
    {
        if (!is_null($this->_author))
            return $this->_author;

        $cache = Cache::get("thread:{$this->id}:author");

        if (!is_null($cache))
        {
            $this->_author = $cache;
            return $this->_author;
        }

        $this->_author = $this->hasOne('App\Models\User', 'id', 'user_id')->first();
        Cache::put("thread:{$this->id}:author", $this->_author, 120);

        return $this->_author;
    }

    /**
     * Comments
     *
     * Uses "lazy" loading.
     *
     * @return  Comment
     */
    public function comments()
    {
        if (!is_null($this->_comments))
            return $this->_comments;

        $this->_comments = $this->hasMany('App\Models\Comment', 'thread_id', 'id')->withTrashed()->orderBy('momentum', 'DESC')->get();

        return $this->_comments;
    }

    /**
     * Return the comment count.
     *
     * Uses "lazy" loading.
     *
     * @return  integer
     */
    public function commentCount()
    {
        if (!is_null($this->_commentCount))
            return $this->_commentCount;

        $this->_commentCount = $this->comments()->count();
        return $this->_commentCount;
    }

    /**
     * Print thread's comments.
     *
     * @return  view
     */
    public function printComments()
    {
        $html      = '';

        foreach ($this->comments()->where('parent_id', null) as $c)
        {
            $html .= view('layouts.comment')->with(['c' => $c, 'threadId' => $this->id, 'indent' => 2])->render();
        }

        return $html;
    }

    public function addView()
    {
        $this->increment('views');
    }

    public function addImpression()
    {
        $this->increment('impressions');
    }

    public function calculateMomentum()
    {
        return DB::SELECT(DB::RAW("SELECT calculateThreadMomentum(impressions, views, total_momentum) as momentum FROM (SELECT t.*, sum(c.momentum) as total_momentum
        FROM threads t
        LEFT JOIN comments c ON c.thread_id = t.id
        WHERE t.id = ?
        GROUP BY t.id) as v"), [$this->id])[0]->momentum;
    }
}
