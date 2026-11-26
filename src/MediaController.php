<?php

namespace LaveroAdmin\Admin\Media;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LaveroAdmin\Admin\Facades\Admin;
use LaveroAdmin\Admin\Layout\Content;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        return Admin::content(function (Content $content) use ($request) {
            $path = $request->get('path', '/');
            $view = $request->get('view', 'table');
            $select = $request->get('select', false);
            $close = $request->get('close', false);
            $fn = $request->get('fn', 'selectFile');

            $manager = new MediaManager($path);
            $manager->select_fn = $fn;

            $content->header(trans('lavero-admin-media::media.media_manager'));
            $content->body(view("lavero-admin-media::$view", [
                'list'      => $manager->ls(),
                'view'      => $view,
                'nav'       => $manager->navigation(),
                'url'       => $manager->urls(),
                'close'     => $close,
                'select'    => $select,
                'fn'        => $fn,
            ]));

            if ($select) {
                $content->addBodyClass('hide-nav');
            }
        });
    }

    public function download(Request $request)
    {
        $file = $request->get('file');

        $manager = new MediaManager($file);

        return $manager->download();
    }

    public function upload(Request $request)
    {
        $files = $request->file('files');
        $dir = $request->get('dir', '/');

        $manager = new MediaManager($dir);

        try {
            if ($manager->upload($files)) {
                admin_toastr(trans('admin.upload_succeeded'));
            }
        } catch (\Exception $e) {
            admin_toastr($e->getMessage(), 'error');
        }

        return back();
    }

    public function delete(Request $request)
    {
        $files = $request->json('files');
        $manager = new MediaManager();

        try {
            if ($manager->delete($files)) {
                return response()->json([
                    'status'  => true,
                    'message' => trans('admin.delete_succeeded'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function move(Request $request)
    {
        $path = $request->get('path');
        $new = $request->get('new');

        $manager = new MediaManager($path);

        try {
            if ($manager->move($new)) {
                return response()->json([
                    'status'  => true,
                    'message' => trans('admin.move_succeeded'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function newFolder(Request $request)
    {
        $dir = $request->get('dir');
        $name = $request->get('name');

        $manager = new MediaManager($dir);

        try {
            if ($manager->newFolder($name)) {
                return response()->json([
                    'status'  => true,
                    'message' => trans('lavero-admin-media::media.create_folder_succeeded'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function processImage(Request $request)
    {
        $path = (string) $request->input('path');
        $width = max(0, (int) $request->input('width', 0));
        $height = max(0, (int) $request->input('height', 0));
        $toWebp = filter_var($request->input('webp', false), FILTER_VALIDATE_BOOLEAN);

        try {
            $manager = new MediaManager($path);
            $resultPath = $manager->processImage($width, $height, $toWebp);

            return response()->json([
                'status'  => true,
                'message' => __('lavero-admin-media::media.image_processed', ['path' => basename($resultPath)]),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
