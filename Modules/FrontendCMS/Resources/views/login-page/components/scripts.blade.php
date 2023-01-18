@push('scripts')
    <script>
        (function($){
            "use strict";
            $(document).ready(function() {

                $(document).on('change', '#document_file_1', function(){
                    getFileName($(this).val(),'#placeholderFileOneName1');
                    imageChangeWithFile($(this)[0],'#blogImgShow1');
                });

                $(document).on('change', '#document_file_2', function(){
                    getFileName($(this).val(),'#placeholderFileOneName2');
                    imageChangeWithFile($(this)[0],'#blogImgShow2');
                });

                $(document).on('change', '#document_file_3', function(){
                    getFileName($(this).val(),'#placeholderFileOneName3');
                    imageChangeWithFile($(this)[0],'#blogImgShow3');
                });
                $(document).on('change', '#document_file_4', function(){
                    getFileName($(this).val(),'#placeholderFileOneName4');
                    imageChangeWithFile($(this)[0],'#blogImgShow4');
                });

                $(document).on('click', '.active_section_class', function(event){
                    let id = $(this).data('id');
                    let url = "/frontendcms/login_page_tab/" + id;
                    $.ajax({
                            url: url,
                            type: "GET",
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(response) {

                            },
                            error: function(response) {

                        }
                    });
                });

            });
        })(jQuery);

    </script>

@endpush
