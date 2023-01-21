<link rel="stylesheet" href="{{ asset('vendor/core/core/media/libraries/dropzone/dropzone.css') }}">
<script src="{{ asset('vendor/core/core/media/libraries/dropzone/dropzone.js') }}"></script>
<style>
    .dropzone {
        border-radius: 5px;
        border: 1px dashed rgb(0, 135, 247);
    }
    .dropzone .dz-preview:not(.dz-processing) .dz-progress {
        display: none;
    }

    .dropzone .dz-message {
        margin : 50px 0;
    }
</style>
<script>
    'use strict';
    Dropzone.autoDiscover = false;

    $(document).ready(function () {
        var uploadedImages = [];

        var dropzone = new Dropzone('#{{ $id }}-upload', {
            previewTemplate: document.querySelector('#preview-template').innerHTML,
            parallelUploads: 1,
            thumbnailHeight: 120,
            thumbnailWidth: 120,
            addRemoveLinks: true,
            filesizeBase: 1000,
            uploadMultiple: {{ setting('media_chunk_enabled') == '1' ? 'false' : 'true' }},
            chunking: {{ setting('media_chunk_enabled') == '1' ? 'true' : 'false' }},
            forceChunking: true, // forces chunking when file.size < chunkSize
            parallelChunkUploads: false, // allows chunks to be uploaded in parallel (this is independent of the parallelUploads option)
            chunkSize: {{ setting('media_chunk_size', config('core.media.media.chunk.chunk_size')) }}, // chunk size 1,000,000 bytes (~1MB)
            retryChunks: true, // retry chunks on failure
            retryChunksLimit: 3, // retry maximum of 3 times (default is 3)
            timeout: 0, // MB,
            maxFilesize: {{ setting('media_chunk_enabled') == '1' ? setting('media_chunk_size', config('core.media.media.chunk.chunk_size')) : 10 }}, // MB
            maxFiles: null, // max files upload,
            paramName: 'file',
            acceptedFiles: 'image/*',
            url: '{{ route('marketplace.vendor.upload') }}',
            sending: function(file, xhr, formData) {
                formData.append('_token', '{{ csrf_token() }}');
            },
            thumbnail: function(file, dataUrl) {
                if (file.previewElement) {
                    file.previewElement.classList.remove('dz-file-preview');
                    var images = file.previewElement.querySelectorAll('[data-dz-thumbnail]');
                    for (var i = 0; i < images.length; i++) {
                        var thumbnailElement = images[i];
                        thumbnailElement.alt = file.name;
                        thumbnailElement.src = dataUrl;
                    }
                    setTimeout(function() { file.previewElement.classList.add('dz-image-preview'); }, 1);
                }
            },
            success: function (file, response) {
                if (response.error) {
                    Botble.showError(response.message);
                } else {
                    if ({{ setting('media_chunk_enabled') == '1' ? 'true' : 'false' }}) {
                        response = JSON.parse(file.xhr.response);
                    }
                    uploadedImages.push(response.data.url);
                    $('input[name="{{ $name }}"]').val(JSON.stringify(uploadedImages));
                }
            },
            removedfile: function(file) {
                var x = confirm('Do you want to delete this image?');
                if (!x)  {
                    return false;
                }
                var i = $(file.previewElement).index() - 1;
                dropzone.options.maxFiles = dropzone.options.maxFiles + 1;
                uploadedImages.splice(i, 1);
                $('input[name="{{ $name }}"]').val(JSON.stringify(uploadedImages));
                $('.dz-message.needsclick').hide();
                if (uploadedImages.length === 0) {
                    $('.dz-message.needsclick').show();
                }

                var _ref;
                return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
            }
        });

        var files = [];
        @foreach($values as $item)
        uploadedImages.push('{{ $item }}');
        files.push({name: '{{ File::name($item) }}', size: '{{ Storage::exists($item) ? Storage::size($item) : 0 }}', url: '{{ $item }}', full_url: '{{ RvMedia::getImageUrl($item, 'thumb') }}'});
        @endforeach

        $.each(files, function(key, file) {
            dropzone.options.addedfile.call(dropzone, file);
            dropzone.options.thumbnail.call(dropzone, file, file.full_url);
        });
    });
</script>
