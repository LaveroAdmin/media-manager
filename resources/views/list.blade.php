@include("lavero-admin-media::header")

<div class="p-3">
    @if (empty($list) || count($list) === 0)
        <div class="text-center py-5 text-muted">
            <i class="icon-folder-open icon-3x mb-2 text-secondary opacity-50"></i>
            <p class="mb-0">{{ __('lavero-admin-media::media.empty_folder') }}</p>
        </div>
    @else
        <ul class="files list clearfix m-0 p-0 d-flex flex-wrap gap-3 list-unstyled">
            @foreach($list as $item)
            <li class="file-card border rounded-3 position-relative bg-white shadow-sm" style="width: 140px;">
                <span class="file-select position-absolute top-0 start-0 m-2 z-2">
                    <input type="checkbox" class="form-check-input" value="{{ $item['name'] }}"/>
                </span>

                <div class="file-preview-box d-flex align-items-center justify-content-center p-3" style="height: 100px;">
                    {!! $item['preview'] !!}
                </div>

                <div class="file-info text-center p-2 border-top bg-light rounded-bottom-3 position-relative">
                    <a @if(!$item['isDir'])target="_blank"@endif href="{{ $item['link'] }}" class="file-name text-decoration-none text-dark fw-medium d-block text-truncate small mb-1" title="{{ $item['name'] }}">
                        {{ basename($item['name']) }}
                    </a>
                    <span class="file-size text-muted" style="font-size: 0.725rem;">
                        {{ $item['size'] ?: '&nbsp;' }}
                    </span>

                    @if (!empty($select))
                        <div class="mt-2">
                            @if ($item['isDir'])
                                <span class="btn btn-xs btn-light invisible">&nbsp;</span>
                            @else
                                <a href="javascript:{{$fn}}('{{ $item['url'] }}','{{ $item['name'] }}');@if ($close) window.close();@endif" class="btn btn-xs btn-primary w-100">{{ trans('admin.select') }}</a>
                            @endif
                        </div>
                    @else
                        <div class="dropdown position-absolute bottom-0 end-0 m-1">
                            <button type="button" class="btn btn-xs btn-light border dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('lavero-admin-media::media.toggle_dropdown') }}">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" role="menu">
                                <li><a href="#" class="dropdown-item file-rename" data-bs-toggle="modal" data-bs-target="#moveModal" data-name="{{ $item['name'] }}"><i class="icon-edit me-1"></i> {{ __('lavero-admin-media::media.rename_and_move') }}</a></li>
                                <li><a href="#" class="dropdown-item file-delete text-danger" data-path="{{ $item['name'] }}"><i class="icon-trash me-1"></i> {{ __('lavero-admin-media::media.delete') }}</a></li>
                                @unless($item['isDir'])
                                <li><a target="_blank" class="dropdown-item" href="{{ $item['download'] }}"><i class="icon-download me-1"></i> {{ __('lavero-admin-media::media.download') }}</a></li>
                                @endunless
                                <li class="dropdown-divider"></li>
                                <li><a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#urlModal" data-url="{{ $item['url'] }}"><i class="icon-link me-1"></i> {{ __('lavero-admin-media::media.url') }}</a></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </li>
            @endforeach
        </ul>
    @endif
</div>

@include("lavero-admin-media::footer")
@include("lavero-admin-media::_shared")
