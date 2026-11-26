            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col-12 -->
</div>
<!-- /.row -->

<div class="modal fade" id="moveModal" tabindex="-1" role="dialog" aria-labelledby="moveModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow border-0" style="border-radius: 12px;">
            <div class="modal-header border-bottom py-3 px-4 bg-light">
                <h5 class="modal-title fw-bold text-dark mb-0" id="moveModalLabel">{{ __('lavero-admin-media::media.rename_and_move') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('admin.close') }}"></button>
            </div>
            <form id="file-move">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="moveModel_new" class="form-label fw-bold">{{ __('lavero-admin-media::media.path') }}:</label>
                        <input type="text" class="form-control" id="moveModel_new" name="new" required />
                    </div>
                    <input type="hidden" id="moveModel_path" name="path"/>
                </div>
                <div class="modal-footer border-top py-2 px-4 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">{{ __('admin.close') }}</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">{{ __('admin.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="processImageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow border-0" style="border-radius: 12px;">
            <div class="modal-header border-bottom py-3 px-4 bg-light">
                <h5 class="modal-title fw-bold text-dark mb-0">{{ __('lavero-admin-media::media.process_image') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('admin.close') }}"></button>
            </div>
            <form id="process-image-form">
                <div class="modal-body p-4">
                    <input type="hidden" name="path" id="processImagePath">
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">{{ __('lavero-admin-media::media.max_width') }}</label>
                            <input type="number" min="0" class="form-control" name="width" placeholder="1920">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">{{ __('lavero-admin-media::media.max_height') }}</label>
                            <input type="number" min="0" class="form-control" name="height" placeholder="1080">
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="webp" value="1" id="processImageWebp" checked>
                        <label class="form-check-label" for="processImageWebp">{{ __('lavero-admin-media::media.convert_webp') }}</label>
                    </div>
                </div>
                <div class="modal-footer border-top py-2 px-4 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">{{ __('admin.close') }}</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">{{ __('admin.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="urlModal" tabindex="-1" role="dialog" aria-labelledby="urlModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow border-0" style="border-radius: 12px;">
            <div class="modal-header border-bottom py-3 px-4 bg-light">
                <h5 class="modal-title fw-bold text-dark mb-0" id="urlModalLabel">{{ __('lavero-admin-media::media.url') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('admin.close') }}"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-2">
                    <div class="input-group">
                        <input type="text" class="form-control" id="urlModalInput" readonly />
                        <button type="button" class="btn btn-outline-secondary" onclick="navigator.clipboard && navigator.clipboard.writeText(document.getElementById('urlModalInput').value)"><i class="icon-copy"></i></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top py-2 px-4 bg-light">
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">{{ __('admin.close') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newFolderModal" tabindex="-1" role="dialog" aria-labelledby="newFolderModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow border-0" style="border-radius: 12px;">
            <div class="modal-header border-bottom py-3 px-4 bg-light">
                <h5 class="modal-title fw-bold text-dark mb-0" id="newFolderModalLabel">{{ __('lavero-admin-media::media.new_folder') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('admin.close') }}"></button>
            </div>
            <form id="new-folder">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="new_folder_name" class="form-label fw-bold">{{ __('lavero-admin-media::media.folder_name') }}:</label>
                        <input type="text" class="form-control" id="new_folder_name" name="name" placeholder="{{ __('lavero-admin-media::media.folder_name') }}" required />
                    </div>
                    <input type="hidden" name="dir" value="{{ $url['path'] }}"/>
                    {{ csrf_field() }}
                </div>
                <div class="modal-footer border-top py-2 px-4 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">{{ __('admin.close') }}</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">{{ __('admin.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="processImageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow border-0" style="border-radius: 12px;">
            <div class="modal-header border-bottom py-3 px-4 bg-light">
                <h5 class="modal-title fw-bold text-dark mb-0">{{ __('lavero-admin-media::media.process_image') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('admin.close') }}"></button>
            </div>
            <form id="process-image-form">
                <div class="modal-body p-4">
                    <input type="hidden" name="path" id="processImagePath">
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">{{ __('lavero-admin-media::media.max_width') }}</label>
                            <input type="number" min="0" class="form-control" name="width" placeholder="1920">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">{{ __('lavero-admin-media::media.max_height') }}</label>
                            <input type="number" min="0" class="form-control" name="height" placeholder="1080">
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="webp" value="1" id="processImageWebp" checked>
                        <label class="form-check-label" for="processImageWebp">{{ __('lavero-admin-media::media.convert_webp') }}</label>
                    </div>
                </div>
                <div class="modal-footer border-top py-2 px-4 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">{{ __('admin.close') }}</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">{{ __('admin.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
