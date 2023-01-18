@extends('backEnd.master')

@section('styles')
<style>
    .UppyDragDrop{
        height: 500px;
    }
    .uppy-Dashboard-inner{
        width: 100%!important;
        height: 500px!important;
    }
    .uppy-Dashboard-AddFiles-info{
        display: none!important;
    }
</style>
@endsection

@section('mainContent')
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="box_header common_table_header">
                    <div class="main-title d-md-flex">
                        <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('All Uploaded Files') }}</h3>
                    </div>
                    <ul class="d-flex">
                        <li><a class="primary-btn radius_30px mr-10 fix-gr-bg float-right" href="{{url('/media-manager/upload-files')}}"></i>{{__('Back to files')}}</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card" >
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="mt-15 mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('Drag & drop files here') }}</h4>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="UppyDragDrop"></div>
                                <div class="for-ProgressBar"></div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>
@endsection

@push('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            'use strict';
            var uppy = new Uppy.Core({
                debug: true,
                autoProceed: true,
                restrictions: {
                    maxFileSize: 2000000,
                    maxNumberOfFiles: 10,
                    minNumberOfFiles: 1,
                    allowedFileTypes: ['image/*']
                }
            });
            uppy.use(Uppy.Dashboard, {
                inline: true,
                target: '.UppyDragDrop'
            });
            uppy.use(Uppy.ProgressBar, {
                target: '.for-ProgressBar',
                hideAfterFinish: true
            });
            let baseUrl = $('#url').val();
            let store_url = baseUrl + '/media-manager/new-upload-store';
            let token = '{{csrf_token()}}';
            uppy.use(Uppy.XHRUpload, {
                endpoint: store_url,
                formData: true,
                fieldName: 'file',
                headers: {
                    'X-CSRF-TOKEN': token,
                },
            });

            uppy.on('upload-success', function (response) {

            });
            
            window.uppy = uppy;
        });
    </script>
@endpush