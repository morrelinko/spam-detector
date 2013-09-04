<?php namespace Linko\Spam\Provider\Laravel;

use Illuminate\Support\ServiceProvider;
use Linko\Spam\SpamFilter;

/**
 * @author Morrison Laju <morrelinko@gmail.com>
 */
class SpamFilterServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['spamfilter'] = $this->app->share(function ($app) {

            $spamFilter = new SpamFilter();

            return $spamFilter;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('spamfilter');
    }
}
