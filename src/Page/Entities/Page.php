<?php

namespace Webup\LaravelHelium\Page\Entities;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['name'];
}
