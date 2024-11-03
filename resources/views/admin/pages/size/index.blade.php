@extends('admin.layouts.base')

@push('styles-lib')
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/dataTables.bootstrap4.min.css') }}" />
@endpush

@push('styles')
    <style>
        .dropdown-item.text-red {
            color: rgba(255, 0, 0, 0.7);
        }

        .dropdown-item.text-red:hover {
            color: rgba(255, 0, 0, 1);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">{{ __('admin.sidebar.size') }}</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">{{ __('admin.common.manage') }}</li>
                        <li class="breadcrumb-item active"><a
                                href="{{ route('admin.size.index') }}">{{ __('admin.sidebar.size') }}</a></li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header justify-content-between align-items-center d-flex">
                        <div>
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-align-justify"></i>
                            </button>
                            <div class="dropdown-menu p-0" style="">
                                <a class="dropdown-item" href="javascript:void(0)" id="addSize" data-toggle="modal"
                                    data-target="#addSizeModel">
                                    <i class="far fa-plus-square"></i> {{ __('admin.common.add') }}</a>
                                <a class="dropdown-item" id="restoreAll" href="javascript:void(0)" style="display: none">
                                    <i class="fab fa-cloudversify"></i> {{ __('admin.common.restore_all') }}</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-red" id="deleteMultiple" href="javascript:void(0)">
                                    <i class="fas fa-trash-alt"></i> {{ __('admin.common.delete_multiple_temps') }}</a>
                                <a class="dropdown-item text-red" id="forceDeleteMultiple" href="javascript:void(0)"
                                    style="display: none">
                                    <i class="fas fa-trash-alt"></i> {{ __('admin.common.delete_many_permanently') }}</a>
                            </div>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" class="custom-control-input" name="include_trashed"
                                id="includeTrashedCheckbox">
                            <label class="custom-control-label" for="includeTrashedCheckbox"
                                style="padding-top: 2px">{{ __('admin.common.trash_log') }}</label>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="size_datatable" class="display table dataTable table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check custom-checkbox">
                                                <input type="checkbox" class="form-check-input" id="checkAll"
                                                    required="">
                                                <label class="form-check-label" for="checkAll"></label>
                                            </div>
                                        </th>
                                        <th>{{ __('admin.size.size_name') }}</th>
                                        <th>{{ __('admin.common.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

    <!-- Add Size Modal -->
    <div class="modal fade" id="addSizeModel" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"Size
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.size.add_size') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="add_size_form">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="add_name">{{ __('admin.size.size_name') }}</label>
                            <input type="text" class="form-control" id="add_name" name="name"
                                placeholder="{{ __('admin.size.enter_size_name') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light"
                            data-dismiss="modal">{{ __('admin.common.close') }}</button>
                        <button type="submit" id="add_size_btn"
                            class="btn btn-primary">{{ __('admin.common.add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Size Modal -->
    <div class="modal fade" id="editSizeModel" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.size.edit_size') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="edit_size_form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="size_id" id="size_id">

                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="update_name">{{ __('admin.size.size_name') }}</label>
                            <input type="text" class="form-control" id="update_name" name="name"
                                placeholder="{{ __('admin.size.enter_size_name') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light"
                            data-dismiss="modal">{{ __('admin.common.close') }}</button>
                        <button type="submit" id="edit_size_btn"
                            class="btn btn-primary">{{ __('admin.common.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts-lib')
    <script src="{{ asset('backend/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
@endpush

@push('scripts')
    <script>
        window.translations = {
            // DataTables
            search: "{{ __('admin.action.search') }}",
            show: "{{ __('admin.action.show') }}",
            record: "{{ __('admin.action.record') }}",
            loading: "{{ __('admin.action.loading') }}",
            to: "{{ __('admin.action.to') }}",
            of: "{{ __('admin.action.of') }}",
            no_data: "{{ __('admin.action.no_data') }}",

            show: "{{ __('admin.action.show') }}",
            hide: "{{ __('admin.action.hide') }}",
            add: "{{ __('admin.action.add') }}",
            adding: "{{ __('admin.action.adding') }}",
            update: "{{ __('admin.action.update') }}",
            updating: "{{ __('admin.action.updating') }}",

            // Notify
            deleted: "{{ __('admin.notify.deleted') }}",
            errors: "{{ __('admin.notify.errors') }}",
            cancelled: "{{ __('admin.notify.cancelled') }}",
            title: "{{ __('admin.notify.title') }}",
            confirmText: "{{ __('admin.notify.confirmText') }}",
            cancelText: "{{ __('admin.notify.cancelText') }}",
            cancel: "{{ __('admin.notify.cancel') }}",
            cancel_all: "{{ __('admin.notify.cancel_all') }}",
            delete_text: "{{ __('admin.notify.delete_text') }}",
            delete_all_text: "{{ __('admin.notify.delete_all_text') }}",
            force_delete_text: "{{ __('admin.notify.force_delete_text') }}",
            force_delete_all_text: "{{ __('admin.notify.force_delete_all_text') }}",
            checkbox_delete: "{{ __('admin.notify.checkbox_delete') }}",
        };
    </script>

    <script>
        $(document).ready(function() {
            // Khởi tạo DataTable
            const $sizeDatatable = $("#size_datatable");

            const initDataTable = () => {
                if ($.fn.DataTable.isDataTable($sizeDatatable)) {
                    $sizeDatatable.DataTable().destroy();
                }

                const dataTable = $sizeDatatable.DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    language: {
                        sSearch: '<span class="fs-14">' + window.translations.search + '</span>',
                        sProcessing: '' + window.translations.loading +
                            ' <i class="fa fa-spinner" style="transition: 2s;"></i>',
                        sLengthMenu: '<span class="fs-14">' + window.translations.show +
                            '</span> <select class="form-control" style="margin: 0 4px;">' +
                            '<option value="10">10</option>' +
                            '<option value="20">20</option>' +
                            '<option value="30">30</option>' +
                            '<option value="40">40</option>' +
                            '<option value="50">50</option>' +
                            '<option value="-1">Tất cả</option>' +
                            '</select> <span class="fs-14">' + window.translations.record + '</span>',
                        sInfo: '' + window.translations.show + ' _START_ ' + window.translations.to +
                            ' _END_ ' + window.translations.of + ' _TOTAL_ ' + window.translations
                            .record + '',
                        sEmptyTable: '' + window.translations.no_data + '',
                        oPaginate: {
                            sNext: '<i class="fa fa-chevron-right"></i>',
                            sPrevious: '<i class="fa fa-chevron-left"></i>',
                        },
                    },
                    ajax: {
                        url: "{{ route('admin.size.index') }}",
                        data: function(d) {
                            d.include_trashed = $('#includeTrashedCheckbox').is(':checked') ? 1 : 0;
                        }
                    },
                    columns: [{
                            data: "checkbox",
                            name: "checkbox",
                            orderable: false,
                            searchable: false,
                            render: function(data, type, full, meta) {
                                return `<div class="form-check custom-checkbox ms-2">
                                    <input type="checkbox" class="form-check-input checkbox_ids" name="ids" required="" value="${full.id}">
                                    <label class="form-check-label"></label>
                                </div>`;
                            },
                        },
                        {
                            data: "name",
                            name: "name"
                        },
                        {
                            data: "action",
                            name: "action",
                            orderable: false,
                            searchable: false
                        },
                    ],
                });

                let shouldReloadData = false;
                const debounceReloadData = debounce(() => {
                    if (shouldReloadData) {
                        dataTable.draw(); // Reload only the data
                        shouldReloadData = false;
                    }
                }, 300); // Adjust the debounce delay as needed

                $('#includeTrashedCheckbox').change(handleCheckboxChange);

                function handleCheckboxChange() {
                    const $checkbox = $(this);
                    const checked = $checkbox.is(":checked");
                    const elementsToShow = checked ? ['#restoreAll', '#forceDeleteMultiple'] : [
                        '#deleteMultiple', '#addSize'
                    ];
                    const elementsToHide = checked ? ['#deleteMultiple', '#addSize'] : ['#restoreAll',
                        '#forceDeleteMultiple'
                    ];

                    elementsToShow.forEach(element => $(element).show());
                    elementsToHide.forEach(element => $(element).hide());

                    shouldReloadData = true;
                    debounceReloadData();
                }
            };

            // Khởi tạo ban đầu
            initDataTable();

            // Xử lý biểu mẫu thêm kích thước
            setupAddHandler("#add_size_form", "#add_size_btn", "#addSizeModel", "{{ route('admin.size.store') }}",
                initDataTable)

            // Nhận dữ liệu được kết xuất trên biểu mẫu chỉnh sửa kích thước
            $(document).on("click", ".editIcon", function(e) {
                e.preventDefault();
                const id = $(this).attr("id");

                $.ajax({
                    url: "{{ route('admin.size.edit') }}",
                    method: "GET",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}",
                    }
                }).done(function(res) {
                    $("#update_name").val(res.name);
                    $("#size_id").val(res.id);
                });
            });

            // Xử lý biểu mẫu chỉnh sửa kích thước
            setupEditHandler("#edit_size_form", "#edit_size_btn", "#editSizeModel",
                "{{ route('admin.size.update') }}", initDataTable)

            // Xử lý việc xóa kích thước
            setupDeleteHandler(".deleteIcon", "{{ route('admin.size.delete') }}", initDataTable)

            // Xử lý việc xóa nhiều kích thước
            setupDeleteMultipleHandler("#deleteMultiple", "{{ route('admin.size.delete.all') }}", initDataTable)

            // Xử lý khôi phục lại một kích thước
            setupRestoreHandler(".restoreIcon", "{{ route('admin.size.restore') }}", initDataTable)

            // Xử lý khôi phục nhiều kích thước
            setupRestoreAllHandler("#restoreAll", "{{ route('admin.size.restore.all') }}", initDataTable)

            // Xử lý xóa vĩnh viễn kích thước
            setupForceHandler(".forceIcon", "{{ route('admin.size.force.delete') }}", initDataTable)

            // Xử lý xóa vĩnh viễn nhiều kích thước
            setupForceMultipleHandler("#forceDeleteMultiple", "{{ route('admin.size.force.delete.all') }}",
                initDataTable)
        });
    </script>
@endpush
