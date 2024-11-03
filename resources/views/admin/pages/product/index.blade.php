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

        .tag {
            display: inline-block;
            background-color: darkgray;
            border-radius: 3px;
            padding: 2px 6px;
            margin: 2px;
            font-size: 12px;
        }

        .df {
            display: flex;
        }

        .align-items-center {
            align-items: center
        }

        .mb-0 {
            margin-bottom: 0px !important;
        }

        .mr-1 {
            margin-right: 1px;
        }

        .mr-8 {
            margin-right: 8px;
        }

        .custom {
            border: 1px solid #ccc;
            padding: 8px;
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
                        <h4 class="mb-0">{{ __('admin.sidebar.product') }}</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">{{ __('admin.common.manage') }}</li>
                        <li class="breadcrumb-item active"><a
                                href="{{ route('admin.product.index') }}">{{ __('admin.sidebar.product') }}</a></li>
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
                                <a class="dropdown-item" href="javascript:void(0)" id="addProduct" data-toggle="modal"
                                    data-target="#addProductModel">
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
                            <table id="product_datatable" class="display table dataTable table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check custom-checkbox">
                                                <input type="checkbox" class="form-check-input" id="checkAll"
                                                    required="">
                                                <label class="form-check-label" for="checkAll"></label>
                                            </div>
                                        </th>
                                        <th>{{ __('admin.product.product_name') }}</th>
                                        <th>{{ __('admin.product.product_quantity') }}</th>
                                        <th>{{ __('admin.product.product_sold_quantity') }}</th>
                                        <th>{{ __('admin.product.SKU') }}</th>
                                        <th>{{ __('admin.product.product_tag') }}</th>
                                        <th>{{ __('admin.product.product_price') }}</th>
                                        <th>{{ __('admin.product.product_discount') }}</th>
                                        <th>{{ __('admin.product.product_image') }}</th>
                                        <th>{{ __('admin.product.category_name') }}</th>
                                        <th>{{ __('admin.product.brand_name') }}</th>
                                        <th>{{ __('admin.product.colors') }}</th>
                                        <th>{{ __('admin.product.sizes') }}</th>
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

        <div class="gallery-wrapper" style="display: none">
            <div class="row">
                <div class="col-12  align-self-center">
                    <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                        <div class="w-sm-100 mr-auto">
                            <h4 class="mb-0">{{ __('admin.product.gallery') }}</h4>
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
                                    <a class="dropdown-item" href="javascript:void(0)" id="addGallery" data-toggle="modal"
                                        data-target="#addGalleryModel">
                                        <i class="far fa-plus-square"></i> {{ __('admin.common.add') }}</a>
                                    <a class="dropdown-item" id="restoreGalleryAll" href="javascript:void(0)"
                                        style="display: none">
                                        <i class="fab fa-cloudversify"></i> {{ __('admin.common.restore_all') }}</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-red" id="deleteGalleryMultiple"
                                        href="javascript:void(0)">
                                        <i class="fas fa-trash-alt"></i>
                                        {{ __('admin.common.delete_multiple_temps') }}</a>
                                    <a class="dropdown-item text-red" id="forceDeleteGalleryMultiple"
                                        href="javascript:void(0)" style="display: none">
                                        <i class="fas fa-trash-alt"></i>
                                        {{ __('admin.common.delete_many_permanently') }}</a>
                                </div>
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" name="include_trashed"
                                    id="includeGalleryCheckboxTrash">
                                <label class="custom-control-label" for="includeGalleryCheckboxTrash"
                                    style="padding-top: 2px">{{ __('admin.common.trash_log') }}</label>
                            </div>
                        </div>
                        <div class="card-body" id="gallery-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModel" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.product.add_product') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="add_product_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="add_name">{{ __('admin.product.product_name') }}</label>
                            <input type="text" class="form-control" id="add_name" name="name"
                                placeholder="{{ __('admin.product.enter_product_name') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="add_slug">{{ __('admin.common.slug') }}</label>
                            <input type="text" class="form-control" id="add_slug" name="slug"
                                placeholder="{{ __('admin.common.enter_slug') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label for="quantity">{{ __('admin.product.product_quantity') }}</label>
                            <input type="number" min="1" max="200" class="form-control" name="quantity"
                                id="quantity" placeholder="{{ __('admin.product.enter_product_quantity') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label for="price">{{ __('admin.product.product_price') }}</label>
                            <input type="text" class="form-control" name="price" id="price"
                                placeholder="{{ __('admin.product.enter_product_price') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label for="discount">{{ __('admin.product.product_discount') }}</label>
                            <input type="text" class="form-control" name="discount" id="discount"
                                placeholder="{{ __('admin.product.enter_product_discount') }}" min="0"
                                max="100" value="0">
                        </div>
                        <div class="form-group mb-4">
                            <label for="add_description">{{ __('admin.product.product_description') }}</label>
                            <textarea class="form-control" id="add_description" name="description"></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label for="add_content">{{ __('admin.product.product_content') }}</label>
                            <textarea class="form-control" id="add_content" name="content"></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label for="SKU">{{ __('admin.product.SKU') }}</label>
                            <input type="text" class="form-control" id="SKU" name="SKU" readonly>
                            <div class="input-group-append" style="margin-top: 8px">
                                <button type="button" class="btn btn-secondary" id="generateSKU">Generate SKU</button>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="tags">{{ __('admin.product.product_tag') }}</label>
                            <input type="text" class="form-control" id="tags" data-role="tagsinput"
                                name="tags">
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.product.product_image') }}</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.product.category_id') }}</label>
                            <select name="category_id" id="selectAddCategory">
                                <option value="">------ {{ __('admin.category.select_category') }} -----</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.product.subcategory_id') }}</label>
                            <select name="subcategory_id" id="selectAddSubcategory">
                                <option value="">------ {{ __('admin.category.select_subcategory') }} -----</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.product.brand_id') }}</label>
                            <select name="brand_id" id="selectAddBrand">
                                <option value="">------ {{ __('admin.brand.select_brand') }} -----</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.product.colors') }}</label>
                            <div class="df custom">
                                @foreach ($colors as $color)
                                    <div class="df align-items-center mr-8">
                                        <input type="checkbox" class="mr-1" name="color_ids[]"
                                            value="{{ $color->id }}" id="color_{{ $color->id }}">
                                        <label for="color_{{ $color->id }}"
                                            class="mb-0">{{ $color->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.product.sizes') }}</label>
                            <div class="df custom">
                                @foreach ($sizes as $size)
                                    <div class="df align-items-center mr-8">
                                        <input type="checkbox" class="mr-1" name="size_ids[]"
                                            value="{{ $size->id }}" id="size_{{ $size->id }}">
                                        <label for="size_{{ $size->id }}" class="mb-0">{{ $size->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light"
                                data-dismiss="modal">{{ __('admin.common.close') }}</button>
                            <button type="submit" id="add_product_btn"
                                class="btn btn-primary">{{ __('admin.common.add') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModel" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.product.edit_product') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="edit_product_form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="product_id" id="product_id">

                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="update_name">{{ __('admin.product.product_name') }}</label>
                            <input type="text" class="form-control" id="update_name" name="name"
                                placeholder="{{ __('admin.product.enter_product_name') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="update_slug">{{ __('admin.common.slug') }}</label>
                            <input type="text" class="form-control" id="update_slug" name="slug"
                                placeholder="{{ __('admin.common.enter_slug') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label for="update_quantity">{{ __('admin.product.product_quantity') }}</label>
                            <input type="number" min="1" max="200" class="form-control" name="quantity"
                                id="update_quantity" placeholder="{{ __('admin.product.enter_product_quantity') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label for="update_price">{{ __('admin.product.product_price') }}</label>
                            <input type="text" class="form-control" name="price" id="update_price"
                                placeholder="{{ __('admin.product.enter_product_price') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label for="update_discount">{{ __('admin.product.product_discount') }}</label>
                            <input type="text" class="form-control" name="discount" id="update_discount"
                                placeholder="{{ __('admin.product.enter_product_discount') }}" min="0"
                                max="100" value="0">
                        </div>
                        <div class="form-group mb-4">
                            <label for="edit_description">{{ __('admin.product.product_description') }}</label>
                            <textarea class="form-control" id="edit_description" name="description"></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label for="edit_content">{{ __('admin.product.product_content') }}</label>
                            <textarea class="form-control" id="edit_content" name="content"></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label for="update_SKU">{{ __('admin.product.SKU') }}</label>
                            <input type="text" class="form-control" id="update_SKU" name="SKU" readonly>
                            <div class="input-group-append" style="margin-top: 8px">
                                <button type="button" class="btn btn-secondary" id="updateGenerateSKU">Generate
                                    SKU</button>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="update_tags">{{ __('admin.product.product_tag') }}</label>
                            <input type="text" class="form-control" id="update_tags" data-role="tagsinput"
                                name="tags">
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.product.product_image') }}</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <div class="mb-4" id="image"></div>

                        <div class="form-group mb-4">
                            <label>{{ __('admin.product.category_id') }}</label>
                            <select name="category_id" id="selectEditCategory">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label>{{ __('admin.product.subcategory_id') }}</label>
                            <select name="subcategory_id" id="selectEditSubCategory"></select>
                        </div>

                        <div class="form-group mb-4">
                            <label>{{ __('admin.product.brand_id') }}</label>
                            <select name="brand_id" id="selectEditBrand"></select>
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.product.colors') }}</label>
                            <div id="color_section" class="df custom"></div>
                        </div>
                        <div class="form-group mb-4">
                            <label>{{ __('admin.product.sizes') }}</label>
                            <div id="size_section" class="df custom"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light"
                            data-dismiss="modal">{{ __('admin.common.close') }}</button>
                        <button type="submit" id="edit_product_btn"
                            class="btn btn-primary">{{ __('admin.common.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Gallery Modal -->
    <div class="modal fade" id="addGalleryModel" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.product.add_gallery') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="add_gallery_form" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="product_id" id="gallery_product_id">

                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label>{{ __('admin.product.product_image') }}</label>
                            <input type="file" name="images[]" class="form-control" multiple>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light"
                            data-dismiss="modal">{{ __('admin.common.close') }}</button>
                        <button type="submit" id="add_gallery_btn"
                            class="btn btn-primary">{{ __('admin.common.add') }}</button>
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

            selectSubcategory: "{{ __('admin.category.select_subcategory') }}",
            selectCategory: "{{ __('admin.category.select_category') }}",
            selectBrand: "{{ __('admin.brand.select_brand') }}",
            noSubcategories: "{{ __('admin.category.select_no_subcategory') }}"
        };
    </script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const $productDatatable = $("#product_datatable");

            const initDataTable = () => {
                if ($.fn.DataTable.isDataTable($productDatatable)) {
                    $productDatatable.DataTable().destroy();
                }

                const dataTable = $productDatatable.DataTable({
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
                        url: "{{ route('admin.product.index') }}",
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
                                return `
                                    <div class="form-check custom-checkbox ms-2">
                                        <input type="checkbox" class="form-check-input checkbox_ids" name="ids" required="" value="${full.id}">
                                        <label class="form-check-label"></label>
                                    </div>
                                `
                            }

                        },
                        {
                            data: "name",
                            name: "name",
                            render: function(data, type, full, meta) {
                                var maxLength = 24;
                                if (data.length > maxLength) {
                                    return data.substring(0, maxLength) + '...';
                                }
                                return data;
                            }
                        },
                        {
                            data: "quantity",
                            name: "quantity"
                        },
                        {
                            data: "sold_quantity",
                            name: "sold_quantity"
                        },
                        {
                            data: "SKU",
                            name: "SKU"
                        },
                        {
                            data: 'tags',
                            name: 'tags',
                            render: function(data, type, full, meta) {
                                if (data) {
                                    return data.split(',').map(tag =>
                                        `<span class="tag">${tag.trim()}</span>`).join('');
                                }
                                return data;
                            }
                        },
                        {
                            data: "price",
                            name: "price"
                        },
                        {
                            data: "discount",
                            name: "discount"
                        },
                        {
                            data: "image",
                            name: "image",
                            render: function(data, type, full, meta) {
                                const defaultImage = "{{ asset('/default.png') }}";
                                const imageUrl = data ?
                                    `{{ asset('/uploads/product/') }}/${data}` : defaultImage;
                                return `<img src="${imageUrl}" width="60">`;
                            }
                        },
                        {
                            data: "subcategory_name",
                            name: "subcategory_name"
                        },
                        {
                            data: "brand_name",
                            name: "brand_name"
                        },
                        {
                            data: 'colors',
                            name: 'colors',
                            render: function(data, type, row) {
                                if (!data) {
                                    return '';
                                }

                                return data.split(', ').map(color => {
                                    return `<span style="display: inline-block; width: 20px; height: 20px; background-color: ${color}; border: 1px solid #ccc; margin-right: 5px;"></span>`;
                                }).join('');
                            }
                        },
                        {
                            data: 'sizes',
                            name: 'sizes'
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

            initializeSelect2('#selectAddCategory');
            initializeSelect2('#selectAddSubcategory');
            initializeSelect2('#selectAddBrand');
            initializeSelect2('#selectEditCategory');
            initializeSelect2('#selectEditSubCategory');
            initializeSelect2('#selectEditBrand');

            // Initialize DataTable
            initDataTable();

            // Bind Slug Generator
            bindSlugGenerator("#add_name, #update_name", "#add_slug, #update_slug");

            $('#generateSKU').on('click', () => handleGenerateSKU('#SKU', '#generateSKU'));
            $('#updateGenerateSKU').on('click', () => handleGenerateSKU('#update_SKU', '#updateGenerateSKU'));

            const resetSkuInput = (modalSelector, skuSelector) => {
                $(modalSelector).on('shown.bs.modal', function() {
                    clickCount = 0;
                    $(skuSelector).prop('disabled', false);
                }).on('hidden.bs.modal', function() {
                    $(this).find('form').trigger('reset');
                    $(skuSelector).val('');
                });
            };

            resetSkuInput('#addProductModel', '#generateSKU');
            resetSkuInput('#editProductModel', '#updateGenerateSKU');

            $('#selectAddCategory').on('change', function() {
                const categoryId = $(this).val();
                const subcategorySelect = $('#selectAddSubcategory');

                loadSubCategory(categoryId, subcategorySelect);
            });

            $('#selectEditCategory').on('change', function() {
                const categoryId = $(this).val();
                const subcategorySelect = $('#selectEditSubCategory');

                loadSubCategory(categoryId, subcategorySelect);
            });

            $('#discount').on('input', function() {
                const input = $(this).val();
                if (input < 0 || input > 100) {
                    toastr.warning('Vui lòng nhập giá trị từ 0 đến 100');
                    $(this).val('0'); // Reset the input to 0 if the value is invalid
                }
            });

            // Xử lý biểu mẫu thêm sản phẩm
            setupAddHandler("#add_product_form", "#add_product_btn", "#addProductModel",
                "{{ route('admin.product.store') }}", initDataTable)

            // Nhận dữ liệu được kết xuất trên biểu mẫu danh mục chỉnh sửa
            $(document).on("click", ".editIcon", function(e) {
                e.preventDefault();
                const id = $(this).attr("id");

                $.ajax({
                    url: "{{ route('admin.product.edit') }}",
                    method: "GET",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    }
                }).done(function(data) {
                    // Cập nhật các trường trong modal
                    $('#product_id').val(data.product.id);
                    $("#update_name").val(data.product.name);
                    $("#update_slug").val(data.product.slug);
                    $("#update_quantity").val(data.product.quantity);
                    $("#update_price").val(data.product.price);
                    $("#update_discount").val(data.product.discount);
                    $("#update_SKU").val(data.product.SKU);
                    $('#update_tags').tagsinput('add', data.product.tags);

                    // Nếu có ảnh thì hiện thị
                    if (data.product.image) {
                        $('#image').html(
                            `<img src="{{ asset('uploads/product/${data.product.image}') }}" alt="Category Image" class="img-fluid img-thumbnail" width="100">`
                        );
                    } else {
                        $('#image').html('');
                    }

                    // Đẩy dữ liệu vào ckeditor
                    editEditorD.setData(data.product.description);
                    editEditorC.setData(data.product.content);

                    // Cập nhật thương hiệu
                    loadBrandsForEdit(data.brands, data.product.brand_id);

                    // Cập nhật màu sắc và kích thước
                    loadColorsForEdit(data.colors, data.product.colors);
                    loadSizesForEdit(data.sizes, data.product.sizes);
                });
            });

            // Xử lý biểu mẫu cập nhật sản phẩm
            setupEditHandler("#edit_product_form", "#edit_product_btn", "#editProductModel",
                "{{ route('admin.product.update') }}", initDataTable)

            // Xử lý xóa một sản phẩm
            setupDeleteHandler(".deleteIcon", "{{ route('admin.product.delete') }}", initDataTable)

            // Xử lý việc xóa nhiều sản phẩm
            setupDeleteMultipleHandler("#deleteMultiple", "{{ route('admin.product.delete.all') }}",
                initDataTable)

            // Xử lý việc khôi phục sản phẩm
            setupRestoreHandler(".restoreIcon", "{{ route('admin.product.restore') }}", initDataTable)

            // Xử lý phục hồi đa sản phẩm
            setupRestoreAllHandler("#restoreAll", "{{ route('admin.product.restore.all') }}", initDataTable)

            // Xử lý xóa vĩnh viễn một sản phẩm
            setupForceHandler(".forceIcon", "{{ route('admin.product.force.delete') }}", initDataTable)

            // Xử lý xóa vĩnh viễn nhiều sản phẩm
            setupForceMultipleHandler("#forceDeleteMultiple", "{{ route('admin.product.force.delete.all') }}",
                initDataTable)

            // -------------------- Gallery ---------------------
            let productId;

            $(document).on('change', '.gallery-checkbox', function(e) {
                e.preventDefault();

                productId = $(this).data('product_id');
                var index = $(this).closest('tr').index();
                var checkboxes = $(this).closest('tbody').find('.gallery-checkbox');
                var isChecked = $(this).is(':checked');

                checkboxes.each(function(i) {
                    if (i !== index) {
                        $(this).prop('disabled', isChecked);
                    }
                });

                if (isChecked) {
                    $(".gallery-wrapper").show();
                    $("#gallery_product_id").val(productId);
                    loadGalleryByProductId(productId);
                } else {
                    $(".gallery-wrapper").hide();
                }
            });

            $(document).on('click', '#checkAllGallery', function() {
                $('.checkbox_gallery_ids').prop('checked', this.checked);
            });

            $(document).on('change', '.checkbox_gallery_ids', function() {
                if ($('.checkbox_gallery_ids:checked').length === $('.checkbox_gallery_ids').length) {
                    $('#checkAllGallery').prop('checked', true);
                } else {
                    $('#checkAllGallery').prop('checked', false);
                }
            });

            $('#includeGalleryCheckboxTrash').change(handleChangeCheckboxGallery);

            function handleChangeCheckboxGallery() {
                const $checkbox = $(this);
                const checked = $checkbox.is(":checked");
                const elementsToShow = checked ? ['#restoreGalleryAll', '#forceDeleteGalleryMultiple'] : [
                    '#deleteGalleryMultiple'
                ];
                const elementsToHide = checked ? ['#deleteGalleryMultiple'] : ['#restoreGalleryAll',
                    '#forceDeleteGalleryMultiple'
                ];

                toggleElements(elementsToShow, elementsToHide)
                loadGalleryByProductId(productId);
            }

            // Xử lý biểu mẫu thêm thư viện ảnh
            setupAddHandler("#add_gallery_form", "#add_gallery_btn", "#addGalleryModel",
                "{{ route('admin.gallery.store') }}",
                function() {
                    loadGalleryByProductId(productId);
                })

            // Xử lý việc xóa thư viện ảnh
            setupDeleteHandler(".deleteGallery", "{{ route('admin.gallery.delete') }}", function() {
                loadGalleryByProductId(productId);
            })

            // Xử lý việc xóa nhiều thư viện ảnh
            setupDeleteMultipleHandler("#deleteGalleryMultiple", "{{ route('admin.gallery.delete.all') }}",
                function() {
                    loadGalleryByProductId(productId);
                })

            // Xử lý việc khôi phục danh mục con
            setupRestoreHandler(".restoreGallery", "{{ route('admin.gallery.restore') }}", function() {
                loadGalleryByProductId(productId);
            })

            // Xử lý khôi phục nhiều thư viện ảnh
            setupRestoreAllHandler("#restoreGalleryAll", "{{ route('admin.gallery.restore.all') }}", function() {
                loadGalleryByProductId(productId);
            })

            // Xử lý xóa vĩnh viễn thư viện ảnh
            setupForceHandler(".forceGallery", "{{ route('admin.gallery.force.delete') }}", function() {
                loadGalleryByProductId(productId);
            })

            // Xử lý xóa vĩnh viễn nhiều thư viện ảnh
            setupForceMultipleHandler("#forceDeleteGalleryMultiple",
                "{{ route('admin.gallery.force.delete.all') }}",
                function() {
                    loadGalleryByProductId(productId);
                })
        });

        document.addEventListener("DOMContentLoaded", function() {
            const editorConfigs = [{
                    selector: "#add_description",
                    placeholder: "Enter Description",
                    variable: "addEditorD"
                },
                {
                    selector: "#add_content",
                    placeholder: "Enter Content",
                    variable: "addEditorC"
                },
                {
                    selector: "#edit_description",
                    placeholder: "Enter Description",
                    variable: "editEditorD"
                },
                {
                    selector: "#edit_content",
                    placeholder: "Enter Content",
                    variable: "editEditorC"
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

        let clickCount = 0;

        // Hàm nạp thư viện ảnh theo mã sản phẩm
        function loadGalleryByProductId(productId) {
            $.ajax({
                url: "{{ route('admin.gallery.index') }}",
                type: 'GET',
                data: {
                    id: productId,
                    include_trashed: $('#includeGalleryCheckboxTrash').is(':checked') ? 1 : 0,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    $("#gallery-content").html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Request failed. Status:', xhr.status, 'Error:', error);
                }
            });
        }

        // Hàm tạo tự động mã SKU
        function handleGenerateSKU(selector, btnSelector) {
            let maxClicks = 5;

            if (clickCount < maxClicks) {
                const firstChar = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                const randomChar = firstChar.charAt(Math.floor(Math.random() * firstChar.length));
                const randomNumber = Math.floor(100000 + Math.random() * 900000);
                const sku = randomChar + randomNumber;
                $(selector).val(sku);

                clickCount++;

                if (clickCount >= maxClicks) {
                    $(btnSelector).prop('disabled', true);
                    toastr.error('Tối đa 5 lần tạo mã SKU')
                }
            }
        }

        // Hàm nạp danh mục con
        function loadSubCategory(categoryId, subcategorySelect) {
            // Xóa các lựa chọn hiện tại
            subcategorySelect.html('<option value="">' + window.translations.selectSubcategory + '</option>');

            if (categoryId) {
                $.ajax({
                    url: "{{ route('admin.product.load.subcategory') }}",
                    type: 'GET',
                    data: {
                        id: categoryId,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        if (data.subcategories.length > 0) {
                            $.each(data.subcategories, function(index, subcategory) {
                                subcategorySelect.append('<option value="' + subcategory
                                    .id +
                                    '" >' +
                                    subcategory.name + '</option>');
                            });
                        } else {
                            subcategorySelect.html('<option value="">' + window.translations
                                .noSubcategories + '</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Request failed. Status:', xhr.status, 'Error:', error);
                    }
                });
            }
        }

        // Hàm nạp thương hiệu trong phần chỉnh sửa sản phẩm
        function loadBrandsForEdit(selectedBrand, productBrandId) {
            $('#selectEditBrand').empty();
            selectedBrand.forEach(function(brand) {
                $('#selectEditBrand').append(new Option(brand.name, brand.id, false,
                    brand.id == productBrandId));
            });
        }

        // Hàm nạp kích thước trong phần chỉnh sửa sản phẩm
        function loadSizesForEdit(selectedSizes, productSizes) {
            $('#size_section').empty();
            selectedSizes.forEach(function(size) {
                var size_checked = Array.isArray(productSizes) && productSizes.some(
                    productSize => productSize.id == size.id) ? 'checked' : '';
                $('#size_section').append(`
                        <div class="df align-items-center mr-8">
                            <input type="checkbox" class="mr-1" name="size_ids[]" value="${size.id}" id="size_${size.id}" ${size_checked}>
                            <label for="size_${size.id}" class="mb-0">${size.name}</label>
                        </div>
                    `);
            });
        }

        // Hàm nạp màu sắc trong phần chỉnh sửa sản phẩm
        function loadColorsForEdit(selectedColors, productColors) {
            $('#color_section').empty();
            selectedColors.forEach(function(color) {
                var color_checked = Array.isArray(productColors) && productColors.some(
                        productColor => productColor.id == color.id) ? 'checked' :
                    '';
                $('#color_section').append(`
                        <div class="df align-items-center mr-8">
                            <input type="checkbox" class="mr-1" name="color_ids[]" value="${color.id}" id="color_${color.id}" ${color_checked}>
                            <label for="color_${color.id}" class="mb-0">${color.name}</label>
                        </div>
                    `);
            });
        }
    </script>
@endpush
