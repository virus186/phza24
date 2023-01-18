@extends('backEnd.master')
@section('page-title', app('general_setting')->site_title)
@section('mainContent')
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="box_header common_table_header">
                        <div class="main-title d-md-flex">
                            <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('report.wallet_recharge_history') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="QA_section QA_section_heading_custom check_box_table">
                        <div class="QA_table ">
                            <!-- table-responsive -->
                            <div class="table-responsive">
                                <table id="walletTable" class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{ __('common.sl') }}</th>
                                        <th scope="col">{{ __('common.user') }}</th>
                                        <th scope="col">{{ __('common.email') }}</th>
                                        <th scope="col">{{ __('common.type') }}</th>
                                        <th scope="col">{{ __('common.amount') }}</th>
                                        
                                        <th scope="col">{{ __('common.payment') }} {{ __('common.details') }}</th>
                                        <th scope="col">{{ __('common.txn_id') }}</th>
                                        <th scope="col">{{ __('common.date') }}</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script type="text/javascript">
        (function($){
            "use strict";

            $('#walletTable').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    "ajax": ( {
                        url: "{{ route('report.wallet_recharge_history_data') }}"
                    }),
                    "initComplete":function(json){

                    },
                    columns: [
                                { data: 'DT_RowIndex', name: 'id' },
                                { data: 'user', name: 'user' },
                                { data: 'email', name: 'email' },
                                { data: 'type', name: 'type' },
                                { data: 'amount', name: 'amount' },
                                { data: 'payment_details', name: 'payment_details' },
                                { data: 'txn_id', name: 'txn_id' },
                                { data: 'date', name: 'date' },
                            ],

                    bLengthChange: false,
                    "order": [[ 7, "asc" ]],
                    "bDestroy": true,
                    language: {
                        search: "<i class='ti-search'></i>",
                        searchPlaceholder: trans('common.quick_search'),
                        paginate: {
                            next: "<i class='ti-arrow-right'></i>",
                            previous: "<i class='ti-arrow-left'></i>"
                        }
                    },
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'copyHtml5',
                            text: '<i class="fa fa-files-o"></i>',
                            title: $("#header_title").text(),
                            titleAttr: 'Copy',
                            exportOptions: {
                                columns: ':visible',
                                columns: ':not(:last-child)',
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="fa fa-file-excel-o"></i>',
                            titleAttr: 'Excel',
                            title: $("#header_title").text(),
                            margin: [10, 10, 10, 0],
                            exportOptions: {
                                columns: ':visible',
                                columns: ':not(:last-child)',
                            },

                        },
                        {
                            extend: 'csvHtml5',
                            text: '<i class="fa fa-file-text-o"></i>',
                            titleAttr: 'CSV',
                            exportOptions: {
                                columns: ':visible',
                                columns: ':not(:last-child)',
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="fa fa-file-pdf-o"></i>',
                            title: $("#header_title").text(),
                            titleAttr: 'PDF',
                            exportOptions: {
                                columns: ':visible',
                                columns: ':not(:last-child)',
                            },
                            pageSize: 'A4',
                            margin: [0, 0, 0, 0],
                            alignment: 'center',
                            header: true,

                        },
                        {
                            extend: 'print',
                            text: '<i class="fa fa-print"></i>',
                            titleAttr: 'Print',
                            title: $("#header_title").text(),
                            exportOptions: {
                                columns: ':not(:last-child)',
                            }
                        },
                        {
                            extend: 'colvis',
                            text: '<i class="fa fa-columns"></i>',
                            postfixButtons: ['colvisRestore']
                        }
                    ],
                    columnDefs: [{
                        visible: false
                    }],
                    responsive: true,
                });

        })(jQuery);
    </script>
@endpush
