<?php

namespace LaveroAdmin\Admin\Media;

use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'lavero-admin-media');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'lavero-admin-media');

        MediaManager::boot();
    }
}
