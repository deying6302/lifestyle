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
    </style>
@endpush

@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">{{ __('admin.sidebar.category') }}</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">{{ __('admin.common.manage') }}</li>
                        <li class="breadcrumb-item active"><a
                                href="{{ route('admin.category.index') }}">{{ __('admin.sidebar.category') }}</a></li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row mb-4">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header justify-content-between align-items-center d-flex">
                        <div>
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-align-justify"></i>
                            </button>
                            <div class="dropdown-menu p-0" style="">
                                <a class="dropdown-item" href="javascript:void(0)" id="addCategory" data-toggle="modal"
                                    data-target="#addCategoryModel">
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
                            <table id="category_datatable" class="display table dataTable table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check custom-checkbox">
                                                <input type="checkbox" class="form-check-input" id="checkAll"
                                                    required="">
                                                <label class="form-check-label" for="checkAll"></label>
                                            </div>
                                        </th>
                                        <th>{{ __('admin.category.category_name') }}</th>
                                        <th>{{ __('admin.common.slug') }}</th>
                                        <th>{{ __('admin.category.category_type') }}</th>
                                        <th>{{ __('admin.category.category_image') }}</th>
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

        <div id="subcategory-wrapper" style="display: none">
            <div class="row">
                <div class="col-12  align-self-center">
                    <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                        <div class="w-sm-100 mr-auto">
                            <h4 class="mb-0">{{ __('admin.category.sub_category') }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header" style="display: flex; justify-content: space-between">
                            <div>
                                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fas fa-align-justify"></i>
                                </button>
                                <div class="dropdown-menu p-0" style="">
                                    <a class="dropdown-item" id="restoreAll1" href="javascript:void(0)"
                                        style="display: none">
                                        <i class="fab fa-cloudversify"></i> {{ __('admin.common.restore_all') }}</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-red" id="deleteMultiple1" href="javascript:void(0)">
                                        <i class="fas fa-trash-alt"></i> {{ __('admin.common.delete_multiple_temps') }}</a>
                                    <a class="dropdown-item text-red" id="forceDeleteMultiple1" href="javascript:void(0)"
                                        style="display: none">
                                        <i class="fas fa-trash-alt"></i>
                                        {{ __('admin.common.delete_many_permanently') }}</a>
                                </div>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" name="include_trashed"
                                    id="includeTrashedCheckbox1">
                                <label class="custom-control-label" for="includeTrashedCheckbox1"
                                    style="padding-top: 2px">{{ __('admin.common.trash_log') }}</label>
                            </div>
                        </div>
                        <div class="card-body" id="subcategory-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModel" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.category.add_category') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="add_category_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="category">{{ __('admin.category.category_name') }}</label>
                            <input type="text" class="form-control" id="add_name" name="name"
                                placeholder="{{ __('admin.category.enter_category_name') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="slug">{{ __('admin.common.slug') }}</label>
                            <input type="text" class="form-control" id="add_slug" name="slug"
                                placeholder="{{ __('admin.common.enter_slug') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.category.category_image') }}</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.category.category_type') }}</label>
                            <select name="type" id="selectAddCategoryType">
                                <option value="" disabled selected>{{ __('admin.category.select_category_type') }}
                                </option>
                                <option value="product">{{ __('admin.sidebar.product') }}</option>
                                <option value="blog">{{ __('admin.sidebar.blog') }}</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.category.parent_category') }}</label>
                            <select name="category_id" id="selectCategory"></select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light"
                            data-dismiss="modal">{{ __('admin.common.close') }}</button>
                        <button type="submit" id="add_category_btn"
                            class="btn btn-primary">{{ __('admin.common.add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModel" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.category.edit_category') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="edit_category_form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="category_id" id="category_id">

                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="update_name">{{ __('admin.category.category_name') }}</label>
                            <input type="text" class="form-control" id="update_name" name="name"
                                placeholder="{{ __('admin.category.enter_category_name') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="update_slug">{{ __('admin.common.slug') }}</label>
                            <input type="text" class="form-control" id="update_slug" name="slug"
                                placeholder="{{ __('admin.category.enter_slug') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.category.category_image') }}</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="mb-4" id="image"></div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.category.category_type') }}</label>
                            <select name="type" id="selectEditCategoryType"></select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light"
                            data-dismiss="modal">{{ __('admin.common.close') }}</button>
                        <button type="submit" id="edit_category_btn"
                            class="btn btn-primary">{{ __('admin.common.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit SubCategory Modal -->
    <div class="modal fade" id="editSubCategoryModel" tabindex="-1" role="dialog" aria-labelledby="editSubModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.category.edit_sub_category') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="edit_subcategory_form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="subcategory_id" id="subcategory_id">

                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="category">{{ __('admin.category.category_name') }}</label>
                            <input type="text" class="form-control" id="update_sub_name" name="name"
                                placeholder="{{ __('admin.category.enter_category_name') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="slug">{{ __('admin.common.slug') }}</label>
                            <input type="text" class="form-control" id="update_sub_slug" name="slug"
                                placeholder="{{ __('admin.category.enter_slug') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.category.category_image') }}</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div id="sub_image"></div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.category.parent_category') }}</label>
                            <select name="category_id" id="selectSubCategory"></select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light"
                            data-dismiss="modal">{{ __('admin.common.close') }}</button>
                        <button type="submit" id="edit_subcategory_btn"
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
    <script src="{{ asset('backend/vendors/select2/js/select2.full.min.js') }}"></script>
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

            const $categoryDatatable = $("#category_datatable");

            const initDataTable = () => {
                if ($.fn.DataTable.isDataTable($categoryDatatable)) {
                    $categoryDatatable.DataTable().destroy();
                }

                const dataTable = $categoryDatatable.DataTable({
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
                        url: "{{ route('admin.category.index') }}",
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
                            data: "slug",
                            name: "slug"
                        },
                        {
                            data: "type",
                            name: "type"
                        },
                        {
                            data: "image",
                            name: "image",
                            render: function(data, type, full, meta) {
                                if (data) {
                                    return `<img src="{{ asset('/uploads/category/') }}/${data}" width="60">`;
                                } else {
                                    return `<img src="{{ asset('/default.png') }}" width="60">`;
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
                        dataTable.draw();
                        shouldReloadData = false;
                    }
                }, 300);

                $('#includeTrashedCheckbox').change(handleCheckboxChange);

                function handleCheckboxChange() {
                    const $checkbox = $(this);
                    const checked = $checkbox.is(":checked");
                    const elementsToShow = checked ? ['#restoreAll', '#forceDeleteMultiple'] : [
                        '#deleteMultiple', '#addCategory'
                    ];
                    const elementsToHide = checked ? ['#deleteMultiple', '#addCategory'] : ['#restoreAll',
                        '#forceDeleteMultiple'
                    ];

                    toggleElements(elementsToShow, elementsToHide);
                    shouldReloadData = true;
                    debounceReloadData();
                }
            };

            const updateOptions = [{
                    key: "product",
                    value: "Product"
                },
                {
                    key: "blog",
                    value: "Blog"
                }
            ];

            // Khởi tạo bảng
            initDataTable();

            initializeSelect2('#selectAddCategoryType');
            initializeSelect2('#selectEditCategoryType');
            initializeSelect2('#selectCategory');
            initializeSelect2('#selectSubCategory');

            $('#selectAddCategoryType').on('change', function() {
                // Lấy giá trị hiện tại của dropdown
                var selectedType = $(this).val();

                // Kiểm tra nếu loại danh mục là "product"
                if (selectedType === "product") {
                    loadSelectCategory();
                } else {
                    $('#selectCategory').empty();
                }
            });

            // Tạo liên kết tạo Slug
            bindSlugGenerator("#add_name, #update_name, #update_sub_name",
                "#add_slug, #update_slug, #update_sub_slug");

            // Xử lý biểu mẫu thêm danh mục
            setupAddHandler("#add_category_form", "#add_category_btn", "#addCategoryModel",
                "{{ route('admin.category.store') }}", initDataTable)

            // Nhận dữ liệu được kết xuất trên biểu mẫu danh mục chỉnh sửa
            $(document).on("click", ".editIcon", function(e) {
                e.preventDefault();
                const id = $(this).attr("id");

                $.ajax({
                    url: "{{ route('admin.category.edit') }}",
                    method: "GET",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    }
                }).done(function(res) {
                    $("#update_name").val(res.name);
                    $("#update_slug").val(res.slug);
                    $("#image").html(
                        `<img src="{{ asset('uploads/category/${res.image}') }}" alt="Category Image" class="img-fluid img-thumbnail" width="100">`
                    );
                    $("#category_id").val(res.id);
                    $("#category_image").val(res.image);

                    $('#selectEditCategoryType').empty();

                    $.each(updateOptions, function(index, item) {
                        var option = $('<option>', {
                            value: item.key,
                            text: item.value
                        });

                        if (item.key == res.type) {
                            option.prop('selected', true);
                        }

                        $('#selectEditCategoryType').append(option);
                    });

                });
            });

            // Xử lý biểu mẫu chỉnh sửa danh mục
            setupEditHandler("#edit_category_form", "#edit_category_btn", "#editCategoryModel",
                "{{ route('admin.category.update') }}", initDataTable)

            // Xử lý xóa một danh mục
            setupDeleteHandler(".deleteIcon", "{{ route('admin.category.delete') }}", initDataTable)

            // Xử lý việc xóa nhiều danh mục
            setupDeleteMultipleHandler("#deleteMultiple", "{{ route('admin.category.delete.all') }}", initDataTable)

            // Xử lý việc khôi phục danh mục
            setupRestoreHandler(".restoreIcon", "{{ route('admin.category.restore') }}", initDataTable)

            // Xử lý phục hồi đa danh mục
            setupRestoreAllHandler("#restoreAll", "{{ route('admin.category.restore.all') }}", initDataTable)

            // Xử lý xóa vĩnh viễn một danh mục
            setupForceHandler(".forceIcon", "{{ route('admin.category.force.delete') }}", initDataTable)

            // Xử lý việc xóa vĩnh viễn nhiều danh mục
            setupForceMultipleHandler("#forceDeleteMultiple", "{{ route('admin.category.force.delete.all') }}",
                initDataTable)

            // ---------------------- Sub category ---------------------------
            let categoryId;

            // Hộp kiểm thay đổi lấy danh mục dữ liệu chọn
            $(document).on('change', '.category-checkbox', function(e) {
                e.preventDefault();

                categoryId = $(this).data('category-id');
                // Lấy index của checkbox được chọn
                var index = $(this).closest('tr').index();
                // Lấy tất cả các checkbox trong DataTable
                var checkboxes = $(this).closest('tbody').find('.category-checkbox');
                // Duyệt qua các checkbox
                checkboxes.each(function(i) {
                    // Nếu checkbox có index lớn hơn index của checkbox được chọn
                    if (i > index) {
                        // Nếu checkbox được chọn
                        if (index >= 0 && $('.category-checkbox:eq(' + index + ')').is(
                                ':checked')) {
                            $(this).prop('disabled', true);
                        } else {
                            $(this).prop('disabled', false);
                        }
                    }
                    // Nếu checkbox có index nhỏ hơn index của checkbox được chọn
                    else if (i < index) {
                        if ($('.category-checkbox:eq(' + index + ')').is(':checked')) {
                            $(this).prop('disabled', true);
                        } else {
                            $(this).prop('disabled', false);
                        }
                    }
                    // Nếu checkbox có index bằng index của checkbox được chọn
                    else {
                        if ($(this).is(':checked')) {
                            $(this).prop('disabled', false);
                            $("#subcategory-wrapper").show();
                            // Gọi hàm để tải danh mục con
                            loadSubCategoryById(categoryId);
                        } else {
                            // Nếu checkbox không được chọn, xóa nội dung của #subcategory-wrapper
                            $("#subcategory-wrapper").hide();
                        }
                    }
                });
            });

            $('#includeTrashedCheckbox1').change(handleCheckboxChange1);

            function handleCheckboxChange1() {
                const $checkbox = $(this);
                const checked = $checkbox.is(":checked");
                const elementsToShow = checked ? ['#restoreAll1', '#forceDeleteMultiple1'] : [
                    '#deleteMultiple1'
                ];
                const elementsToHide = checked ? ['#deleteMultiple1'] : ['#restoreAll1',
                    '#forceDeleteMultiple1'
                ];

                toggleElements(elementsToShow, elementsToHide)
                loadSubCategoryById(categoryId);
            }

            // Nhận dữ liệu được kết xuất trên biểu mẫu danh mục con chỉnh sửa
            $(document).on("click", ".editSubIcon", function(e) {
                e.preventDefault();
                const id = $(this).attr("id");

                $.ajax({
                    url: "{{ route('admin.subcategory.sub.edit') }}",
                    method: "GET",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}",
                    }
                }).done(function(res) {
                    $('#update_sub_name').val(res.subcategory.name);
                    $('#update_sub_slug').val(res.subcategory.slug);
                    $("#sub_image").html(
                        `<img src="{{ asset('uploads/subcategory/${res.subcategory.image}') }}" alt="SubCategory Image" class="img-fluid img-thumbnail" width="100">`
                    );
                    $("#subcategory_id").val(res.subcategory.id);

                    $('#selectSubCategory').empty();
                    $.each(res.categories, function(index, category) {
                        var option = $('<option>', {
                            value: category.id,
                            text: category.name
                        });

                        // Kiểm tra nếu category.id bằng res.subcategory.category_id
                        if (category.id == res.subcategory.category_id) {
                            option.prop('selected', true);
                        }

                        $('#selectSubCategory').append(option);
                    });
                });
            });

            // Xử lý biểu mẫu chỉnh sửa danh mục con
            setupEditHandler("#edit_subcategory_form", "#edit_subcategory_btn", "#editSubCategoryModel",
                "{{ route('admin.subcategory.update') }}",
                function() {
                    loadSubCategoryById(categoryId);
                })


            // Xử lý xóa một danh mục con
            setupDeleteHandler(".deleteSubIcon", "{{ route('admin.subcategory.delete') }}", function() {
                loadSubCategoryById(categoryId);
            })


            // Xử lý việc xóa nhiều danh mục con
            setupDeleteMultipleHandler("#deleteMultiple1", "{{ route('admin.subcategory.delete.all') }}", function() {
                loadSubCategoryById(categoryId);
            })

            // Xử lý việc khôi phục danh mục con
            setupRestoreHandler(".restoreSubIcon", "{{ route('admin.subcategory.restore') }}", function() {
                loadSubCategoryById(categoryId);
            })

            // Xử lý khôi phục nhiều danh mục con
            setupRestoreAllHandler("#restoreAll1", "{{ route('admin.subcategory.restore.all') }}", function() {
                loadSubCategoryById(categoryId);
            })

            // Xử lý xóa vĩnh viễn một danh mục con
            setupForceHandler(".forceSubIcon", "{{ route('admin.subcategory.force.delete') }}", function() {
                loadSubCategoryById(categoryId);
            })

            // Xử lý việc xóa vĩnh viễn nhiều danh mục con
            setupForceMultipleHandler("#forceDeleteMultiple1", "{{ route('admin.subcategory.force.delete.all') }}", function() {
                loadSubCategoryById(categoryId);
            })

            $(document).on("click", "#checkAllSubCategory", function() {
                $(".checkbox_subcategory_ids").prop("checked", $(this).prop("checked"));
            });
        });

        // Hiển thị tất cả danh mục
        function loadSelectCategory() {
            $.ajax({
                url: "{{ route('admin.category.select') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#selectCategory').empty();
                    $('#selectCategory').append($('<option>', {
                        value: '',
                        text: 'Chọn danh mục'
                    }));

                    $.each(response, function(index, category) {
                        $('#selectCategory').append($('<option>', {
                            value: category.id,
                            text: category.name
                        }));
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Request failed. Status:', xhr.status);
                }
            });
        }

        // Chức năng tải danh mục con theo danh mục cha
        function loadSubCategoryById(categoryId) {
            $.ajax({
                url: "{{ route('admin.subcategory.list') }}",
                type: 'GET',
                data: {
                    id: categoryId,
                    include_trashed: $('#includeTrashedCheckbox1').is(':checked') ? 1 : 0,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    $("#subcategory-content").html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Request failed. Status:', xhr.status);
                }
            });
        }
    </script>
@endpush
