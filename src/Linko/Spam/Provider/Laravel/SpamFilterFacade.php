<?php namespace Linko\Spam\Provider\Laravel;

use Illuminate\Support\Facades\Facade;

/**
 * @author Morrison Laju <morrelinko@gmail.com>
 */
class SpamFilterFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'spamfilter';
    }
}
