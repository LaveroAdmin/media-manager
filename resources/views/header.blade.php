<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border media-manager-card">
            <!-- Toolbar -->
            <div class="card-header bg-white border-bottom p-3">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <!-- Left actions -->
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <div class="btn-group" role="group">
                            <a href="" type="button" class="btn btn-sm btn-outline-secondary media-reload" title="{{ __('lavero-admin-media::media.refresh') }}">
                                <i class="icon-sync-alt"></i>
                            </a>
                            <a type="button" class="btn btn-sm btn-outline-danger file-delete-multiple" title="{{ __('lavero-admin-media::media.delete') }}">
                                <i class="icon-trash"></i>
                            </a>
                        </div>

                        <label class="btn btn-sm btn-primary mb-0 d-inline-flex align-items-center" style="cursor: pointer;">
                            <i class="icon-upload me-1"></i>
                            <span>{{ __('lavero-admin-media::media.upload') }}</span>
                            <form action="{{ $url['upload'] }}" method="post" class="file-upload-form d-none" enctype="multipart/form-data" pjax-container>
                                <input type="file" name="files[]" class="d-none file-upload" multiple>
                                <input type="hidden" name="dir" value="{{ $url['path'] }}" />
                                {{ csrf_field() }}
                            </form>
                        </label>

                        <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#newFolderModal">
                            <i class="icon-folder me-1 text-warning"></i>
                            <span>{{ __('lavero-admin-media::media.new_folder') }}</span>
                        </button>
                    </div>

                    <!-- Right actions: view toggle & path goto -->
                    <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
                        <div class="btn-group" role="group">
                            <a href="{{ route('media-index', ['path' => $url['path'], 'view' => 'table', 'select' => $select, 'fn' => $fn]) }}" class="btn btn-sm @if($view == 'table') btn-primary @else btn-outline-secondary @endif" title="{{ __('lavero-admin-media::media.table_view') }}">
                                <i class="icon-list"></i>
                            </a>
                            <a href="{{ route('media-index', ['path' => $url['path'], 'view' => 'list', 'select' => $select, 'fn' => $fn]) }}" class="btn btn-sm @if($view == 'list') btn-primary @else btn-outline-secondary @endif" title="{{ __('lavero-admin-media::media.list_view') }}">
                                <i class="icon-th"></i>
                            </a>
                        </div>

                        <form action="{{ $url['index'] ?? route('media-index') }}" method="get" class="d-flex m-0 goto-url-form" pjax-container>
                            <input type="hidden" name="view" value="{{ $view }}">
                            <input type="hidden" name="select" value="{{ $select }}">
                            <input type="hidden" name="fn" value="{{ $fn }}">
                            <div class="input-group input-group-sm goto-url" style="width: 220px; max-width: 100%;">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="icon-folder-open"></i></span>
                                <input type="text" name="path" class="form-control border-start-0" value="{{ '/'.trim($url['path'], '/') }}" placeholder="{{ __('lavero-admin-media::media.path') }}">
                                <button type="submit" class="btn btn-outline-secondary"><i class="icon-arrow-right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Path Breadcrumbs bar -->
            <div class="bg-light border-bottom px-3 py-2 d-flex align-items-center justify-content-between">
                <nav aria-label="breadcrumb" class="breadcrumb-nav m-0 p-0">
                    <ol class="breadcrumb m-0 p-0 align-items-center" style="font-size: 0.8125rem;">
                        <li class="breadcrumb-item">
                            <a href="{{ route('media-index') }}?select={{$select}}&fn={{$fn}}&view={{$view}}" class="text-decoration-none d-inline-flex align-items-center text-secondary">
                                <i class="icon-home me-1 text-primary"></i>
                                <span>{{ __('lavero-admin-media::media.root') }}</span>
                            </a>
                        </li>
                        @foreach($nav as $item)
                            @if($loop->last)
                                <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                                    {{ $item['name'] }}
                                </li>
                            @else
                                <li class="breadcrumb-item">
                                    <a href="{{ $item['url'] }}&view={{$view}}&select={{$select}}&fn={{$fn}}" class="text-decoration-none text-secondary">
                                        {{ $item['name'] }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
                <div class="text-muted small d-none d-sm-block">
                    {{ count($list) }} {{ __('lavero-admin-media::media.files') }}
                </div>
            </div>

            <div class="card-body p-0">
