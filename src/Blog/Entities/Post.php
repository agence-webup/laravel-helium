<?php

namespace Webup\LaravelHelium\Blog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'thumbnail',
        'image',
        'content',
        'seo_title',
        'seo_description',
        'slug',
        'state',
        'published_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'published_at',
    ];

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset('/storage/'.$this->thumbnail) : null;
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('/storage/'.$this->image) : null;
    }

    public function getContentAttribute()
    {
        if (array_key_exists('content', $this->attributes)) {
            return new HtmlString($this->attributes['content']);
        }

        return null;
    }

    public function getStateAttribute()
    {
        if (array_key_exists('state', $this->attributes)) {
            return new \Webup\LaravelHelium\Blog\Values\State($this->attributes['state']);
        }

        return null;
    }
}
