$(document).ready(function () {


    $("#json-one").change(function () {
        ids = new Array();
        $("#json-one option").each(function () {
            if ($(this).is(":checked")) {
                ids.push($(this).val());
            }
        });
        ids = $("#json-one").val();
        $.ajax({
            url: site_url + 'jsondata/get_sub_category',
            type: "POST",
            dataType: "JSON",
            data: {"ids": ids},
            beforeSend: function () {
                $('#notification_loader_search_speciality').show('slow');
                $('#notification_loader_speciality').show('slow');
            },
            complete: function () {
                $('#notification_loader_search_speciality').hide('slow');
                $('#notification_loader_speciality').hide('slow');
            },
            success: function (result) {
                var $jsontwo = $("#json-two");
                $jsontwo.empty();
                for (var i = 0; i < result.length; i++) {
                    $jsontwo.append("<option value=" + result[i].id + ">" + result[i].category_name + "</option>");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('An error has occured');
            }
        });
    });

    $("#json-two").bind('change', function () {
        ids = new Array();
        removed_ids = new Array();
        $('#speciality_list').find('.chosen').each(function () {
            removed_ids.push($(this).val());
        });
        $("#json-two option").each(function () {
            if ($(this).is(":checked")) {
                ids.push($(this).val());
            }
        });

        $.ajax({
            url: site_url + 'jsondata/get_sub_category',
            type: "POST",
            dataType: "JSON",
            data: {"ids": ids},
            beforeSend: function () {
                $('#notification_loader_search_speciality').show('slow');
                $('#notification_loader_speciality').show('slow');
            },
            complete: function () {
                $('#notification_loader_search_speciality').hide('slow');
                $('#notification_loader_speciality').hide('slow');
            },
            success: function (result) {
                var $jsonthree = $("#json-three");
                $jsonthree.empty();
                for (var i = 0; i < result.length; i++) {
                    var not_already_exist_bit = checkAlreadyExistSpeciality(removed_ids, result[i].id);
                    //alert(not_already_exist_bit);
                    if (not_already_exist_bit) {
                        $jsonthree.append("<option value=" + result[i].id + ">" + result[i].category_name + "</option>");
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('An error has occured');
            }
        });
    });

    function checkAlreadyExistSpeciality(removed_ids, speciality_id) {
        console.log(removed_ids);
        for (var j = 0; j < removed_ids.length; j++) {
            if (removed_ids[j] == speciality_id)
                return false;
        }
        return true;
    }

    $("#search_speciality").keyup(function () {
        var search_speciality_timer = 0;
        clearTimeout(search_speciality_timer);
        search_speciality_timer = setTimeout(function () {
            search_speciality_func();
        }, 1000);
    });

    function search_speciality_func() {
        var search_speciality = $("#search_speciality").val();
        $.ajax({
            url: site_url + 'member/search/search_by_speciality_name',
            type: "POST",
            dataType: "JSON",
            data: {"search_speciality": search_speciality},
            beforeSend: function () {
                $('#notification_loader_search_speciality_alone').show('slow');
            },
            complete: function () {
                $('#notification_loader_search_speciality_alone').hide('slow');
            },
            success: function (result) {
                var $jsonthree = $("#json-three");
                $jsonthree.empty();
                for (var i = 0; i < result.length; i++) {
                    $jsonthree.append("<option value=" + result[i].id + ">" + result[i].category_name + "</option>");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('An error has occured');
            }
        });
    }

    $('#outlook_image').bind('click', function () {
        $.ajax({
            url: site_url + 'member/profile/check_outlook_mail',
            dataType: "JSON",
            beforeSend: function () {
                $('#notification_loader_edit_outlook').show('slow');
            },
            complete: function () {
                $('#notification_loader_edit_outlook').hide('slow');
            },
            success: function (result) {
                if (result == 0) {
                    $('#outlook_email_modal').modal('show');
                }
                alert('Agenda Synced');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });
    bindSync();
    function bindSync() {
        $("#sync_btn").on('click', function () {
            $(this).unbind('click');
            $.ajax({
                url: site_url + 'member/profile/sync_calendar',
                dataType: "JSON",
                beforeSend: function () {
                    $("#sync_btn").addClass('spin-icon');
                    $('#sync_text').show();
                },
                success: function (result) {
                    bindSync();
                    $('#sync_btn').removeClass('spin-icon');
                    $('#sync_text').hide();
                    if (result.gmail_status == 1)
                        window.location.href = site_url + "google/googleapi";
                    else if (result.status == 1)
                        alert('Agenda Synced');
                    if (result.status == 0)
                        alert('No account go to edit page to sync account.');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        });
    }

    $('.api_edit_btn').on('click', function () {
        var api_name = $(this).parent().parent().find('.api_name_class').text();
        var api_id = $(this).attr('api_id');
        var api_key = $(this).parent().parent().find('.api_key_class').text();
        $('#api_name_id').val(api_name);
        $('#api_id').val(api_id);
        $('#api_key').val(api_key);
        $('#api_modal').modal('show');
    });

    $('#api_submit_btn').on('click', function () {
        var id = $('#api_id').val();
        var api_key = $('#api_key').val();
        if (api_key == "") {
            alert('Api key field should not be empty.');
        }
        else {
            $.ajax({
                url: site_url + 'admin/home/save_api',
                type: "POST",
                dataType: "JSON",
                data: {"id": id, "api_key": api_key},
                success: function (result) {
                    window.location.reload();
                    //$("#apis").reload();
                }
            });
        }
    });

    $('#address').bind('keypress', function (e) {
        if (e.keyCode == 13) {
            return false;
        }
    });

    $('#visibility').on('change', function () {
        var selected_item = $('#visibility').val();
        if (selected_item == 3) {
            $('#basicModal').modal('show');
        }
    });

    $("#select_company").keydown(function (event) {
        //alert(event.keyCode === $.ui.keyCode.TAB);
        if (event.keyCode === $.ui.keyCode.TAB) { //&& $(this).data("autocomplete").menu.active){
            //alert('abcdafads');
            event.preventDefault();

        }
    })
        .autocomplete({
            minLength: 1,
            source: function (request, response) {
				var already_selected_companies = "";
				$("ul#selected_company .selected_company_ids").each(function() {
					already_selected_companies += $(this).val()+",";
				});
                $.getJSON(site_url + 'member/profile/searchCompany' + '/' + $("#select_company").val()+"?already_selected_companies="+already_selected_companies, 
				{
                    term: extractLast(request.term)
                }, response);
            },
            search: function () {
                // custom minLength
                var term = extractLast(this.value);
                if (term.length < 1) {
                    return false;
                }
            },
            focus: function () {
                // prevent value inserted on focus
                return true;
            },
            select: function (event, ui) {
                var terms = split(this.value);
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push(ui.item.value);
                // add placeholder to get the comma-and-space at the end
                terms.push("");
                this.value = terms.join("");
                get_company_detail(this.value);
                $('#select_company').val("");
                return false;
            }
        });

    $("#select_user").keydown(function (event) {
        //alert(event.keyCode === $.ui.keyCode.TAB);
        if (event.keyCode === $.ui.keyCode.TAB) { //&& $(this).data("autocomplete").menu.active){
            //alert('abcdafads');
            event.preventDefault();

        }
    })
        .autocomplete({
            minLength: 1,
            source: function (request, response) {
                $.getJSON(site_url + 'admin/payment/searchUser' + '/' + $("#select_user").val(), {
                    term: extractLast(request.term)
                }, response);
            },
            search: function () {
                // custom minLength
                var term = extractLast(this.value);
                if (term.length < 1) {
                    return false;
                }
            },
            focus: function () {
                // prevent value inserted on focus
                return true;
            },
            select: function (event, ui) {
                var terms = split(this.value);
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push(ui.item.value);
                // add placeholder to get the comma-and-space at the end
                terms.push("");
                this.value = terms.join("");
                get_user_detail(this.value);
                return false;
            }
        });


    function get_user_detail(val) {
        $.ajax({
            url: site_url + 'admin/payment/get_user',
            type: "POST",
            dataType: "JSON",
            data: {"user_detail": val},
            success: function (result) {
                console.log(result);
                var $select_user = $("#select_user");
                var $general_id_add_subscription = $("#general_id_add_subscription");
                $general_id_add_subscription.val(result.general_id);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('An error has occured');
            }
        });
    }

    $('.contact').on('click', function () {
        $('#contact_modal').modal('show');
        $('#message').html('');
    });

    $('#add_subscription_btn').on('click', function () {
        $('#select_user').html('');
        $('#all_subscription_modal').modal('show');
    });

    $('.edit_subscription_btn').on('click', function () {
        var name = $(this).parent().parent().find('.name_class').html();
        var id = $(this).attr('result');
        $('#user_name').val(name);
        $('#edit_subscription_id').val(id);
        $('#edit_subscription_modal').modal('show');
    });

    $('#contact_company').on('click', function () {
        var send_to = $('#contact_to').val();
        var user_id = $('#user_id_for_contact_to').val();
        var message = $('#message_contact').val();
        if (message == "") {
            alert('Message is required.');
        }
        else {
            $.ajax({
                url: site_url + 'member/profile/send_contact_mail',
                type: "POST",
                data: {"send_to": send_to, "user_id": user_id, "message": message},
                success: function (result) {
                    $('#contact_trainer_search_modal').modal('hide');
                }
            });
        }

    });

    $('.contact_trainer_search_button').on('click', function (e) {
		e.preventDefault();
        var send_to = $(this).attr('result_mail');
        var user_id = $(this).attr('result_id');
        $('#contact_to').val(send_to);
        $('#user_id_for_contact_to').val(user_id);
        $('#contact_trainer_search_modal').modal('show');
    });

    $('.email_send_button').on('click', function () {
        var id = $(this).attr('id_attribute');
        var subject = $(this).parent().parent().find('.suject_class').text();
        var email = $(this).parent().parent().find('.email_class').text();
        $('#email_id').val(id);
        $('#subject_save').val(subject);
        $('#email_save').val(email);
        $('#email_save_modal').modal('show');
    });

    $('#add_to_favorite').on('click', function () {
        $.ajax({
            url: site_url + 'member/profile/add_to_favorite',
            type: "POST",
            dataType: "JSON",
            data: {"general_id": userid},
            beforeSend: function () {
                $("#notification_loader_favorite").show('slow');
            },
            success: function (result) {
                if (result.status == "1") {
                    $("#add_to_favorite").html('<i class="fa fa-star"></i> &nbsp;&nbsp;Favorite');
                }
                else {
                    $("#add_to_favorite").html(' <i class="fa fa-star-o"></i> &nbsp;&nbsp;Add to favorite');
                }
            }
        });
    });

    $('.agdBtn').on('click', function () {
        var obj = $(this);
        var id = obj.attr('agenda_id');
        $.ajax({
            url: site_url + 'member/trainer_agenda/ajax_agenda',
            type: "POST",
            dataType: "JSON",
            data: {"id": id},
            success: function (result) {
                $(".appointment_name").html(result.agenda.appointment_name);
                $(".company_name").html(result.agenda.name);
                $(".location").html(result.agenda.location);
                $(".date_time").html(result.agenda.appoint_date);
                $(".date_time").append(" / " + result.all_time);
                $(".description").html(result.agenda.appointment_description);
                if (result.document != null) {
                    $(".document").html(result.document.document_name);
                    $(".document").attr("href", base_url + "uploads/document/" + result.document.document_name);
                }
                else {
                    $(".document").html("No Document");
                    $(".document").removeAttr("href");
                }
            }
        });

    });


    $(".select2-search-choice-close").click(function () {
        $(this).parent().remove();
    });

    function get_company_detail(val) {
        $.ajax({
            url: site_url + 'member/profile/get_company',
            type: "POST",
            dataType: "JSON",
            data: {"company_detail": val},
            success: function (result) {
                var $selected_company = $("#selected_company");
                $selected_company.append("<li>" + result.name + "<input type='hidden' class='selected_company_ids' name='companies[]' value='" + result.id + "'><span class='remove_selected_company'><i class='fa fa-times'></i></span></li>");
                $(".remove_selected_company").click(function () {
                    $(this).parent().remove();
                });
                var $company_list = $("#company_list");
                $company_list.append("<li>" + result.name + "<input type='hidden' class='selected_company_ids' name='companies[]' value='" + result.id + "'><span class='remove_selected_company'><i class='fa fa-times'></i></span></li>");
                $(".remove_selected_company").click(function () {
                    $(this).parent().remove();
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('An error has occured');
            }
        });
    }


    function split(val) {
        return val.split(/,\s*/);
    }

    function extractLast(term) {
        return split(term).pop();
    }

    function initialize() {
        if (document.getElementById('address')) {
            //document.getElementById('address_latitude').value = '';
            //document.getElementById('address_longitude').value = '';
            var input = document.getElementById('address');
            var autocomplete = new google.maps.places.Autocomplete(input);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                document.getElementById('address_latitude').value = place.geometry.location.lat();
                document.getElementById('address_longitude').value = place.geometry.location.lng();

            });
        }
    }

    function initialize_search() {
        if (document.getElementById('address_latitude')) {
            //document.getElementById('address_latitude').value = '';
            //document.getElementById('address_longitude').value = '';
            var input = document.getElementById('location');
            var autocomplete = new google.maps.places.Autocomplete(input);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                document.getElementById('address_latitude').value = place.geometry.location.lat();
                document.getElementById('address_longitude').value = place.geometry.location.lng();

            });
        }
    }

    /* function initialize_appointment() {
     if(document.getElementById('agenda_latitude')){
     //document.getElementById('address_latitude').value = '';
     //document.getElementById('address_longitude').value = '';
     var input = document.getElementById('agenda_location');

     var autocomplete = new google.maps.places.Autocomplete(input);
     google.maps.event.addListener(autocomplete, 'place_changed', function () {
     var place = autocomplete.getPlace();
     document.getElementById('agenda_latitude').value = place.geometry.location.lat();
     });
     }
     }*/

    google.maps.event.addDomListener(window, 'load', initialize);
    google.maps.event.addDomListener(window, 'load', initialize_search);
    //google.maps.event.addDomListener(window, 'load', initialize_appointment);

    $("#reply_btn").on('click', function () {
        $("#reply-message").append('<section class="panel-textarea inbox reply-section"><textarea placeholder="Reply message here" cols="10" rows="2" class="form-control parsley-validated" id="text_area"></textarea><button type="submit" class="btn btn-primary" id="send_btn">Send</button><button class="btn btn-default" id="cancel_btn">Cancel</button></section>');
        $(this).unbind("click");
        $("#text_area").focus();
    });

    function myHandler() {
        $("#reply_btn").on('click', function () {
            $("#reply-message").append('<section class="panel-textarea inbox reply-section"><textarea placeholder="Reply message here" cols="10" rows="2" class="form-control parsley-validated" id="text_area"></textarea><button  class="btn btn-primary" id="send_btn">Send</button><button class="btn btn-default" id="cancel_btn">Cancel</button></section>');
            $(this).unbind("click");
            $("#text_area").focus();
        });
    }

    $(document).on('click', "#cancel_btn", function () {
        $("#reply-message").html('');
        myHandler();
    });

    $(document).on('click', '#send_btn', function () {
        var parent_id = $("#parent_id").val();
        var text_area = $("#text_area").val();
        var sent_to_id = $("#sent_to_id").val();
        var sent_by_id = $("#sent_by_id").val();
        if (text_area == "") {
            alert("Message is required.");
        }
        else {
            $.ajax({
                url: site_url + 'member/profile/send_contact_mail_page',
                type: "POST",
                data: {"parent_id": parent_id, "message": text_area, "sent_to_id": sent_to_id, "sent_by_id": sent_by_id},
                success: function (result) {
                    window.location.reload();
                }
            });
        }

    });


});