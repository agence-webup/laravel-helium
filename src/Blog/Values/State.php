<?php

namespace Webup\LaravelHelium\Blog\Values;

class State
{
    const DRAFT = 1;
    const SCHEDULED = 2;
    const PUBLISHED = 3;

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function label()
    {
        $list = self::list();

        return array_key_exists($this->value, $list) ? $list[$this->value] : '';
    }

    public function value()
    {
        return $this->value;
    }

    public static function list()
    {
        return [
            self::DRAFT => 'brouillon',
            self::SCHEDULED => 'plannifié',
            self::PUBLISHED => 'en ligne',
        ];
    }
}
