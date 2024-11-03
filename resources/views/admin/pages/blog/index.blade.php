@extends('admin.layouts.base')

@push('styles-lib')
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/select2/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/dropify/css/dropify.min.css') }}">
    <link rel="stylesheet" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
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

        .tag {
            background: black;
            color: #fff;
            padding: 2px;
            border-radius: 4px;
            margin: 4px;
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
                        <h4 class="mb-0">{{ __('admin.sidebar.blog') }}</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">{{ __('admin.common.manage') }}</li>
                        <li class="breadcrumb-item active"><a href="{{ route('admin.blog.index') }}">{{ __('admin.sidebar.blog') }}</a></li>
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
                                <a class="dropdown-item" href="javascript:void(0)" id="addBlog" data-toggle="modal"
                                    data-target="#addBlogModel">
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
                            <label class="custom-control-label" for="includeTrashedCheckbox" style="padding-top: 2px">{{ __('admin.common.trash_log') }}</label>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="blog_datatable" class="display table dataTable table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check custom-checkbox">
                                                <input type="checkbox" class="form-check-input" id="checkAll"
                                                    required="">
                                                <label class="form-check-label" for="checkAll"></label>
                                            </div>
                                        </th>
                                        <th>{{ __('admin.blog.blog_title') }}</th>
                                        <th>{{ __('admin.common.slug') }}</th>
                                        <th>{{ __('admin.blog.blog_view') }}</th>
                                        <th>{{ __('admin.blog.blog_tags') }}</th>
                                        <th>{{ __('admin.blog.blog_category') }}</th>
                                        <th>{{ __('admin.blog.blog_author') }}</th>
                                        <th>{{ __('admin.blog.blog_image') }}</th>
                                        <th>{{ __('admin.common.status') }}</th>
                                        <th>{{ __('admin.common.created_at') }}</th>
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

    <!-- Add Blog Modal -->
    <div class="modal fade" id="addBlogModel" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.blog.add_blog') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="add_blog_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="add_title">{{ __('admin.blog.blog_title') }}</label>
                            <input type="text" class="form-control" id="add_title" name="title"
                                placeholder="Enter title" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="add_slug">{{ __('admin.common.slug') }}</label>
                            <input type="text" class="form-control" id="add_slug" name="slug"
                                placeholder="Enter slug">
                        </div>
                        <div class="form-group mb-4">
                            <label for="add_description">{{ __('admin.blog.blog_desc') }}</label>
                            <textarea class="form-control" id="add_description" name="description"></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label for="add_content">{{ __('admin.blog.blog_cont') }}</label>
                            <textarea class="form-control" id="add_content" name="content"></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label for="">{{ __('admin.blog.blog_tags') }}</label>
                            <select name="tags[]" class="form-control select2-auto-tokenize" multiple="multiple"
                                required>
                                <option value="Marketing">Marketing</option>
                                <option value="Hub">Hub</option>
                                <option value="Finance">Finance</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.blog.blog_image') }}</label>
                            <input type="file" id="input-file-now" class="dropify" accept="image/*" name="image"
                                data-default-file="" data-height="300" />
                        </div>
                        <div class="form-group mb-4">
                            <label for="category_id">{{ __('admin.sidebar.category') }}</label>
                            <select class="default-select form-control wide mb-3" name="category_id"
                                id="selectCategoryAdd">
                                <option value="" selected>{{ __('admin.category.select_category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal">{{ __('admin.common.close') }}</button>
                        <button type="submit" id="add_blog_btn" class="btn btn-primary">{{ __('admin.common.add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Blog Modal -->
    <div class="modal fade" id="editBlogModel" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.blog.edit_blog') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="edit_blog_form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="blog_id" id="blog_id">
                    <input type="hidden" name="blog_image" id="blog_image">

                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="edit_title">{{ __('admin.blog.blog_title') }}</label>
                            <input type="text" class="form-control" id="edit_title" name="title"
                                placeholder="{{ __('admin.blog.enter_title') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="edit_slug">{{ __('admin.common.slug') }}</label>
                            <input type="text" class="form-control" id="edit_slug" name="slug"
                                placeholder="{{ __('admin.common.enter_slug') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label for="edit_description">{{ __('admin.blog.blog_desc') }}</label>
                            <textarea class="form-control" id="edit_description" name="description"></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label for="edit_content">{{ __('admin.blog.blog_cont') }}</label>
                            <textarea class="form-control" id="edit_content" name="content"></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label for="">{{ __('admin.blog.blog_tags') }}</label>
                            <select name="tags[]" class="form-control select2-auto-tokenize" id="selectTagsEdit"
                                multiple="multiple" required>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.blog.blog_image') }}</label>
                            <input type="file" id="input-file-now" class="dropify" accept="image/*" name="image"
                                data-default-file="" data-height="300" />
                        </div>
                        <div id="image" class="mb-4"></div>
                        <div class="form-group mb-4">
                            <label for="category_id">{{ __('admin.sidebar.category') }}</label>
                            <select class="default-select form-control wide mb-3" name="category_id"
                                id="selectCategoryEdit">
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal">{{ __('admin.common.close') }}</button>
                        <button type="submit" id="edit_blog_btn" class="btn btn-primary">{{ __('admin.common.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts-lib')
    <script src="{{ asset('backend/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/custom.js') }}"></script>

    <script src="{{ asset('backend/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/ckeditor5/ckeditor.js') }}"></script>
    <script src="{{ asset('backend/assets/js/ckeditor5/ckeditor-setup.js') }}"></script>
    <script src="{{ asset('backend/vendors/dropify/js/dropify.min.js') }}"></script>
@endpush

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            initializeCKEditor("#add_description", {
                placeholder: 'Enter description'
            }).then(addEditorDesc => {
                addEditorD = addEditorDesc;
            });

            initializeCKEditor("#add_content", {
                placeholder: 'Enter content'
            }).then(addEditorContent => {
                addEditorC = addEditorContent;
            });

            initializeCKEditor("#edit_description", {
                placeholder: 'Enter description'
            }).then(editEditorDesc => {
                editEditorD = editEditorDesc;
            });

            initializeCKEditor("#edit_content", {
                placeholder: 'Enter content'
            }).then(editEditorContent => {
                editEditorC = editEditorContent;
            });
        });

        $(document).ready(function() {
            $('.select2-auto-tokenize').select2({
                dropdownParent: $('.card-body'),
                tags: true,
                tokenSeparators: [',']
            });

            // Tải lên thả xuống
            $('.dropify').dropify();
            $(".dropify-fr").dropify({
                messages: {
                    default: "Drag and drop a file here or click to replace",
                    replace: "Drag and drop a file or click to replace",
                    remove: "Remove",
                    error: "Sorry, the file is too large",
                },
            });

            // Khởi tạo DataTable
            const $blogDatatable = $("#blog_datatable");

            const initDataTable = () => {
                if ($.fn.DataTable.isDataTable($blogDatatable)) {
                    $blogDatatable.DataTable().destroy();
                }

                const dataTable = $blogDatatable.DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    language: {
                        sSearch: `<span class="fs-14">Search: </span>`,
                        sProcessing: 'Loading <i class="fa fa-spinner" style="transition: 2s;"></i>',
                        oPaginate: {
                            sNext: '<i class="fa fa-chevron-right"></i>',
                            sPrevious: '<i class="fa fa-chevron-left"></i>',
                        },
                    },
                    ajax: {
                        url: "{{ route('admin.blog.index') }}",
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
                            data: "title",
                            name: "title"
                        },
                        {
                            data: "slug",
                            name: "slug"
                        },
                        {
                            data: "view_count",
                            name: "view_count"
                        },
                        {
                            data: "tags",
                            name: "tags",
                            render: function(data, type, full, meta) {
                                return full.tags_array.map(tag => '<span class="tag">' + tag +
                                    '</span>').join(' ');
                            }
                        },
                        {
                            data: 'category_name',
                            name: 'category_name',
                            render: function(data, type, full, meta) {
                                return full.category ? full.category.name : '';
                            }
                        },
                        {
                            data: 'admin_name',
                            name: 'admin_name',
                            render: function(data, type, full, meta) {
                                return full.admin ? full.admin.full_name : '';
                            }
                        },
                        {
                            data: "image",
                            name: "image",
                            render: function(data, type, full, meta) {
                                if (data) {
                                    return `<img src="{{ asset('/uploads/blog/') }}/${data}" width="60">`;
                                } else {
                                    return `<img src="{{ asset('/default.png') }}" width="60">`;
                                }
                            },
                        },
                        {
                            data: "status",
                            name: "status",
                            render: function(data, type, full, meta) {
                                if (data === 1) {
                                    return `<span class="badge p-2 badge-success mb-1">Hiển thị</span>`;
                                } else {
                                    return `<span class="badge p-2 badge-dark mb-1">Ẩn</span>`;
                                }
                            }
                        },
                        {
                            data: "created_at",
                            name: "created_at"
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
                        '#deleteMultiple', '#addBlog'
                    ];
                    const elementsToHide = checked ? ['#deleteMultiple', '#addBlog'] : ['#restoreAll',
                        '#forceDeleteMultiple'
                    ];

                    toggleElements(elementsToShow, elementsToHide);
                    shouldReloadData = true;
                    debounceReloadData();
                }
            };

            // Khởi tạo ban đầu
            initDataTable();

            // Khỏi tạo hộp chọn select2
            initializeSelect2('#selectCategoryAdd');
            initializeSelect2('#selectCategoryEdit');

            // Xử lý chọn nhiều ô kiểm
            $(document).on("click", "#checkAll", function() {
                $(".checkbox_ids").prop("checked", $(this).prop("checked"));
            });

            // Tạo liên kết tạo Slug
            bindSlugGenerator("#add_title, #edit_title", "#add_slug, #edit_slug");

            // Xử lý biểu mẫu thêm bài viết
            $(document).on("submit", "#add_blog_form", function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#add_blog_btn").text("Adding...");

                $.ajax({
                    url: "{{ route('admin.blog.store') }}",
                    method: "POST",
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "json"
                }).done(function(res) {
                    if (res.status == 200) {
                        toastr.success(res.message);
                        initDataTable();

                        addEditorD.setData("");
                        addEditorC.setData("");

                        $("#add_blog_btn").text("Add");
                        $("#add_blog_form")[0].reset();
                        $("#addBlogModel").modal("hide");

                        // Đặt lại trường input file
                        $('#input-file-now').dropify('clear');
                        $('#input-file-now').val('');

                        $('.dropify-preview').hide();

                        // Xóa các lựa chọn đã được chọn
                        $('select[name="tags[]"]').val(null).trigger('change');

                        // Đặt lại giá trị của select category về giá trị mặc định, là disabled và là selected
                        $('#selectCategoryAdd').prop('disabled', false).val(null).trigger('change')
                            .trigger('select2:unselect');
                    }
                });
            });

            // Đặt lại dữ liệu cho modal
            $('#addBlogModel, #editBlogModel').on('hidden.modal', function() {
                $(this).find('form').trigger('reset');
            });

            // Nhận dữ liệu được kết xuất trên biểu mẫu bài viết chỉnh sửa
            $(document).on("click", ".editIcon", function(e) {
                e.preventDefault();
                const id = $(this).attr("id");

                $.ajax({
                    url: "{{ route('admin.blog.edit') }}",
                    method: "GET",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    }
                }).done(function(res) {
                    $('#blog_id').val(res.blog.id);
                    $('#blog_image').val(res.blog.image);
                    $("#edit_title").val(res.blog.title)
                    $("#edit_slug").val(res.blog.slug);
                    $("#image").html(
                        `<img src="{{ asset('uploads/blog/${res.blog.image}') }}" alt="Product Image" class="img-fluid img-thumbnail" width="100">`
                    );

                    editEditorD.setData(res.blog.description);
                    editEditorC.setData(res.blog.content);

                    // Update select dropdowns
                    const categoryId = res.blog.category_id;
                    const tags = res.blog.tags;
                    const categories = res.categories;

                    // Tags
                    const tagData = [{
                            value: "Marketing",
                            text: "Marketing"
                        },
                        {
                            value: "Finance",
                            text: "Finance"
                        },
                        {
                            value: "Hub",
                            text: "Hub"
                        }
                    ];

                    const cateOptions = categories.map(category => {
                        const cateSelected = (category.id ==
                            categoryId) ? 'selected' : '';
                        return `<option value="${category.id}" ${cateSelected}>${category.name}</option>`;
                    });

                    const tagOptions = tagData.map(item => {
                        const tagSelected = (tags.includes(item.value)) ? 'selected' :
                            ''; // Kiểm tra xem tag có trong danh sách tags hay không
                        return `<option value="${item.value}" ${tagSelected}>${item.text}</option>`;
                    });

                    // Render UI
                    $("#selectCategoryEdit").html(cateOptions);
                    $("#selectTagsEdit").html(tagOptions);
                });
            });

            // Xử lý biểu mẫu chỉnh sửa bài viết
            $(document).on("submit", "#edit_blog_form", function(e) {
                e.preventDefault();
                const csrfToken = $('meta[name="csrf-token"]').attr("content");
                const fd = new FormData(this);
                fd.append("_method", "PUT");

                $("#edit_blog_btn").text("Updating...");

                $.ajax({
                    url: "{{ route('admin.blog.update') }}",
                    type: "POST",
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.status == 200) {
                            swal("Updated!", res.message, "success");
                            initDataTable();
                        }
                        $("#edit_blog_btn").text("Update");
                        $("#edit_blog_form")[0].reset();
                        $("#editBlogModel").modal("hide");
                    },
                });
            });

            // Xử lý xóa bài viết
            $(document).on("click", ".deleteIcon", function(e) {
                e.preventDefault();
                const id = $(this).attr("id");
                const csrfToken = $('meta[name="csrf-token"]').attr("content");

                swal({
                        title: "Bạn có chắc không?",
                        text: "Sau khi xóa, bạn vẫn có thể khôi phục bài viết này trong thùng rác!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: 'btn-danger',
                        confirmButtonText: 'Vâng, tôi xóa!',
                        cancelButtonText: "Không, tôi hủy!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "{{ route('admin.blog.delete') }}",
                                method: "DELETE",
                                data: {
                                    id: id,
                                },
                                headers: {
                                    "X-CSRF-TOKEN": csrfToken,
                                },
                                dataType: "json"
                            }).done(function(res) {
                                if (res.status == 200) {
                                    swal("Đã Xóa!", res.message, "success");
                                    initDataTable();
                                } else {
                                    swal("Error!", "Xóa bài viết thất bại!", "error");
                                }
                            });
                        } else {
                            swal("Cancelled", "Hủy bỏ việc xóa bài viết", "warning");
                        }
                    });
            });

            // Xử lý việc xóa nhiều bài viết
            $(document).on("click", "#deleteMultiple", function(e) {
                e.preventDefault();

                var checked = $(this).prop("checked");
                var checkboxes = $(".checkbox_ids");

                if (!checked && checkboxes.filter(":checked").length === 0) {
                    // Nếu không có checkbox nào được chọn và checkbox #checkAll cũng không được chọn
                    toastr.error("Bạn chưa chọn bất kỳ mục nào để xóa.");
                    return; // Ngăn chặn việc thực hiện Ajax nếu không có checkbox nào được chọn
                }

                // Nếu có ít nhất một checkbox được chọn hoặc checkbox #checkAll được chọn
                var ids = [];
                const csrfToken = $('meta[name="csrf-token"]').attr("content");

                checkboxes.each(function() {
                    if ($(this).prop("checked")) {
                        ids.push($(this).val()); // Lấy giá trị của các checkbox đã được chọn
                    }
                });

                swal({
                        title: "Bạn có chắc không?",
                        text: "Sau khi xóa, bạn vẫn có thể khôi phục bài viết này trong thùng rác!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: 'btn-danger',
                        confirmButtonText: 'Vâng, tôi xóa!',
                        cancelButtonText: "Không, tôi hủy!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "{{ route('admin.blog.delete.all') }}",
                                method: "DELETE",
                                data: {
                                    ids: ids,
                                },
                                headers: {
                                    "X-CSRF-TOKEN": csrfToken,
                                },
                                dataType: "json"
                            }).done(function(res) {
                                if (res.status == 200) {
                                    swal("Đã Xóa!", res.message, "success");
                                    $("#checkAll").prop("checked", false);
                                    initDataTable();
                                } else {
                                    swal("Error!", "Xóa nhiều bài viết thất bại", "error");
                                }
                            });
                        } else {
                            swal("Cancelled", "Hủy bỏ việc xóa nhiều bài viết", "warning");
                        }
                    }
                );
            });

            // Xử lý việc khôi phục bài viết
            $(document).on("click", ".restoreIcon", function(e) {
                e.preventDefault();
                const id = $(this).attr("id");
                const csrfToken = $('meta[name="csrf-token"]').attr("content");

                $.ajax({
                    url: "{{ route('admin.blog.restore') }}",
                    method: "POST",
                    data: {
                        id: id,
                    },
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    dataType: "json"
                }).done(function(res) {
                    if (res.status == 200) {
                        toastr.success(res.message);
                        initDataTable();
                    } else {
                        toastr.error(res.message);
                    }
                });
            });

            // Xử lý khôi phục nhiều bài viết
            $(document).on("click", "#restoreAll", function(e) {
                e.preventDefault();
                const csrfToken = $('meta[name="csrf-token"]').attr("content");

                $.ajax({
                    url: "{{ route('admin.blog.restore.all') }}",
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    dataType: "json"
                }).done(function(res) {
                    if (res.status == 200) {
                        toastr.success(res.message);
                        $("#checkAll").prop("checked", false);
                        initDataTable();
                    } else {
                        toastr.error(res.message);
                    }
                });
            });

            // Xử lý xóa vĩnh viễn một bài viết
            $(document).on("click", ".forceIcon", function(e) {
                e.preventDefault();
                const id = $(this).attr("id");
                const csrfToken = $('meta[name="csrf-token"]').attr("content");

                swal({
                        title: "Bạn có chắc không?",
                        text: "Sau khi xóa, bạn sẽ không thể khôi phục bài viết này nữa!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: 'btn-danger',
                        confirmButtonText: 'Vâng, tôi xóa!',
                        cancelButtonText: "Không, tôi hủy!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "{{ route('admin.blog.force.delete') }}",
                                method: "DELETE",
                                data: {
                                    id: id,
                                },
                                headers: {
                                    "X-CSRF-TOKEN": csrfToken,
                                },
                                dataType: "json"
                            }).done(function(res) {
                                if (res.status == 200) {
                                    swal("Đã Xóa!", res.message, "success");
                                    initDataTable();
                                } else {
                                    swal("Lỗi!", res.message, "error");
                                }
                            });
                        } else {
                            swal("Hủy bỏ", "Đã hủy bỏ việc xóa bài viết", "warning");
                        }
                    }
                );
            });

            // Xử lý việc xóa vĩnh viễn nhiều bài viết
            $(document).on("click", "#forceDeleteMultiple", function(e) {
                e.preventDefault();

                var checked = $(this).prop("checked");
                var checkboxes = $(".checkbox_ids");

                if (!checked && checkboxes.filter(":checked").length === 0) {
                    // Nếu không có checkbox nào được chọn và checkbox #checkAll cũng không được chọn
                    toastr.error("Bạn chưa chọn bất kỳ mục nào để xóa.");
                    return; // Ngăn chặn việc thực hiện Ajax nếu không có checkbox nào được chọn
                }

                // Nếu có ít nhất một checkbox được chọn hoặc checkbox #checkAll được chọn
                var ids = [];
                const csrfToken = $('meta[name="csrf-token"]').attr("content");

                checkboxes.each(function() {
                    if ($(this).prop("checked")) {
                        ids.push($(this).val()); // Lấy giá trị của các checkbox đã được chọn
                    }
                });

                swal({
                        title: "Bạn có chắc không?",
                        text: "Sau khi xóa, bạn không thể khôi phục bài viết này nữa!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: 'btn-danger',
                        confirmButtonText: 'Vâng, tôi xóa!',
                        cancelButtonText: "Không, tôi hủy!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "{{ route('admin.blog.force.delete.all') }}",
                                method: "DELETE",
                                data: {
                                    ids: ids,
                                },
                                headers: {
                                    "X-CSRF-TOKEN": csrfToken,
                                },
                                dataType: "json"
                            }).done(function(res) {
                                if (res.status == 200) {
                                    swal("Đã Xóa!", res.message, "success");
                                    $("#checkAll").prop("checked", false);
                                    initDataTable();
                                } else {
                                    swal("Error!", "Xóa nhiều bài viết thất bại", "error");
                                }
                            });
                        } else {
                            swal("Cancelled", "Hủy bỏ việc xóa nhiều bài viết", "warning");
                        }
                    }
                );
            });

            // Click status item slider
            $(document).on('click', '.statusIcon', function(e) {
                e.preventDefault();

                const id = $(this).attr("id");
                var currentStatus = $(this).hasClass('btn-success') ? 1 : 0;
                var newStatus = currentStatus === 1 ? 0 : 1;
                const csrfToken = $('meta[name="csrf-token"]').attr("content");

                $.ajax({
                    url: "{{ route('admin.blog.change.status') }}",
                    method: 'POST',
                    data: {
                        id: id,
                        new_status: newStatus
                    },
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                }).done(function(res) {
                    if (res.status == 200) {
                        if (newStatus === 1) {
                            $('#' + id).removeClass('btn-dark').addClass(
                                'btn-success');
                        } else {
                            $('#' + id).removeClass('btn-success').addClass(
                                'btn-dark');
                        }

                        toastr.success(res.message);
                        initDataTable();
                    } else {
                        toastr.error(res.message);
                    }
                });
            });
        });

        // Hàm hiển thị/ẩn phần tử dựa trên trạng thái checked
        function toggleElements(elementsToShow, elementsToHide) {
            elementsToShow.forEach(element => $(element).show());
            elementsToHide.forEach(element => $(element).hide());
        }
    </script>
@endpush
