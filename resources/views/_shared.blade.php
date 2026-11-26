<style>
    .media-manager-card {
        border-radius: 10px;
        background: #fff;
        overflow: hidden;
    }

    .file-card {
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
    }
    .file-card:hover {
        border-color: #cbd5e1 !important;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08) !important;
        transform: translateY(-2px);
    }

    .file-card .dropdown-menu {
        z-index: 1050 !important;
        min-width: 180px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.08);
        padding: 0.35rem;
    }

    .file-preview-cell .file-icon {
        font-size: 20px;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .file-preview-cell .file-icon.has-img img {
        max-width: 28px;
        max-height: 28px;
        object-fit: cover;
        border-radius: 4px;
    }

    .file-preview-box .file-icon {
        font-size: 48px;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .file-preview-box .file-icon.has-img img {
        max-width: 80px;
        max-height: 80px;
        object-fit: cover;
        border-radius: 6px;
    }

    .table .action-buttons {
        opacity: 0.4;
        transition: opacity 0.15s ease;
    }
    .table tr:hover .action-buttons {
        opacity: 1;
    }

    .table th {
        font-weight: 600;
        letter-spacing: 0.03em;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    .table td {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }

    [data-bs-theme="dark"] .media-manager-card,
    body.dark-mode .media-manager-card {
        background: #1e293b;
        border-color: #334155 !important;
    }
    [data-bs-theme="dark"] .media-manager-card .card-header,
    body.dark-mode .media-manager-card .card-header {
        background: #1e293b !important;
        border-color: #334155 !important;
    }
    [data-bs-theme="dark"] .media-manager-card .bg-light,
    body.dark-mode .media-manager-card .bg-light {
        background: #0f172a !important;
        border-color: #334155 !important;
    }
    [data-bs-theme="dark"] .file-card,
    body.dark-mode .file-card {
        background: #1e293b !important;
        border-color: #334155 !important;
    }
</style>

<script data-exec-on-popstate>
    (function () {
        function defaultMediaMangerResponse(result){
            admin.ajax.reload();
            if (typeof result.data === 'object') {
                let data = result.data;
                if (data.status) {
                    admin.toastr.success(data.message);
                } else {
                    admin.toastr.error(data.message);
                }
            }
        }

        document.querySelectorAll('.file-delete').forEach(function(el) {
            el.addEventListener("click", function () {
                let path = this.dataset.path;

                Swal.fire({
                    title: "{{ __('admin.delete_confirm') }}",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "{{ __('lavero-admin-media::media.delete') }}",
                    cancelButtonText: "{{ __('admin.cancel') }}",
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return new Promise(function(resolve) {
                            let url = '{{ $url['delete'] }}';
                            let obj = {
                                method: 'delete',
                                data: {'files': [path]},
                            };
                            admin.ajax.request(url, obj, resolve);
                        });
                    }
                }).then(function(result){
                    if (result.isConfirmed || result.value) {
                        defaultMediaMangerResponse(result.value);
                    }
                });
            });
        });

        var moveModalEl = document.getElementById('moveModal');
        if (moveModalEl) {
            moveModalEl.addEventListener('show.bs.modal', function (event) {
                let button = event.relatedTarget;
                let name = button.dataset.name;
                moveModalEl.querySelector('[name=path]').value = name;
                moveModalEl.querySelector('[name=new]').value = name;
            });
        }

        var urlModalEl = document.getElementById('urlModal');
        if (urlModalEl) {
            urlModalEl.addEventListener('show.bs.modal', function (event) {
                let button = event.relatedTarget;
                let url = button.dataset.url;
                urlModalEl.querySelector('input').value = url;
            });
        }

        var fileMoveForm = document.getElementById('file-move');
        if (fileMoveForm) {
            fileMoveForm.addEventListener('submit', function (event) {
                event.preventDefault();
                let form = event.target;
                let path = form.querySelector('[name=path]').value;
                let name = form.querySelector('[name=new]').value;

                let url = '{{ $url['move'] }}';
                let obj = {
                    method: 'put',
                    data: {
                        'path': path,
                        'new': name,
                    },
                };
                admin.ajax.request(url, obj, function(result){
                    defaultMediaMangerResponse(result);
                });

                var modalInstance = bootstrap.Modal.getInstance(document.getElementById('moveModal'));
                if (modalInstance) modalInstance.hide();
            });
        }

        var fileUploadInput = document.querySelector('.file-upload');
        if (fileUploadInput) {
            fileUploadInput.addEventListener('change', function () {
                var form = document.querySelector('.file-upload-form');
                if (form) form.submit();
            });
        }

        var newFolderForm = document.getElementById('new-folder');
        if (newFolderForm) {
            newFolderForm.addEventListener('submit', function (event) {
                event.preventDefault();
                let formData = new FormData(event.target);
                let url = '{{ $url['new-folder'] }}';
                let obj = {
                    method: 'post',
                    data: formData,
                    async: false
                };
                admin.ajax.request(url, obj, function(result){
                    defaultMediaMangerResponse(result);
                });
                var modalInstance = bootstrap.Modal.getInstance(document.getElementById('newFolderModal'));
                if (modalInstance) modalInstance.hide();
            });
        }

        var reloadBtn = document.querySelector('.media-reload');
        if (reloadBtn) {
            reloadBtn.addEventListener('click', function (e) {
                e.preventDefault();
                admin.ajax.reload();
            });
        }

        var gotoForm = document.querySelector('.goto-url-form');
        if (gotoForm) {
            gotoForm.addEventListener('submit', function (e) {
                e.preventDefault();
                let pathInput = this.querySelector('input[name=path]');
                let path = pathInput ? pathInput.value : '/';
                admin.ajax.navigate('{{ $url['index'] }}?path=' + encodeURIComponent(path) + '&view={{ $view }}&select={{ $select }}&fn={{ $fn }}');
            });
        }

        var deleteMultipleBtn = document.querySelector('.file-delete-multiple');
        if (deleteMultipleBtn) {
            deleteMultipleBtn.addEventListener('click', function () {
                let files = [];
                document.querySelectorAll(".file-select input:checked").forEach(function(el) {
                    files.push(el.value);
                });

                if (!files.length) {
                    return;
                }

                Swal.fire({
                    title: "{{ __('admin.delete_confirm') }}",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "{{ __('lavero-admin-media::media.delete') }}",
                    cancelButtonText: "{{ __('admin.cancel') }}",
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return new Promise(function(resolve) {
                            let url = "{{ $url['delete'] }}";
                            let obj = {
                                method: 'delete',
                                data: {'files': files},
                            };
                            admin.ajax.request(url, obj, resolve);
                        });
                    }
                }).then(function(result){
                    if (result.isConfirmed || result.value) {
                        defaultMediaMangerResponse(result.value);
                    }
                });
            });
        }

        // 1-click Copy URL
        document.querySelectorAll('.btn-copy-url').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var url = this.getAttribute('data-url');
                if (url && navigator.clipboard) {
                    navigator.clipboard.writeText(url).then(function() {
                        admin.toastr.success("{{ __('lavero-admin-media::media.url_copied') }}");
                    });
                }
            });
        });

        // Drag-and-drop multi-upload
        var dropzone = document.getElementById('media-dropzone');
        if (dropzone) {
            dropzone.addEventListener('click', function() {
                var input = document.querySelector('.file-upload');
                if (input) input.click();
            });
            ['dragenter', 'dragover'].forEach(function(evt) {
                dropzone.addEventListener(evt, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('bg-primary', 'bg-opacity-10', 'text-primary');
                });
            });
            ['dragleave', 'drop'].forEach(function(evt) {
                dropzone.addEventListener(evt, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('bg-primary', 'bg-opacity-10', 'text-primary');
                });
            });
            dropzone.addEventListener('drop', function(e) {
                var files = e.dataTransfer ? e.dataTransfer.files : null;
                if (!files || !files.length) return;
                var fd = new FormData();
                fd.append('dir', '{{ $url['path'] }}');
                fd.append('_token', '{{ csrf_token() }}');
                for (var i = 0; i < files.length; i++) {
                    fd.append('files[]', files[i]);
                }
                fetch('{{ $url['upload'] }}', { method: 'POST', body: fd })
                    .then(function() { admin.ajax.reload(); })
                    .catch(function() { admin.toastr.error("{{ __('lavero-admin-media::media.error') }}"); });
            });
        }

        // Process Image (Resize / WebP)
        var processModal = document.getElementById('processImageModal');
        if (processModal) {
            processModal.addEventListener('show.bs.modal', function(event) {
                var trigger = event.relatedTarget;
                var pathInput = document.getElementById('processImagePath');
                if (trigger && pathInput) {
                    pathInput.value = trigger.getAttribute('data-path') || '';
                }
            });
        }
        var processForm = document.getElementById('process-image-form');
        if (processForm) {
            processForm.addEventListener('submit', function(e) {
                e.preventDefault();
                var fd = new FormData(processForm);
                admin.ajax.post('{{ route("media-process-image") }}', {
                    path: fd.get('path'),
                    width: fd.get('width') || 0,
                    height: fd.get('height') || 0,
                    webp: document.getElementById('processImageWebp').checked ? 1 : 0,
                }, function(result) {
                    var modalInstance = bootstrap.Modal.getInstance(processModal);
                    if (modalInstance) modalInstance.hide();
                    defaultMediaMangerResponse(result);
                });
            });
        }
    })();
</script>
