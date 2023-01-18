@extends('backEnd.master')
@section('mainContent')

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="box_header common_table_header">
                    <div class="main-title d-md-flex">
                        <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('All Uploaded Files') }}</h3>
                    </div>
                    @if(permissionCheck('media-manager.new-upload'))
                    <ul class="d-flex">
                        <li><a class="primary-btn radius_30px mr-10 fix-gr-bg float-right" href="{{url('/media-manager/new-upload')}}"></i>{{__('Uploads new files')}}</a></li>
                    </ul>
                    @endif
                </div>
            </div>
            <div class="col-lg-12">
                <div class="media_box box_shadow_white p-0">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="" method="GET" class="d-flex align-items-center gap_20 flex-wrap">
                                    <h4 class="flex-fill m-0">{{ __('All Files') }}</h4>
                                    <div class="media_header_inputs flex-fill">
                                        <div class="primary_input ">
                                                <select class="primary_select style2" name="sort" id="status">
                                                    <option value="newest" @if(isset($_GET['sort']) && $_GET['sort'] == 'newest') selected @endif>{{ __('Sort by newest') }}</option>
                                                    <option value="oldest" @if(isset($_GET['sort']) && $_GET['sort'] == 'oldest') selected @endif>{{ __('Sort by oldest') }}</option>
                                                    <option value="smallest" @if(isset($_GET['sort']) && $_GET['sort'] == 'smallest') selected @endif>{{ __('Sort by smallest') }}</option>
                                                    <option value="bigest" @if(isset($_GET['sort']) && $_GET['sort'] == 'bigest') selected @endif>{{ __('Sort by bigest') }}</option>
                                                </select>
                                            </div>
                                            <div class="primary_input">
                                                <input class="primary_input_field2 input_height50 radius_30" name="search" placeholder="Search" type="text" value="{{isset($_GET['search'])?$_GET['search']:''}}">
                                            </div>
                                    </div>
                                    <button class="primary-btn semi_large2 fix-gr-bg cusrve_30px w_160"><i class="ti-check"></i>{{__("Search")}} </button>
                            </form>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="amazcart_file_wrapper">
                            @foreach ($files as $file)
                                    <div class="amazcart_file_box">
                                        <div class="amazcart_file_body">
                                            <div class="img-box position-relative">
                                                <div class="gallery_action position-absolute">
                                                    <a data-value="{{$file}}" class="details_info" data-toggle="tooltip" title="Info"><i class="ti-info-alt"></i></a>
                                                    <a data-id="{{$file->id}}" class="copy_id" data-toggle="tooltip" title="{{ __('product.Copy ID') }}"><i class="ti-pin"></i></a>
                                                    <a href="{{$file->storage=='local'?showImage($file->file_name):$file->file_name}}" download data-toggle="tooltip" title="Download"><i class="ti-download"></i></a>
                                                    <a href="{{$file->storage=='local'?showImage($file->file_name):$file->file_name}}" class="copy_link" data-toggle="tooltip" title="Copy Link"><i class="ti-layers"></i></a>
                                                    @if(permissionCheck('media-manager.delete_media_file'))
                                                        <a data-url="{{route('media-manager.delete_media_file', $file->id)}}" class="delete_file" data-toggle="tooltip" title="Delete"><i class="ti-trash"></i></a>
                                                    @endif
                                                </div>
                                                <img  src="{{$file->storage=='local'?showImage($file->file_name):$file->file_name}}" alt="">
                                            </div>
                                            <div class="amazcart_file_content-box">
                                                <div class="file-content-wrapper">
                                                    <h5>{{$file->orginal_name}}</h5>
                                                    <p>{{$file->size}} kb</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                {{ $files->onEachSide(1)->links('backEnd.media_manager.paginate') }}
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        @include('backEnd.partials.delete_modal')
        @include('backEnd.media_manager.partials._info_modal')
    </div>
</section>

@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $(document).on('click', '.delete_file', function(event){
                event.preventDefault();
                let url = $(this).data('url');
                confirm_modal(url);
            });
            $(document).on('click','#delete_link', function(){
                $('#pre-loader').removeClass('d-none');
                $('#confirm-delete').modal('hide');
            });
            $(document).on('click', '.details_info', function(event){
                event.preventDefault();
                let data = $(this).data('value');
                if(data){
                    $('#show_name').text(data.orginal_name);
                    $('#show_extension').text(data.extension);
                    $('#show_size').text(data.size + ' kb');
                    $('#show_storage').text(data.storage);
                    $('#single_image_div').removeClass('d-none');
                    var imag= data.file_name;
                    if(data.storage == 'local'){
                        $('#show_path').text('{{url('')}}'+'/public/'+data.file_name);
                        var image_path = "{{asset(asset_path(''))}}" + "/"+imag;
                        document.getElementById('view_image').src=image_path;
                    }else{
                        $('#show_path').text(data.file_name);
                        document.getElementById('view_image').src=imag;
                    }
                    $('#item_show').modal('show');
                }
            });

            $('.copy_link').click(function (e) {
                e.preventDefault();
                var copyText = $(this).attr('href');

                document.addEventListener('copy', function(e) {
                    e.clipboardData.setData('text/plain', copyText);
                        e.preventDefault();
                    }, true);

                    document.execCommand('copy');
                    toastr.info('Link copied to clipboard!'); 
                });

            });
    </script>
@endpush