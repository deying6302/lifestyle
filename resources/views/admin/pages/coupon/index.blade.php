@extends('admin.layouts.base')

@push('styles-lib')
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/select2/css/select2-bootstrap.min.css') }}">
@endpush

@push('styles')
    <style>
        .dropdown-item.text-red {
            color: rgba(255, 0, 0, 0.7);
        }

        .dropdown-item.text-red:hover {
            color: rgba(255, 0, 0, 1);
        }

        .ck-editor__editable[role="textbox"] {
            /* Editing area */
            min-height: 200px;
        }

        .ck-content .image {
            /* Block images */
            max-width: 80%;
            margin: 20px auto;
        }

        input[disabled] {
            background-color: #ddd !important;
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
                        <h4 class="mb-0">{{ __('admin.breadcrumbs.coupon') }}</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">{{ __('admin.breadcrumbs.manage') }}</li>
                        <li class="breadcrumb-item active"><a
                                href="{{ route('admin.coupon.index') }}">{{ __('admin.sidebar.coupon') }}</a></li>
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
                                <a class="dropdown-item" href="javascript:void(0)" id="addCoupon" data-toggle="modal"
                                    data-target="#addCouponModel">
                                    <i class="far fa-plus-square"></i> {{ __('admin.action.add') }}</a>
                                <a class="dropdown-item" id="restoreAll" href="javascript:void(0)" style="display: none">
                                    <i class="fab fa-cloudversify"></i> {{ __('admin.action.restore_all') }}</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-red" id="deleteMultiple" href="javascript:void(0)">
                                    <i class="fas fa-trash-alt"></i> {{ __('admin.action.delete_temps') }}</a>
                                <a class="dropdown-item text-red" id="forceDeleteMultiple" href="javascript:void(0)"
                                    style="display: none">
                                    <i class="fas fa-trash-alt"></i> {{ __('admin.action.delete_permanently') }}</a>
                            </div>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" class="custom-control-input" name="include_trashed"
                                id="includeTrashedCheckbox">
                            <label class="custom-control-label" for="includeTrashedCheckbox"
                                style="padding-top: 2px">{{ __('admin.action.trash_record') }}</label>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="coupon_datatable" class="display table dataTable table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check custom-checkbox">
                                                <input type="checkbox" class="form-check-input" id="checkAll"
                                                    required="">
                                                <label class="form-check-label" for="checkAll"></label>
                                            </div>
                                        </th>
                                        <th>{{ __('admin.coupon.code') }}</th>
                                        <th>{{ __('admin.coupon.discount_type') }}</th>
                                        <th>{{ __('admin.coupon.discount_value') }}</th>
                                        <th>{{ __('admin.coupon.threshold') }}</th>
                                        <th>{{ __('admin.coupon.quantity') }}</th>
                                        <th>{{ __('admin.coupon.start_date') }}</th>
                                        <th>{{ __('admin.coupon.end_date') }}</th>
                                        <th>{{ __('admin.coupon.is_active') }}</th>
                                        <th>{{ __('admin.coupon.action') }}</th>
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

    <!-- Add Coupon Modal -->
    <div class="modal fade" id="addCouponModel" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.coupon.add_coupon') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="add_coupon_form">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="code">{{ __('admin.coupon.code') }}</label>
                            <input type="text" class="form-control" id="code" name="code"
                                value="{{ old('code') }}" placeholder="{{ __('admin.coupon.enter_code') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.coupon.discount_type') }}</label>
                            <select name="discount_type" id="discount_type" class="form-control">
                                <option value="" selected>{{ __('admin.coupon.select_type') }}</option>
                                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>
                                    {{ __('admin.coupon.fixed') }}
                                </option>
                                <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>
                                    {{ __('admin.coupon.percentage') }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group mb-4" id="isShowDiscount" style="display: none">
                            <label>{{ __('admin.coupon.discount_value') }}</label>
                            <input type="number" name="discount_value" id="discount_value" class="form-control"
                                value="{{ old('discount_value') }}"
                                placeholder="{{ __('admin.coupon.discount_value') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.coupon.threshold') }}</label>
                            <input type="number" min="1" name="threshold" id="threshold" class="form-control"
                                value="{{ old('threshold') }}" placeholder="{{ __('admin.coupon.threshold') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.coupon.quantity') }}</label>
                            <input type="text" name="quantity" class="form-control" value="{{ old('quantity') }}"
                                placeholder="{{ __('admin.coupon.enter_quantity') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label for="add_description">{{ __('admin.coupon.description') }}</label>
                            <textarea class="form-control" id="add_description" name="description"></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.coupon.start_date') }}</label>
                            <input type="datetime-local" name="start_date" id="start_date" class="form-control"
                                value="{{ old('start_date') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.coupon.end_date') }}</label>
                            <input type="datetime-local" name="end_date" id="end_date" class="form-control"
                                value="{{ old('end_date') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light"
                            data-dismiss="modal">{{ __('admin.action.close') }}</button>
                        <button type="submit" id="add_coupon_btn"
                            class="btn btn-primary">{{ __('admin.action.add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Coupon Modal -->
    <div class="modal fade" id="editCouponModel" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.coupon.edit_coupon') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="edit_coupon_form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="coupon_id" id="coupon_id">

                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="update_code">{{ __('admin.coupon.code') }}</label>
                            <input type="text" class="form-control" id="update_code" name="code"
                                value="{{ old('code') }}" placeholder="{{ __('admin.coupon.enter_code') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.coupon.discount_type') }}</label>
                            <select name="discount_type" id="update_discount_type" class="form-control"></select>
                        </div>
                        <div class="form-group mb-4" id="isShowUpdateDiscount" style="display: none">
                            <label>{{ __('admin.coupon.discount_type') }}</label>
                            <input type="text" name="discount_value" id="update_discount_value" class="form-control"
                                value="{{ old('discount_value') }}"
                                placeholder="{{ __('admin.coupon.enter_discount_value') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.coupon.quantity') }}</label>
                            <input type="text" name="quantity" id="update_quantity" class="form-control"
                                value="{{ old('quantity') }}" placeholder="{{ __('admin.coupon.enter_quantity') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label for="edit_description">{{ __('admin.coupon.description') }}</label>
                            <textarea class="form-control" id="edit_description" name="description"></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.coupon.start_date') }}</label>
                            <input type="datetime-local" name="start_date" id="update_start_date" class="form-control"
                                value="{{ old('start_date') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.coupon.end_date') }}</label>
                            <input type="datetime-local" name="end_date" id="update_end_date" class="form-control"
                                value="{{ old('end_date') }}">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light"
                            data-dismiss="modal">{{ __('admin.action.close') }}</button>
                        <button type="submit" id="edit_coupon_btn"
                            class="btn btn-primary">{{ __('admin.action.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts-lib')
    <script src="{{ asset('backend/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/ckeditor5/ckeditor.js') }}"></script>
    <script src="{{ asset('backend/assets/js/ckeditor5/ckeditor-setup.js') }}"></script>
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
        document.addEventListener("DOMContentLoaded", function() {
            const editorConfigs = [{
                    selector: "#add_description",
                    placeholder: "Enter Description",
                    variable: "addEditorD"
                },
                {
                    selector: "#edit_description",
                    placeholder: "Enter Description",
                    variable: "editEditorD"
                },
            ];

            editorConfigs.forEach(config => {
                initializeCKEditor(config.selector, {
                    placeholder: config.placeholder
                }).then(editor => {
                    window[config.variable] = editor;
                });
            });
        });

        $(document).ready(function() {

            const $couponDatatable = $("#coupon_datatable");

            const initDataTable = () => {
                if ($.fn.DataTable.isDataTable($couponDatatable)) {
                    $couponDatatable.DataTable().destroy();
                }

                const dataTable = $couponDatatable.DataTable({
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
                        url: "{{ route('admin.coupon.index') }}",
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
                            data: "code",
                            name: "code"
                        },
                        {
                            data: "discount_type",
                            name: "discount_type"
                        },
                        {
                            data: "discount_value",
                            name: "discount_value",
                            render: function(data, type, full, meta) {
                                if (full.discount_type === "fixed") {
                                    return `<span>${data}đ</span>`;
                                } else {
                                    return `<span>${data}%</span>`;
                                }
                            },
                        },
                        {
                            data: "threshold",
                            name: "threshold"
                        },
                        {
                            data: "quantity",
                            name: "quantity"
                        },
                        {
                            data: "start_date",
                            name: "start_date"
                        },
                        {
                            data: "end_date",
                            name: "end_date"
                        },
                        {
                            data: "is_active",
                            name: "is_active",
                            render: function(data, type, full, meta) {
                                if (data === 1) {
                                    return `<span class="badge badge-success">Hiệu lực</span>`;
                                } else {
                                    return `<span class="badge badge-danger">Hết hạn</span>`;
                                }
                            },
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
                        '#deleteMultiple', '#addCoupon'
                    ];
                    const elementsToHide = checked ? ['#deleteMultiple', '#addCoupon'] : ['#restoreAll',
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

            initializeSelect2('#discount_type');
            initializeSelect2('#update_discount_type');

            $(document).on("change", "#discount_type", function(e) {
                const discountType = $(this).val();
                handleDiscountChange(discountType, '#isShowDiscount', '#discount_value');
            });

            $(document).on("change", "#start_date, #end_date", function() {
                let now = new Date();

                const startDate = new Date($('#start_date').val());
                const endDate = new Date($('#end_date').val());

                // Đặt giờ của ngày hiện tại về 00:00:00 để so sánh chính xác
                now.setHours(now.getHours(), now.getMinutes(), now.getSeconds(), now.getMilliseconds());

                // Kiểm tra ngày bắt đầu
                if (startDate < now) {
                    toastr.warning('Start date must be today or later.');
                    $('#start_date').val('');
                }

                if (startDate && endDate && endDate < startDate) {
                    toastr.warning('End date must be later than start date.');
                    $('#end_date').val('');
                }
            });

            $(document).on("change", "#update_discount_type", function(e) {
                const discountType = $(this).val();
                handleDiscountChange(discountType, '#isShowUpdateDiscount', '#update_discount_value');
            });

            // Xử lý biểu mẫu thêm mã giảm giá
            setupAddHandler("#add_coupon_form", "#add_coupon_btn", "#addCouponModel",
                "{{ route('admin.coupon.store') }}", initDataTable)

            // Nhận dữ liệu được kết xuất trên biểu mẫu chỉnh sửa mã giảm giá
            $(document).on("click", ".editIcon", function(e) {

                e.preventDefault();
                const id = $(this).attr("id");

                $.ajax({
                    url: "{{ route('admin.coupon.edit') }}",
                    method: "GET",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}",
                    }
                }).done(function(res) {
                    $("#coupon_id").val(res.id);
                    $("#update_code").val(res.code);
                    $("#update_discount_value").val(res.discount_value);
                    $("#update_quantity").val(res.quantity);
                    $("#update_start_date").val(formatDateToLocal(res.start_date));
                    $("#update_end_date").val(formatDateToLocal(res.end_date));



                    const discountType = res.discount_type;

                    const discountTypeData = [{
                            value: 'fixed',
                            text: 'Fixed'
                        },
                        {
                            value: 'percentage',
                            text: 'Percentage'
                        }
                    ];

                    const discountTypeOptions = discountTypeData.map(item => {
                        const discountTypeSelected = (item.value === discountType) ?
                            'selected' : '';
                        return `<option value="${item.value}" ${discountTypeSelected}>${item.text}</option>`;
                    });

                    $('#update_discount_type').html(discountTypeOptions);

                    // Cập nhật giá trị và hiển thị trường giảm giá dựa trên loại giảm giá
                    handleDiscountChange(discountType, '#isShowUpdateDiscount',
                        '#update_discount_value');

                    // Đẩy dữ liệu vào ckeditor
                    editEditorD.setData(res.description);
                });
            });

            // Xử lý biểu mẫu chỉnh sửa mã giảm giá
            setupEditHandler("#edit_coupon_form", "#edit_coupon_btn", "#editCouponModel", "{{ route('admin.coupon.update') }}", initDataTable)

            // Xử lý việc xóa mã giảm giá
            setupDeleteHandler(".deleteIcon", "{{ route('admin.coupon.delete') }}", initDataTable)

            // Xử lý việc xóa nhiều mã giảm giá
            setupDeleteMultipleHandler("#deleteMultiple", "{{ route('admin.coupon.delete.all') }}", initDataTable)

            // Xử lý khôi phục lại một mã giảm giá
            setupRestoreHandler(".restoreIcon", "{{ route('admin.coupon.restore') }}", initDataTable)

            // Xử lý khôi phục nhiều mã giảm giá
            setupRestoreAllHandler("#restoreAll", "{{ route('admin.coupon.restore.all') }}", initDataTable)

            // Xử lý xóa vĩnh viễn mã giảm giá
            setupForceHandler(".forceIcon", "{{ route('admin.coupon.force.delete') }}", initDataTable)

            // Xử lý xóa vĩnh viễn nhiều mã giảm giá
            setupForceMultipleHandler("#forceDeleteMultiple", "{{ route('admin.coupon.force.delete.all') }}", initDataTable)
        });

        function handleDiscountChange(discountType, discountSelector, discountInputSelector) {
            const discountInput = $(discountInputSelector);

            if (discountType === 'percentage') {
                $(discountSelector).show();
                discountInput.attr('min', 1);
                discountInput.attr('max', 100);
                discountInput.attr('type', 'number');
            } else if (discountType === 'fixed') {
                $(discountSelector).show();
                discountInput.removeAttr('min');
                discountInput.removeAttr('max');
                discountInput.attr('type', 'number');
            } else {
                $(discountSelector).hide();
            }
        }

        // Format date to datetime-local input
        function formatDateToLocal(dateString) {
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }
    </script>
@endpush
