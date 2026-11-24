<?php

namespace LaveroAdmin\Admin\Media;

use LaveroAdmin\Admin\Admin;

trait BootExtension
{
    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        static::registerRoutes();

        Admin::extend('media-manager', __CLASS__);
    }

    /**
     * Register routes for LaveroAdmin.
     *
     * @return void
     */
    protected static function registerRoutes()
    {
        parent::routes(function ($router) {
            /* @var \Illuminate\Routing\Router $router */
            $router->get('media', 'LaveroAdmin\Admin\Media\MediaController@index')->name('media-index');
            $router->get('media/download', 'LaveroAdmin\Admin\Media\MediaController@download')->name('media-download');
            $router->delete('media/delete', 'LaveroAdmin\Admin\Media\MediaController@delete')->name('media-delete');
            $router->put('media/move', 'LaveroAdmin\Admin\Media\MediaController@move')->name('media-move');
            $router->post('media/upload', 'LaveroAdmin\Admin\Media\MediaController@upload')->name('media-upload');
            $router->post('media/folder', 'LaveroAdmin\Admin\Media\MediaController@newFolder')->name('media-new-folder');
        });
    }

    /**
     * {@inheritdoc}
     */
    public static function import()
    {
        parent::createMenu('Media manager', 'media', 'icon-file');

        parent::createPermission('Media manager', 'ext.media-manager', 'media*');
    }
}
