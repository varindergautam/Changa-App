var blockUImessage = $('<div class="blockui-message bg-info"><div class="uil-ring-css"  style="transform:scale(0.38);"><div></div></div> <span class="display-block">Just a sec &hellip; processing request</span></div>');

function getAjax(url, data, callback) {

    if (callback != undefined) {
        blockUI("body");
    }
    var xhr = $.ajax({
        url: url,
        dataType: 'html',
        type: 'GET',
        data: data,
        success: function (returndata, textStatus, XMLHttpRequest) {
            unblockUI("body", 300);
            if (callback != undefined && typeof callback === 'function') {
                callback(returndata);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            unblockUI("body", 500);
            console.log(errorThrown);
        }
    });
}

function postMultipartAjax(url, form, callback, callback_error, args, modelForm,showExp=true) {
    var status = 'body';
    var loading_msg = 'Just a sec &hellip; processing request';
    var loading_icon = 'fa fas fa-spinner fa-spin';
    var success_icon = 'fa fa-check';
    var error_icon = 'fa fa-frown';
    if (typeof args !== 'undefined') {
        if (args.length > 0)
            status = args[0];
        if (args.length > 1)
            loading_msg = args[1];
        if (args.length > 2)
            success_icon = args[2];
        if (args.length > 3)
            loading_icon = args[3];
        if (args.length > 4)
            error_icon = args[4];
    }


    blockUImessage.removeClass('bg-danger bg-warning').addClass('bg-info').html('<div class=""  style="transform:scale(0.38);"><div></div></div><i class="'+loading_icon+'"></i> <span class="display-block">' + loading_msg + '</span>');
    blockUI(status);
    $.ajax({
        url: url,
        method: "POST",
        dataType: 'json',
        data: form,
        processData: false,
        contentType: false,
        success: function (data, textStatus, XMLHttpRequest) {
            if (data.response != undefined) {
                data = data.response;
            }
            if (data.error.length > 0) {
                if (!data.swal) {
                    blockUImessage.removeClass('bg-info').addClass('bg-' + ((data.valid) ? 'success' : 'danger')).html('<i class="' + ((data.valid) ? success_icon : error_icon) + '"></i> <span class="display-block">' + data.error + '<!--<button type="button" class="btn btn-default">OK</button>--></span>');
                } else {
                    unblockUI(status);
                    createAlert(data.valid, data.swal, data.error);
                }
                unblockUI(status, 500);
            } else {
                unblockUI(status);
            }
            if (data.valid && data.redirect) {
                setTimeout(function () {
                    location.href = data.redirect;
                }, 200);
            }
            if (data.valid && typeof callback === 'function') {
                callback(data);
            } else if (!data.valid && typeof callback_error === 'function') {
                callback_error(data);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(errorThrown);
            if(showExp){                
                blockUImessage.removeClass('bg-info').addClass('bg-danger').html('<i class="' + error_icon + '"></i> <span class="display-block">Error while contacting server: ' + errorThrown + '</span>');
                unblockUI(status, 500);
                if (typeof callback_error === 'function') {
                    callback_error();
                }
            }else{
                unblockUI(status, 500);
                if (typeof callback_error === 'function') {
                    callback_error(errorThrown);
                }
            }
            
        }
    });
}

function blockUI(block) {
    $(block).block({
        message: blockUImessage,
        overlayCSS: {
            backgroundColor: '#fff',
            opacity: 0.8,
            cursor: 'wait'
        },
        css: {
            width: 240,
            border: 0,
            padding: 0,
            backgroundColor: 'transparent',
        },
        onBlock: function () {
            clearTimeout();
        }
    });
}

function unblockUI(block, delay) {
    if (!delay)
        delay = 0;
    window.setTimeout(function () {
        $(block).unblock({
            onUnblock: function () {
                blockUImessage.removeClass('bg-danger bg-success').addClass('bg-info');
            }
        });
    }
    , delay);

    blockUImessage = $('<div class="blockui-message bg-info"><i class="fa fa-spinner fa-spin"></i> <span class="display-block">Just a sec &hellip; processing request</span></div>');
}

function formHndlError(data) {
    removeError();
    if (data.validations != undefined && typeof data.validations == 'object') {
        for (var prop in data.validations) {
            if (typeof data.validations[prop] == 'object') {
                
                for (var key in data.validations[prop]) {
                    if (data.validations[prop][0] != undefined) {
                        if (typeof data.validations[prop][key] == 'string') {
                            $("." + prop + "-error").html(data.validations[prop][key]);
                            $("." + prop + "-error").closest('.form-group').addClass('has-error');
                        } else {
                            // in case error is from child association table
                            for (var assosKey in data.validations[prop][key]) {
                                for (errorKey in data.validations[prop][key][assosKey]) {
                                    var propId = "." + prop + "-" + key + "-" + assosKey;
                                    console.log(propId);
                                    propId = propId.replace("_", "-");
                                    if (assosKey != 'content') {
                                        $(propId).next().text(data.validations[prop][key][assosKey][errorKey]);
                                        $(propId).closest('.form-group').addClass('has-error');
                                    }
                                }
                            }
                        }
                    } else {
                        $("." + prop + "-error").html(data.validations[prop][key]);
                        $("." + prop + "-error").closest('.form-group').addClass('has-error');
                    }
                }
            }
        }
    }
}

function removeError() {
    $(".error").html('');
    $(".form-group").removeClass('has-error');
}

jQuery(document).on('click', ".delete-data", function () {
    var title = $(this).attr('data-title');
    var body = $(this).attr('data-body');
    var url = $(this).attr('data-url');
    var icon = $(this).attr('data-icon');
    var success_msg = $(this).attr('data-success');
    var cancel_msg = $(this).attr('data-cancel');

    swal({
        title: title,
        text: body,
        icon: icon,
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                type: "GET",
                url: url,
                data: {},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {

                    if (response.status == 1) {
                        swal(success_msg, {
                            icon: "success",
                        });

                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    } else if (response.status == 0) {
                        swal(response.message, {
                            icon: "warning",
                        });

                    }
                }
            });
        } else {
            swal(cancel_msg);
        }
    });
});