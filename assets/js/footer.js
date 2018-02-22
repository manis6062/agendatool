$("#filter_select").change(function () {
    var filter = $(this).val();
    $.ajax({
        url: site_url + 'member/trainer_agenda/pending_agenda',
        type: "POST",
        data: {"filter": filter},
        beforeSend: function () {
            $("#notification_loader").show('slow');
        },
        success: function (result) {
            location.reload();
        }
    });
});

$('#active_select').change(function () {
    var active_select = $(this).val();
    $.ajax({
        url: site_url + 'admin/home/setting_page',
        type: 'POST',
        data: {'active_status': active_select},
        success: function (result) {
            location.reload();
        }
    });
});

$("#order_select").change(function () {
    var order = $(this).val();
    $.ajax({
        url: site_url + 'member/trainer_agenda/pending_agenda',
        type: "POST",
        data: {"order": order},
        beforeSend: function () {
            $("#notification_loader").show('slow');
        },
        success: function (result) {
            location.reload();
        }
    });
});
$("#list_select").change(function () {
    var per_page = $(this).val();
    $.ajax({
        url: site_url + 'member/trainer_agenda/pending_agenda',
        type: "POST",
        data: {"per_page": per_page},
        beforeSend: function () {
            $("#notification_loader").show('slow');
        },
        success: function (result) {
            location.reload();
        }
    });
});

$("#search_order_select").change(function () {
    var search_order = $(this).val();
    $.ajax({
        url: site_url + 'member/search/search_user',
        type: "POST",
        data: {"search_order": search_order},
        beforeSend: function () {
            $("#search_loader").show('slow');
        },
        success: function (result) {
            location.reload();
        }
    });
});
$("#search_list_select").change(function () {
    var search_per_page = $(this).val();
    $.ajax({
        url: site_url + 'member/search/search_user',
        type: "POST",
        data: {"search_per_page": search_per_page},
        beforeSend: function () {
            $("#search_loader").show('slow');
        },
        success: function (result) {
            location.reload();
        }
    });
});


$(document).ready(function () {
    $('#more_options').click(function () {
        $('#insert_options').append('<label for="option" class="placeholder-hidden">Option</label><input type="text" class="form-control option" placeholder="Option" name="options[]"><br>');
    });


    $('#submit_btn').click(function () {
        var fup = document.getElementById('upload');
        if (fup.value) {
            var fileName = fup.value;
            var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
            if (ext == "pdf" || ext == "doc" || ext == "docx" || ext == "txt") {
                return true;
            }
            else {
                alert("Please select file with doc, docx, txt and pdf.");
                fup.focus();
                return false;
            }
        }
        else
            return true;
    });

    $("#tp-ex-1").val("");
});

$(function() {
    var dateToday = new Date();
    $("#available_date").datepicker({
        showOn: "button",
        buttonText: "<i class='fa fa-calendar'></i>",
        defaultDate: "",
        dateFormat: 'yy-mm-dd',
        nextText: "→",
        prevText: "←",
        firstDay: 1,
        dayNamesMin : ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
        minDate: dateToday
    });
});
