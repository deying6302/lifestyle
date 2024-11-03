// Chọn tất cả hộp kiểm
$(document).on("click", "#checkAll", function () {
    $(".checkbox_ids").prop("checked", $(this).prop("checked"));
});

function removeVietnameseAccents(str) {
    const accentsMap = {
        'a': 'àáảãạâầấẩẫậăằắẳẵặ',
        'e': 'èéẻẽẹêềếểễệ',
        'i': 'ìíỉĩị',
        'o': 'òóỏõọôồốổỗộơờớởỡợ',
        'u': 'ùúủũụưừứửữự',
        'y': 'ỳýỷỹỵ',
        'd': 'đ',
        'A': 'ÀÁẢÃẠÂẦẤẨẪẬĂẰẮẲẴẶ',
        'E': 'ÈÉẺẼẸÊỀẾỂỄỆ',
        'I': 'ÌÍỈĨỊ',
        'O': 'ÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢ',
        'U': 'ÙÚỦŨỤƯỪỨỬỮỰ',
        'Y': 'ỲÝỶỸỴ',
        'D': 'Đ'
    };

    for (const [nonAccent, accents] of Object.entries(accentsMap)) {
        for (const accent of accents) {
            str = str.replace(new RegExp(accent, 'g'), nonAccent);
        }
    }
    return str;
}

function generateSlug(text) {
    return removeVietnameseAccents(text)
        .toLowerCase()               // Chuyển thành chữ thường
        .trim()                      // Loại bỏ khoảng trắng ở đầu và cuối
        .replace(/\s+/g, '-')        // Thay thế khoảng trắng bằng dấu gạch ngang
        .replace(/[^a-zA-Z0-9-]/g, '') // Loại bỏ các ký tự không hợp lệ
}

// Function to bind slug generator
function bindSlugGenerator(inputSelector, outputSelector) {
    $(inputSelector).on("input", function () {
        const inputValue = $(this).val();
        const slugValue = generateSlug(inputValue);
        $(outputSelector).val(slugValue);
    });
}

// Hàm Debounce
function debounce(func, wait, immediate) {
    let timeout;
    return function () {
        const context = this,
            args = arguments;
        const later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

// Khởi tạo hàm select2
function initializeSelect2(selector) {
    $(selector).select2({
        theme: "bootstrap4",
        width: "style",
        placeholder: $(selector).attr("placeholder"),
        allowClear: Boolean($(selector).data("allow-clear")),
    });
}

function toggleElements(elementsToShow, elementsToHide) {
    elementsToShow.forEach((selector) => $(selector).show());
    elementsToHide.forEach((selector) => $(selector).hide());
}

function setupAddHandler(
    addFormClass,
    addButtonClass,
    addModal,
    addRoute,
    callback
) {
    $(document).on("submit", addFormClass, function (e) {
        e.preventDefault();

        const fd = new FormData(this);
        $(addButtonClass).text(window.translations.adding);

        $.ajax({
            url: addRoute,
            method: "POST",
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
        }).done(function (res) {
            if (res.status == 200) {
                toastr.success(res.message);
                callback();

                $(addButtonClass).text(window.translations.add);
                $(addFormClass)[0].reset();
                $(addModal).modal("hide");
            } else {
                toastr.error(res.message);
            }
        });
    });
}

function setupEditHandler(
    editFormClass,
    editButtonClass,
    editModal,
    editRoute,
    callback
) {
    $(document).on("submit", editFormClass, function (e) {
        e.preventDefault();

        const csrfToken = $('meta[name="csrf-token"]').attr("content");
        const fd = new FormData(this);

        fd.append("_method", "PUT");

        $(editButtonClass).text(window.translations.updating);

        $.ajax({
            url: editRoute,
            type: "POST",
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            dataType: "json",
        }).done(function (res) {
            if (res.status == 200) {
                toastr.success(res.message);
                callback();

                $(editButtonClass).text(window.translations.update);
                $(editFormClass)[0].reset();
                $(editModal).modal("hide");
            } else {
                toastr.error(res.message);
            }
        });
    });
}

function setupDeleteHandler(deleteButtonClass, deleteRoute, callback) {
    $(document).on("click", deleteButtonClass, function (e) {
        e.preventDefault();

        // Lấy ID của mục cần xóa
        const id = $(this).attr("id");

        // Lấy mã thông báo CSRF từ thẻ meta
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        // Hiển thị hộp thoại xác nhận
        swal(
            {
                title: window.translations.title,
                text: window.translations.delete_text,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: window.translations.confirmText,
                cancelButtonText: window.translations.cancelText,
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function (isConfirm) {
                // Nếu người dùng xác nhận xóa
                if (isConfirm) {
                    $.ajax({
                        url: deleteRoute,
                        method: "DELETE",
                        data: {
                            id: id,
                        },
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        dataType: "json",
                    }).done(function (res) {
                        // Nếu xóa thành công
                        if (res.status == 200) {
                            swal(
                                window.translations.deleted,
                                res.message,
                                "success"
                            );
                            callback();
                        } else {
                            // Nếu việc xóa không thành công
                            swal(
                                window.translations.errors,
                                res.message,
                                "error"
                            );
                        }
                    });
                } else {
                    // Nếu người dùng hủy việc xóa
                    swal(
                        window.translations.cancelled,
                        window.translations.cancel,
                        "warning"
                    );
                }
            }
        );
    });
}

function setupDeleteMultipleHandler(
    deleteMultipleClass,
    deleteMultipleRoute,
    callback
) {
    $(document).on("click", deleteMultipleClass, function (e) {
        e.preventDefault();

        var checked = $(this).prop("checked");
        var checkboxes = $(".checkbox_ids");

        if (!checked && checkboxes.filter(":checked").length === 0) {
            // Nếu không có checkbox nào được chọn và checkbox #checkAll cũng không được chọn
            toastr.error(window.translations.checkbox_delete);
            return; // Ngăn chặn việc thực hiện Ajax nếu không có checkbox nào được chọn
        }

        // Nếu có ít nhất một checkbox được chọn hoặc checkbox #checkAll được chọn
        var ids = [];

        checkboxes.each(function () {
            if ($(this).prop("checked")) {
                ids.push($(this).val()); // Lấy giá trị của các checkbox đã được chọn
            }
        });

        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        swal(
            {
                title: window.translations.title,
                text: window.translations.delete_all_text,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: window.translations.confirmText,
                cancelButtonText: window.translations.cancelText,
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: deleteMultipleRoute,
                        method: "DELETE",
                        data: {
                            ids: ids,
                        },
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        dataType: "json",
                    }).done(function (res) {
                        if (res.status == 200) {
                            swal(
                                window.translations.deleted,
                                res.message,
                                "success"
                            );
                            $("#checkAll").prop("checked", false);
                            callback();
                        } else {
                            swal(
                                window.translations.errors,
                                res.message,
                                "error"
                            );
                        }
                    });
                } else {
                    swal(
                        window.translations.cancelled,
                        window.translations.cancel_all,
                        "warning"
                    );
                }
            }
        );
    });
}

function setupRestoreHandler(restoreButtonClass, restoreRoute, callback) {
    $(document).on("click", restoreButtonClass, function (e) {
        e.preventDefault();

        const id = $(this).attr("id");
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        $.ajax({
            url: restoreRoute,
            method: "POST",
            data: {
                id: id,
            },
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            dataType: "json",
        }).done(function (res) {
            if (res.status == 200) {
                toastr.success(res.message);
                callback();
            } else {
                toastr.error(res.message);
            }
        });
    });
}

function setupRestoreAllHandler(restoreAllClass, restoreAllRoute, callback) {
    $(document).on("click", restoreAllClass, function (e) {
        e.preventDefault();
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        $.ajax({
            url: restoreAllRoute,
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            dataType: "json",
        }).done(function (res) {
            if (res.status == 200) {
                toastr.success(res.message);
                $("#checkAll").prop("checked", false);
                callback();
            } else {
                toastr.error(res.message);
            }
        });
    });
}

function setupForceHandler(forceButtonClass, forceRoute, callback) {
    $(document).on("click", forceButtonClass, function (e) {
        e.preventDefault();
        const id = $(this).attr("id");
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        swal(
            {
                title: window.translations.title,
                text: window.translations.force_delete_text,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: window.translations.confirmText,
                cancelButtonText: window.translations.cancelText,
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: forceRoute,
                        method: "DELETE",
                        data: {
                            id: id,
                        },
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        dataType: "json",
                    }).done(function (res) {
                        if (res.status == 200) {
                            swal(
                                window.translations.deleted,
                                res.message,
                                "success"
                            );
                            callback();
                        } else {
                            swal(
                                window.translations.errors,
                                res.message,
                                "error"
                            );
                        }
                    });
                } else {
                    swal(
                        window.translations.cancelled,
                        window.translations.cancel,
                        "warning"
                    );
                }
            }
        );
    });
}

function setupForceMultipleHandler(
    forceMultipleClass,
    forceMultipleRoute,
    callback
) {
    $(document).on("click", forceMultipleClass, function (e) {
        e.preventDefault();

        var checked = $(this).prop("checked");
        var checkboxes = $(".checkbox_ids");

        if (!checked && checkboxes.filter(":checked").length === 0) {
            // Nếu không có checkbox nào được chọn và checkbox #checkAll cũng không được chọn
            toastr.error(window.translations.checkbox_delete);
            // Ngăn chặn việc thực hiện Ajax nếu không có checkbox nào được chọn
            return;
        }

        // Nếu có ít nhất một checkbox được chọn hoặc checkbox #checkAll được chọn
        var ids = [];
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        checkboxes.each(function () {
            if ($(this).prop("checked")) {
                ids.push($(this).val()); // Lấy giá trị của các checkbox đã được chọn
            }
        });

        swal(
            {
                title: window.translations.title,
                text: window.translations.force_delete_all_text,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: window.translations.confirmText,
                cancelButtonText: window.translations.cancelText,
                closeOnConfirm: false,
                closeOnCancel: false,
            },
            function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: forceMultipleRoute,
                        method: "DELETE",
                        data: {
                            ids: ids,
                        },
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        dataType: "json",
                    }).done(function (res) {
                        if (res.status == 200) {
                            swal(
                                window.translations.deleted,
                                res.message,
                                "success"
                            );
                            $("#checkAll").prop("checked", false);
                            callback();
                        } else {
                            swal(
                                window.translations.errors,
                                res.message,
                                "error"
                            );
                        }
                    });
                } else {
                    swal(
                        window.translations.cancelled,
                        window.translations.cancel_all,
                        "warning"
                    );
                }
            }
        );
    });
}

function setupStatusHandler(statusButtonClass, statusRoute, callback) {
    $(document).on("click", statusButtonClass, function (e) {
        e.preventDefault();

        const id = $(this).attr("id");
        var currentStatus = $(this).hasClass("btn-success") ? 1 : 0;
        var newStatus = currentStatus === 1 ? 0 : 1;
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        $.ajax({
            url: statusRoute,
            method: "POST",
            data: {
                id: id,
                new_status: newStatus,
            },
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
        }).done(function (res) {
            if (res.status == 200) {
                if (newStatus === 1) {
                    $("#" + id)
                        .removeClass("btn-dark")
                        .addClass("btn-success");
                } else {
                    $("#" + id)
                        .removeClass("btn-success")
                        .addClass("btn-dark");
                }

                toastr.success(res.message);
                callback();
            } else {
                toastr.error(res.message);
            }
        });
    });
}
