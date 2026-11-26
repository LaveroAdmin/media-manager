<?php

namespace LaveroAdmin\Admin\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Local\LocalFilesystemAdapter;
use LaveroAdmin\Admin\Exception\Handler;
use LaveroAdmin\Admin\Extension;

/**
 * Class MediaManager.
 */
class MediaManager extends Extension
{
    use BootExtension;

    protected $v;

    /**
     * @var string
     */
    protected $path = '/';

    /**
     * @var \Illuminate\Filesystem\FilesystemAdapter
     */
    protected $storage;

    /**
     * @var
     */
    protected $adapter;

    /**
     * @var bool
     */
    protected $disk_has_url = false;

    /**
     * @var Url
     */
    protected $disk_url = '';

    /**
     * @var array
     */
    protected $fileTypes = [
        'image' => 'png|jpg|jpeg|tmp|gif',
        'word'  => 'doc|docx',
        'ppt'   => 'ppt|pptx',
        'pdf'   => 'pdf',
        'code'  => 'php|js|java|python|ruby|go|c|cpp|sql|m|h|json|html|aspx',
        'zip'   => 'zip|tar\.gz|rar|rpm',
        'txt'   => 'txt|pac|log|md',
        'audio' => 'mp3|wav|flac|3pg|aa|aac|ape|au|m4a|mpc|ogg',
        'video' => 'mkv|rmvb|flv|mp4|avi|wmv|rm|asf|mpeg',
    ];

    /**
     * MediaManager constructor.
     *
     * @param string $path
     */
    public function __construct($path = '/')
    {
        $this->path = $path;
        $this->v    = 8;

        $this->initStorage();
    }

    private function getAdapter()
    {
        if (method_exists($this->storage, 'getAdapter')) {
            // laravel >= 9 (including 10, 11, 12, 13)
            $this->v = 9;

            return $this->storage->getAdapter();
        } else {
            // laravel <= 8
            $this->v = 8;

            return $this->storage->getDriver()->getAdapter();
        }
    }

    private function initStorage()
    {
        $disk   = static::config('disk');
        $config = config('filesystems.disks.'.$disk);

        if (!empty($config['url'])) {
            $this->disk_has_url = true;
            $this->disk_url     = $config['url'];
        }

        $this->storage = Storage::disk($disk);
        $this->adapter = $this->getAdapter();

        if (!$this->adapter instanceof LocalFilesystemAdapter && !(class_exists(Local::class) && $this->adapter instanceof Local)) {
            Handler::error(trans('lavero-admin-media::media.error'), trans('lavero-admin-media::media.only_local_storage'));
        }
    }

    public function ls()
    {
        if (!$this->exists()) {
            Handler::error(trans('lavero-admin-media::media.error'), trans('lavero-admin-media::media.not_exists', ['path' => $this->path]));

            return [];
        }

        $files = $this->storage->files($this->path);

        $directories = $this->storage->directories($this->path);

        return $this->formatDirectories($directories)
            ->merge($this->formatFiles($files))
            ->sort(function ($item) {
                return $item['name'];
            })->all();
    }

    /**
     * Get full path for a giving fiel path.
     *
     * @param string $path
     *
     * @return string
     */
    protected function getFullPath($path)
    {
        if ($this->v >= 9 || method_exists($this->storage, 'path')) {
            return $this->storage->path($path);
        } else {
            return $this->storage->getDriver()->getAdapter()->applyPathPrefix($path);
        }
    }

    public function download()
    {
        $fullPath = $this->getFullPath($this->path);

        if (File::isFile($fullPath)) {
            return response()->download($fullPath);
        }

        return response('', 404);
    }

    public function delete($path)
    {
        $paths = is_array($path) ? $path : func_get_args();

        foreach ($paths as $path) {
            $fullPath = $this->getFullPath($path);

            if (is_file($fullPath)) {
                $this->storage->delete($path);
            } else {
                $this->storage->deleteDirectory($path);
            }
        }

        return true;
    }

    public function move($new)
    {
        return $this->storage->move($this->path, $new);
    }

    /**
     * @param UploadedFile[] $files
     * @param string         $dir
     *
     * @return mixed
     */
    public function upload($files = [])
    {
        foreach ($files as $file) {
            $this->storage->putFileAs($this->path, $file, $file->getClientOriginalName());
        }

        return true;
    }

    public function newFolder($name)
    {
        $path = rtrim($this->path, '/').'/'.trim($name, '/');

        return $this->storage->makeDirectory($path);
    }

    public function exists()
    {
        $path = $this->getFullPath($this->path);

        return file_exists($path);
    }

    /**
     * @return array
     */
    public function urls()
    {
        return [
            'path'       => $this->path,
            'index'      => route('media-index'),
            'move'       => route('media-move'),
            'delete'     => route('media-delete'),
            'upload'     => route('media-upload'),
            'new-folder' => route('media-new-folder'),
        ];
    }

    public function formatFiles($files = [])
    {
        $files = array_map(function ($file) {
            return [
                'download' => route('media-download', compact('file')),
                'icon'     => '',
                'name'     => $file,
                'preview'  => $this->getFilePreview($file),
                'isDir'    => false,
                'size'     => $this->getFilesize($file),
                'link'     => route('media-download', compact('file')),
                'url'      => $this->storage->url($file),
                'time'     => $this->getFileChangeTime($file),
            ];
        }, $files);

        return collect($files);
    }

    public function formatDirectories($dirs = [])
    {
        $url = route('media-index', ['path' => '__path__', 'view' => request('view')]);

        $preview = "<a href=\"$url\"><span class=\"file-icon text-primary\"><i class=\"icon-folder\"></i></span></a>";

        $dirs = array_map(function ($dir) use ($preview) {
            return [
                'download' => '',
                'icon'     => '',
                'name'     => $dir,
                'preview'  => str_replace('__path__', $dir, $preview),
                'isDir'    => true,
                'size'     => '',
                'link'     => route('media-index', ['path' => '/'.trim($dir, '/'), 'view' => request('view'), 'select' => request('select'), 'fn' => $this->select_fn]),
                'url'      => $this->storage->url($dir),
                'time'     => $this->getFileChangeTime($dir),
            ];
        }, $dirs);

        return collect($dirs);
    }

    public function navigation()
    {
        $folders = explode('/', $this->path);

        $folders = array_filter($folders);

        $path = '';

        $navigation = [];

        foreach ($folders as $folder) {
            $path = rtrim($path, '/').'/'.$folder;

            $navigation[] = [
                'name' => $folder,
                'url'  => route('media-index', ['path' => $path]),
            ];
        }

        return $navigation;
    }

    public function getFilePreview($file)
    {
        switch ($this->detectFileType($file)) {
            case 'image':
                if ($this->disk_has_url) {
                    $url     = $this->storage->url($file);
                    $preview = "<span class=\"file-icon has-img\"><img src=\"$url\" alt=\"Attachment\"></span>";
                } else {
                    $preview = '<span class="file-icon"><i class="icon-file-image"></i></span>';
                }
                break;

            case 'pdf':
                $preview = '<span class="file-icon"><i class="icon-file-pdf"></i></span>';
                break;

            case 'zip':
                $preview = '<span class="file-icon"><i class="icon-file-archive"></i></span>';
                break;

            case 'word':
                $preview = '<span class="file-icon"><i class="icon-file-word"></i></span>';
                break;

            case 'ppt':
                $preview = '<span class="file-icon"><i class="icon-file-powerpoint"></i></span>';
                break;

            case 'xls':
                $preview = '<span class="file-icon"><i class="icon-file-excel"></i></span>';
                break;

            case 'txt':
                $preview = '<span class="file-icon"><i class="icon-file-text"></i></span>';
                break;

            case 'code':
                $preview = '<span class="file-icon"><i class="icon-code"></i></span>';
                break;

            default:
                $preview = '<span class="file-icon"><i class="icon-file"></i></span>';
        }

        return $preview;
    }

    protected function detectFileType($file)
    {
        $extension = File::extension($file);

        foreach ($this->fileTypes as $type => $regex) {
            if (preg_match("/^($regex)$/i", $extension) !== 0) {
                return $type;
            }
        }

        return false;
    }

    public function getFilesize($file)
    {
        $bytes = filesize($this->getFullPath($file));

        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    public function getFileChangeTime($file)
    {
        $time = filectime($this->getFullPath($file));

        return date('Y-m-d H:i:s', $time);
    }

    /**
     * Resize and/or convert an image to WebP using Intervention Image or GD fallback.
     *
     * @param int $maxWidth
     * @param int $maxHeight
     * @param bool $toWebp
     * @return string Relative path of resulting file
     */
    public function processImage(int $maxWidth = 0, int $maxHeight = 0, bool $toWebp = false): string
    {
        if ($this->detectFileType($this->path) !== 'image') {
            throw new \InvalidArgumentException('Selected file is not a supported image.');
        }

        $sourceFull = $this->getFullPath($this->path);
        if (!is_file($sourceFull)) {
            throw new \RuntimeException(trans('lavero-admin-media::media.not_exists', ['path' => $this->path]));
        }

        $ext = strtolower(pathinfo($this->path, PATHINFO_EXTENSION));
        $targetRelative = ($toWebp && $ext !== 'webp')
            ? preg_replace('/\.[^.]+$/', '.webp', $this->path)
            : $this->path;
        $targetFull = $this->getFullPath($targetRelative);

        // Try Intervention Image v3/v4 if available
        if (class_exists(\Intervention\Image\ImageManager::class)) {
            try {
                $manager = \Intervention\Image\ImageManager::gd();
                $image = $manager->read($sourceFull);
                if ($maxWidth > 0 || $maxHeight > 0) {
                    $image->scaleDown(
                        width: $maxWidth > 0 ? $maxWidth : null,
                        height: $maxHeight > 0 ? $maxHeight : null
                    );
                }
                if ($toWebp) {
                    $image->toWebp(85)->save($targetFull);
                } else {
                    $image->save($targetFull);
                }

                return $targetRelative;
            } catch (\Throwable) {
                // Fall through to native GD
            }
        }

        if (!function_exists('imagecreatefromstring')) {
            throw new \RuntimeException('PHP GD extension is required for image processing.');
        }

        $raw = file_get_contents($sourceFull);
        $img = $raw !== false ? @imagecreatefromstring($raw) : false;
        if ($img === false) {
            throw new \RuntimeException('Unable to decode image.');
        }

        try {
            $origW = imagesx($img);
            $origH = imagesy($img);
            $newW = $origW;
            $newH = $origH;

            if ($maxWidth > 0 && $newW > $maxWidth) {
                $newH = (int) round($newH * ($maxWidth / $newW));
                $newW = $maxWidth;
            }
            if ($maxHeight > 0 && $newH > $maxHeight) {
                $newW = (int) round($newW * ($maxHeight / $newH));
                $newH = $maxHeight;
            }

            if ($newW !== $origW || $newH !== $origH) {
                $resized = imagescale($img, max(1, $newW), max(1, $newH));
                if ($resized !== false) {
                    imagedestroy($img);
                    $img = $resized;
                }
            }

            imagepalettetotruecolor($img);
            imagealphablending($img, true);
            imagesavealpha($img, true);

            if ($toWebp) {
                if (!function_exists('imagewebp')) {
                    throw new \RuntimeException('GD WebP support is not enabled on this server.');
                }
                imagewebp($img, $targetFull, 85);
            } elseif ($ext === 'png') {
                imagepng($img, $targetFull, 6);
            } elseif ($ext === 'gif') {
                imagegif($img, $targetFull);
            } elseif ($ext === 'webp' && function_exists('imagewebp')) {
                imagewebp($img, $targetFull, 85);
            } else {
                imagejpeg($img, $targetFull, 88);
            }
        } finally {
            imagedestroy($img);
        }

        return $targetRelative;
    }
}
