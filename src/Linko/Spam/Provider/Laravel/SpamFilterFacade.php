<?php namespace Linko\Spam\Provider\Laravel;

use Illuminate\Support\Facades\Facade;

class SpamFilterFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'spamfilter';
    }
}