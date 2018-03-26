$('.alert').delay(10000).slideUp(300);
$(".dropdown").collapse("hide");
/* swap open/close side menu icons */
$('[data-toggle=collapse]').click(function () {
    // toggle icon
    $(this).find("i").toggleClass("glyphicon-menu-right glyphicon-menu-down");
});
if (typeof post_page === 'undefined') {
    var post_page = false;
}
$(document).ready(function () {

    if (document.querySelector('#add_file_btn') !== null) {
        $('#add_file_btn').show();
    }
    $('.close').click(function () {
        $('.alert').hide();
    });
});


function _delete(_url) {


    swal({
            title: "آیا برای حذف مطمئنید؟",
            text: "",
            type: "warning",
            showCancelButton: true,
            animation: 'slide-from-top',
            cancelButtonText: "لغو",
            confirmButtonColor: "#F24646",
            confirmButtonText: "حذف",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {
            window.location.href = _url;
        });
}

function _status(_url, obj) {

    var title = obj.title;
    var message = null;
    var btn_text = null;
    var btn_color = null;
    switch (title) {
        case 'فعال' :
            message = 'آیا برای غیرفعال کردن مطمئنید؟';
            btn_text = 'غیرفعال کردن';
            btn_color = '#dd1111';
            break;
        case  'غیرفعال':
            message = 'آیا برای فعال کردن مطمئنید؟';
            btn_text = 'فعال کردن';
            btn_color = '#98d84e';
            break;
    }

    swal({
            title: message,
            text: "",
            type: "warning",
            showCancelButton: true,
            animation: 'slide-from-top',
            cancelButtonText: "لغو",
            confirmButtonColor: btn_color,
            confirmButtonText: btn_text,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {
            window.location.href = _url;
        });
}


function _publish(_url, obj) {

    var title = obj.title;
    var message = null;
    var btn_text = null;
    var btn_color = null;
    switch (title) {
        case 'انتشار' :
            message = 'آیا برای تغییر وضعیت به پیش نویس مطمئنید؟';
            btn_text = 'پیش نویس';
            btn_color = '#bae4f1';
            break;
        case  'پیش نویس':
            message = 'آیا برای تغییر وضعیت به انتشار مطمئنید؟';
            btn_text = 'انتشار';
            btn_color = '#98d84e';
            break;
    }

    swal({
            title: message,
            text: "",
            type: "warning",
            showCancelButton: true,
            animation: 'slide-from-top',
            cancelButtonText: "لغو",
            confirmButtonColor: btn_color,
            confirmButtonText: btn_text,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {
            window.location.href = _url;
        });
}

function _confirm(_url, obj) {

    var title = obj.title;
    var message = null;
    var btn_text = null;
    var btn_color = null;
    switch (title) {
        case 'نمایش بده' :
            message = 'آیا برای نمایش نظر مطمئنید؟';
            btn_text = 'نمایش نظر';
            btn_color = '#98d84e';
            break;
        case  'نمایش نده':
            message = 'آیا برای عدم نمایش نظر مطمئنید؟';
            btn_text = 'عدم نمایش نظر';
            btn_color = '#68c4d8';
            break;
    }

    swal({
            title: message,
            text: "",
            type: "warning",
            showCancelButton: true,
            animation: 'slide-from-top',
            cancelButtonText: "لغو",
            confirmButtonColor: btn_color,
            confirmButtonText: btn_text,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {
            window.location.href = _url;
        });
}


function init() {

    if (document.querySelector('.drag-item') === null) {
        return;
    }
    $('.drag-item').draggable({
        containment: '.main',
        cursor: 'move',
        stack: '.drag-item',
        revert: true,
        start: function () {
            $(this).css({'opacity': 0.4});
        },
        stop: function () {
            $(this).css({'opacity': 1});
        }

    });
    $('.drop-item').droppable({
        drop: handleDropEvent
    });
}

function handleDropEvent(event, ui) {
    var draggable = ui.draggable;
    var source_path = draggable.attr('data-path');
    var target_path = $(this).data('path');
    var source_type = draggable.attr('data-type');
    var target_type = $(this).data('type');
    var _current_path = $('#path').val();

    if (target_type === 'folder') {
        $.ajax({
            url: $(this).data('url'),
            type: "POST",
            data: {
                'source_path': source_path,
                'source_type': source_type,
                'target_path': target_path,
                'target_type': target_type,
                'current_path': _current_path
            },
            success: function (data, textStatus, jqXHR) {
                //data - response from server

                data = data.trim();
                if (data.length != 0) {
                    if ($.isEmptyObject(data)) {
                        return;
                    }
                    data = JSON.parse(data);
                    if (data.error == 'yes') {
                        alert(data.text);
                        return;
                    }

                    $('.content').empty();
                    $('.content').html(data.text);


                }


            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus.toString());
            },

            beforeSend: function () {
                // setting a timeout
                $('.main').css({'opacity': '0.3'});
                $('.address').css({'opacity': '0.3'});


            },

            complete: function () {

                if (post_page) {
                    checkboxFileManager();
                }
                $(init);
            },

        });
    }


}

$(init);

$('#folder_name').keypress(function (event) {

    if (event.keyCode == 13) {
        $('#save_folder_btn').click();
    }
});


function open_dir(obj) {
    var _path = $(obj).attr('data-path');
    var _url = $(obj).data('url');
    $.ajax({
        url: _url,
        type: "POST",
        data: {
            'path': _path,
        },
        success: function (data, textStatus, jqXHR) {
            //data - response from server
            data = data.trim();
            if (data.length != 0) {
                $('.content').empty();
                $('.content').html(data);
            }


        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus.toString());
        },

        beforeSend: function () {
            // setting a timeout
            $('.main').css({'opacity': '0.3'});
            $('.address').css({'opacity': '0.3'});


        },

        complete: function () {
            if (post_page) {
                checkboxFileManager();
            }
            $(init);
        },

    });
}

function show_folder_modal(obj) {
    $('#createFolderModal').modal('show');
    $(this).on('shown.bs.modal', function () {
        $('#folder_name').focus();
    });
}


function create_folder(obj) {

    var _name = $('#folder_name').val();
    if ($.isEmptyObject(_name)) {
        $("#create_message").addClass("text-danger").removeClass('text-success');
        $("#create_message").html('Enter Folder Name!').delay(5000).fadeOut();
        $("#create_message").removeClass('*');
        $("#create_message").show();
        $('#folder_name').focus();
        return;
    }
    var _path = $('#path').val();
    var _url = $(obj).data('url');

    $.ajax({
        url: _url,
        type: "POST",
        data: {
            'path': _path,
            'name': _name
        },
        success: function (data, textStatus, jqXHR) {
            //data - response from server

            data = data.trim();
            if (data.length != 0) {
                if ($.isEmptyObject(data)) {
                    return;
                }
                data = JSON.parse(data);
                if (data.error == 'yes') {
                    $("#create_message").addClass("text-danger").removeClass('text-success');
                    $("#create_message").html(data.text).delay(5000).fadeOut();

                    return;
                }

                $('.content').empty();
                $('.content').html(data.text);
                $("#create_message").addClass("text-success").removeClass('text-danger');
                $("#create_message").html('Folder Created Successfully').delay(5000).fadeOut();

            }


        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus.toString());
        },

        beforeSend: function () {
            // setting a timeout

        },

        complete: function () {


            $(init);
            $("#create_message").removeClass('*');
            $("#create_message").show();
            $('#folder_name').val('');
            $('#folder_name').focus();
        },

    });
}

function show_rename_modal(obj) {
    $('#name').val($(obj).attr('data-filename'));
    $('#name').select();
    $('#renameModal').modal('show');
    $(this).on('shown.bs.modal', function () {
        $('#name').focus();
        $('#type').val($(obj).attr('data-type'));
        $('#rename_path').val($(obj).attr('data-path'));

    });
}


function rename(obj) {

    var _name = $('#name').val();
    var _type = $('#type').val();
    var _path = $('#rename_path').val();
    var _url = $(obj).data('url');
    if ($.isEmptyObject(_name)) {
        alert('Enter Name!');
        return;
    }

    $.ajax({
        url: _url,
        type: "POST",
        data: {
            'path': _path,
            'name': _name,
            'type': _type
        },
        success: function (data, textStatus, jqXHR) {
            //data - response from server

            data = data.trim();
            /* console.log(data);
             return ;*/
            if (data.length != 0) {
                if ($.isEmptyObject(data)) {
                    return;
                }
                data = JSON.parse(data);
                if (data.error == 'yes') {
                    alert(data.text);
                    return;
                }
                $('#renameModal').modal('hide');
                $('.modal-backdrop').fadeOut(300);
                $('.content').empty();
                $('.content').html(data.text);
            }


        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus.toString());
        },

        beforeSend: function () {
            // setting a timeout

        },

        complete: function () {

            $(init);
        },

    });
}


function open_upload_modal(obj) {
    $('#uploadModal').modal('show');

}

function do_upload(obj) {
    if ($.isEmptyObject($("#file1").val())) {
        $("#upload_message").addClass("text-danger").removeClass('text-success');
        $("#upload_message").html('Please, Select File!').delay(5000).fadeOut();
        return;
    }
    var _url = $(obj).attr('data-url');
    var formData = new FormData($('#upload_form')[0]);
    formData.append('path', $('#path').val());
    formData.append('file', $('input[type=file]')[0].files[0]);

    $.ajax({
        url: _url,
        type: "POST",
        data: formData,
        mimeTypes: "multipart/form-data",
        contentType: false,
        cache: false,
        processData: false,
        success: function (data, textStatus, jqXHR) {
            //data - response from server

            data = data.trim();
            if (data.length != 0) {
                if ($.isEmptyObject(data)) {
                    return;
                }
                data = JSON.parse(data);

                if (data.error == 'yes') {
                    $("#upload_message").addClass("text-danger").removeClass('text-success');
                    $("#upload_message").html(data.text).delay(5000).fadeOut();
                    return;
                }

                $('.content').empty();
                $('.content').html(data.text);
                $("#upload_message").addClass("text-success").removeClass('text-danger');
                $("#upload_message").html('File Uploaded Successfully').delay(5000).fadeOut();


            }


        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus.toString());
        },

        beforeSend: function () {
            // setting a timeout

        },

        complete: function () {

            $(init);
            $("#upload_message").removeClass('*');
            $("#file1").val('');
            $("#upload_message").show();
        }
    });

}

function file_delete(obj) {

    $.confirm({
        title: 'Do you want to delete?',
        titleClass: 'confirm-js-title',
        content: null,
        containerFluid: true,
        type: 'red',
        useBootstrap: true,
        rtl: false,
        buttons: {
            confirm: {
                btnClass: 'btn-primary',
                action: function () {
                    var _file = $(obj).data('path');
                    var _type = $(obj).data('type');
                    var _path = $('#path').val();
                    var _url = $(obj).data('url');


                    $.ajax({
                        url: _url,
                        type: "POST",
                        data: {
                            'path': _path,
                            'file': _file,
                            'type': _type
                        },
                        success: function (data, textStatus, jqXHR) {
                            //data - response from server

                            data = data.trim();

                            if (data.length != 0) {
                                if ($.isEmptyObject(data)) {
                                    return;
                                }
                                data = JSON.parse(data);
                                if (data.error == 'yes') {
                                    alert(data.text);
                                    return;
                                }

                                $('.content').empty();
                                $('.content').html(data.text);


                            }


                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(textStatus.toString());
                        },

                        beforeSend: function () {
                            // setting a timeout

                        },

                        complete: function () {

                            $(init);
                        },

                    });
                }
            },
            cancel: function () {

            }

        }
    });


}


function show_view_modal(obj) {
    var _path = $(obj).data('path');
    var _url = $(obj).data('url');
    var _file_url = $(obj).attr('data-file-url');
    $('#viewModal').modal('show');
    $(this).on('shown.bs.modal', function () {
        $('#edit_btn').data('path', _path);
    });
    $.ajax({
        url: _url,
        type: "POST",
        data: {
            'path': _path,
        },
        success: function (data, textStatus, jqXHR) {
            //data - response from server

            data = data.trim();
            if (data.length != 0) {
                if ($.isEmptyObject(data)) {
                    return;
                }
                data = JSON.parse(data);
                if (data.error == 'yes') {
                    alert(data.text);
                    return;
                }
                $('#file_content').empty();
                $('#file_content').html(data.text);


            }


        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus.toString());
        },

        beforeSend: function () {
            // setting a timeout

        },

        complete: function () {

            $(init);

        },

    });

}

function file_edit(obj) {
    var _path = $(obj).data('path');
    var _url = $(obj).data('url');
    var _file_content = $('#file_content').val();

    $.ajax({
        url: _url,
        type: "POST",
        data: {
            'path': _path,
            'file_content': _file_content
        },
        success: function (data, textStatus, jqXHR) {
            //data - response from server

            data = data.trim();

            if (data.length != 0) {
                if ($.isEmptyObject(data)) {
                    return;
                }
                data = JSON.parse(data);
                if (data.error == 'yes') {
                    $("#edit_message").addClass("text-danger").removeClass('text-success');
                    $("#edit_message").html(data.text).delay(5000).fadeOut();
                    return;
                }

                $('#file_content').empty();
                $('#file_content').html(data.text);
                $("#edit_message").addClass("text-success").removeClass('text-danger');
                $("#edit_message").html('File Saved Successfully').delay(5000).fadeOut();

            }


        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus.toString());
        },

        beforeSend: function () {
            // setting a timeout

        },

        complete: function () {

            $(init);
            $("#edit_message").removeClass('*');
            $("#edit_message").show();
        },

    });


}

$("#viewModal").on("hidden.bs.modal", function () {
    $('#file_content').html('');
});

function show_info_modal(obj) {

    var _url = $(obj).data('url');
    var _type = $(obj).data('type');
    var _path = $(obj).data('path');
    var _filename = $(obj).data('filename');
    var _size = $(obj).data('size');
    $('#infoModal').modal('show');
    $(this).on('shown.bs.modal', function () {
        $('#info_name').val(_filename);
        $('#info_physical_path').val(_path);
        $('#info_website_url').val(_url);
        $('#info_size').val(_size);
    });
}

$("#infoModal").on("hidden.bs.modal", function () {
    $('#info_name').val('');
    $('#info_physical_path').val('');
    $('#info_website_url').val('');
    $('#info_size').val('');
});

function copy_to_clipboard(obj) {
    var copyText = document.getElementById("info_website_url");
    copyText.select();
    document.execCommand("Copy");
    $(obj).effect("shake", {direction: "left", times: 3, distance: 3}, 500);
}

function file_download(obj) {
    var _url = $(obj).data('url');
    var _path = $(obj).data('path');
    window.location.href = _url + '?path=' + _path;
}

function checkboxFileManager() {
    var i = 100;
    $("div.well[data-type='file']").each(function (e) {
        var attr = $(this).attr('for');
        if (typeof attr !== typeof undefined && attr !== false) {
            return true;
        }
        var _url = $(".text-info", this).attr('data-url');
        var _file = 'file';
        if ($("img.file-img", this).length) {
            var _file = 'image';
        }
        var _checkbox = '<span class="selector fa  fa-square" data-check="false" id="file-' + i + '" data-url="' + _url + '" data-type="' + _file + '"></span>';
        $(this).attr("for", 'file-' + i);
        $(this).prepend(_checkbox);
        $(this).on('click', function () {

            var $_checkbox = $("span.selector#" + $(this).attr('for'));
            if ($_checkbox.attr('data-check') == 'false') {
                $_checkbox.attr('data-check', 'true');
                $_checkbox.removeClass('fa-square').addClass('fa-check-square');
            } else {
                $_checkbox.attr('data-check', 'false');
                $_checkbox.removeClass('fa-check-square').addClass('fa-square');
            }
        });
        i++;
    });
}

function show_filemanager_modal(obj) {

    $("#createFolderModal").on('hide.bs.modal', function () {
        checkboxFileManager();
    });

    $("#uploadModal").on('hide.bs.modal', function () {
        checkboxFileManager();
    });

    $("#filemanagerModal").on('show.bs.modal', function () {
        checkboxFileManager();
    });
    $('#filemanagerModal').modal('show');

    $("#addToEditor").on("click", function () {
        var item = 0;
        $("div.well[data-type='file'] span.selector").each(function (e) {
            if ($(this).attr('data-check') == 'true') {
                if ($(this).attr('data-type') === 'image') {
                    add_to_editor('body', '<img src="' + $(this).attr('data-url') + '" style="height:200px; width:200px">')
                } else {
                    add_to_editor('body', '<a href="' + $(this).attr('data-url') + '">file</a>')
                }
                $(this).attr('data-check', 'false');
                $(this).removeClass('fa-check-square').addClass('fa-square');
                item++;
            }
        });

        if (item > 0) {
            $('#filemanagerModal').modal('hide');
        }
    });


}

function add_to_editor(instance, html) {
    CKEDITOR.instances[instance].insertHtml(html);
}
