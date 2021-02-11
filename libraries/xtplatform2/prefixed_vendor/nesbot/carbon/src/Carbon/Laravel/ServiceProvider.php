<?php
/* This file has been prefixed by <PHP-Prefixer> for "XT Platform" on 2019-09-01 09:25:38 */

namespace XTP_BUILD\Carbon\Laravel;

use XTP_BUILD\Carbon\Carbon;
use XTP_BUILD\Carbon\CarbonImmutable;
use XTP_BUILD\Carbon\CarbonInterval;
use XTP_BUILD\Carbon\CarbonPeriod;
use XTP_BUILD\Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use XTP_BUILD\Illuminate\Events\Dispatcher;
use XTP_BUILD\Illuminate\Events\EventDispatcher;
use XTP_BUILD\Illuminate\Support\Carbon as IlluminateCarbon;
use XTP_BUILD\Illuminate\Support\Facades\Date;
use Throwable;

class ServiceProvider extends \XTP_BUILD\Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->updateLocale();

        if (!$this->app->bound('events')) {
            return;
        }

        $service = $this;
        $events = $this->app['events'];

        if ($this->isEventDispatcher($events)) {
            $events->listen(class_exists('XTP_BUILD\Illuminate\Foundation\Events\LocaleUpdated') ? 'XTP_BUILD\Illuminate\Foundation\Events\LocaleUpdated' : 'locale.changed', function () use ($service) {
                $service->updateLocale();
            });
        }
    }

    public function updateLocale()
    {
        $app = $this->app && method_exists($this->app, 'getLocale') ? $this->app : app('translator');
        $locale = $app->getLocale();
        Carbon::setLocale($locale);
        CarbonImmutable::setLocale($locale);
        CarbonPeriod::setLocale($locale);
        CarbonInterval::setLocale($locale);

        // @codeCoverageIgnoreStart
        if (class_exists(IlluminateCarbon::class)) {
            IlluminateCarbon::setLocale($locale);
        }

        if (class_exists(Date::class)) {
            try {
                $root = Date::getFacadeRoot();
                $root->setLocale($locale);
            } catch (Throwable $e) {
                // Non Carbon class in use in Date facade
            }
        }
        // @codeCoverageIgnoreEnd
    }

    public function register()
    {
        // Needed for Laravel < 5.3 compatibility
    }

    protected function isEventDispatcher($instance)
    {
        return $instance instanceof EventDispatcher
            || $instance instanceof Dispatcher
            || $instance instanceof DispatcherContract;
    }
}
