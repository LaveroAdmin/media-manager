@include("lavero-admin-media::header")

@if (!empty($list) && count($list) > 0)
    <div class="table-responsive mb-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted small text-uppercase">
                <tr>
                    <th style="width: 44px;" class="ps-3">
                        <span class="file-select-all">
                            <input type="checkbox" class="form-check-input" value=""/>
                        </span>
                    </th>
                    <th>{{ trans('admin.name') }}</th>
                    @if (!empty($select))
                        <th style="width: 100px;">{{ trans('admin.select') }}</th>
                    @else
                        <th style="width: 160px;" class="text-end pe-3"></th>
                    @endif
                    <th style="width: 190px;" class="d-none d-md-table-cell">{{ trans('admin.time') }}</th>
                    <th style="width: 100px;" class="text-end pe-3">{{ trans('admin.size') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($list as $item)
                <tr>
                    <td class="ps-3">
                        <span class="file-select">
                            <input type="checkbox" class="form-check-input" value="{{ $item['name'] }}"/>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="file-preview-cell flex-shrink-0 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                {!! $item['preview'] !!}
                            </div>
                            <a @if(!$item['isDir'])target="_blank"@endif href="{{ $item['link'] }}" class="file-name text-decoration-none text-dark fw-medium text-truncate" title="{{ $item['name'] }}" style="max-width: 320px;">
                                {{ basename($item['name']) }}
                            </a>
                        </div>
                    </td>

                    @if (!empty($select))
                        <td>
                            @if ($item['isDir'])
                                <span class="btn btn-sm btn-light invisible">&nbsp;</span>
                            @else
                                <a href="javascript:{{$fn}}('{{ $item['url'] }}','{{ $item['name'] }}');@if ($close) window.close();@endif" class="btn btn-sm btn-primary">{{ trans('admin.select') }}</a>
                            @endif
                        </td>
                    @else
                        <td class="action-row text-end pe-3">
                            <div class="btn-group btn-group-sm action-buttons">
                                <a class="btn btn-sm btn-light file-rename" data-bs-toggle="modal" data-bs-target="#moveModal" data-name="{{ $item['name'] }}" title="{{ __('lavero-admin-media::media.rename_and_move') }}"><i class="icon-edit"></i></a>
                                <a class="btn btn-sm btn-light file-delete text-danger" data-path="{{ $item['name'] }}" title="{{ __('lavero-admin-media::media.delete') }}"><i class="icon-trash"></i></a>
                                @unless($item['isDir'])
                                    <a target="_blank" href="{{ $item['download'] }}" class="btn btn-sm btn-light" title="{{ __('lavero-admin-media::media.download') }}"><i class="icon-download"></i></a>
                                @endunless
                                <a class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#urlModal" data-url="{{ $item['url'] }}" title="{{ __('lavero-admin-media::media.url') }}"><i class="icon-link"></i></a>
                            </div>
                        </td>
                    @endif

                    <td class="text-muted small d-none d-md-table-cell">{{ $item['time'] }}</td>
                    <td class="text-muted small text-end pe-3">{{ $item['size'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-5 text-muted">
        <i class="icon-folder-open icon-3x mb-2 text-secondary opacity-50"></i>
        <p class="mb-0">{{ __('lavero-admin-media::media.empty_folder') }}</p>
    </div>
@endif

@include("lavero-admin-media::footer")
@include("lavero-admin-media::_shared")
