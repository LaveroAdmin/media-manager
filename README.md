# Media manager for LaveroAdmin

Local and cloud filesystem media manager for LaveroAdmin.

Repository: [https://github.com/LaveroAdmin/media-manager](https://github.com/LaveroAdmin/media-manager)

## Installation

```bash
composer require laveroadmin/media-manager

php artisan admin:import media-manager
```

Configure disk in `config/admin.php`:

```php
'extensions' => [
    'media-manager' => [
        // Select a disk configured in config/filesystems.php
        'disk' => 'public',
    ],
],
```

Open `/admin/media` in your browser.

## License

Licensed under [The MIT License (MIT)](LICENSE).

## Thanks

- Special thanks to [open-admin](https://github.com/open-admin-org/open-admin) for open-admin development.
- Special thanks to [z-song](https://github.com/z-song) for original development.
