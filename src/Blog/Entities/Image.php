<?php

namespace Webup\LaravelHelium\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function getUrlAttribute()
    {
        return url('/storage/'.$this->file);
    }
}
