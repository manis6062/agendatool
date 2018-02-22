$(document).ready(function () {
    var userEventDate = new Array();
    $.ajax({
        url: site_url + 'member/profile/get_date',
        type: "POST",
        dataType: "JSON",
        data: {"id": trainerid},
        success: function (result) {
            if (result.standard_availability != null) {
                var startStandardTime = (result.standard_availability.from_time);
                var endStandardTime = (result.standard_availability.to_time);

                var totalStandardTime = parseInt(endStandardTime) - parseInt(startStandardTime) + 1;
            }
            $('#widget-calender').fullCalendar({
                header: {
                    left: 'prevYear prev',
                    center: 'title',
                    right: 'next nextYear'
                },
                dayRender: function (date, cell) {
                    cell.find(".fc-day-number").addClass("unavailable");
                    cell.find('.fc-day-number').click(function () {
                        window.location.href = site_url + 'member/trainer_agenda/get_trainer_agenda/' + userdata_id;
                    });
                    var day = date.getDay();//to get day from 0 to 6
                    var year = date.getFullYear();

                    if (result.standard_availability != null) {
                        var startDay = parseInt(result.standard_availability.from_day);
                        var endDay = parseInt(result.standard_availability.to_day);
                        var startTime = parseInt(result.standard_availability.from_time);
                        var endTime = parseInt(result.standard_availability.to_time);
                        if (startDay != "" && endDay != "") {
                            for (var i = startDay; i <= endDay; i++) {
                                if (day == i)
                                    cell.find(".fc-day-number").removeClass("notConfirmed unavailable").addClass("available");
                            }
                        }
                        else {
                            cell.find(".fc-day-number").removeClass("notConfirmed unavailable").addClass("available");
                        }
                        if (result.days != null) {
                            $.each(result.days, function (index, obj) {
                                if (day == obj)
                                    cell.find(".fc-day-number").removeClass("notConfirmed available").addClass("unavailable");
                            });
                        }

                    }
                    else {
                        cell.find(".fc-day-number").removeClass("notConfirmed unavailable").addClass("available");
                    }
                    var day = date.getDay();//to get day from 0 to 6
                    var year = date.getFullYear();
                    var month = date.getMonth() + 1;
                    if (month < 10) {
                        month = "0" + month;
                    }
                    var d = date.getDate();
                    if (d < 10) {
                        d = "0" + d;
                    }
                    var fulldate = year + "-" + month + "-" + d;

                    if (result.trainer_agenda != null) {
                        var confirmedTime = 0;
                        $.each(result.trainer_agenda, function (index, obj) {
                            console.log(obj);
                            if (obj.appoint_date == fulldate && obj.reserved_time.length != 0) {
                                cell.find(".fc-day-number").removeClass("unavailable available").addClass("notConfirmed");
                            }
                            /*else if(obj.appoint_date == fulldate && obj.is_confirm == 1 && obj.is_appointed == 1 && obj.reserved_time.length != 0){
                             cell.find(".fc-day-number").removeClass("unavailable available").addClass("notConfirmed");
                             }*/
                            /*else */
                            if (obj.appoint_date == fulldate && obj.is_confirm == 1 && obj.is_appointed == 1 && obj.reserved_time.length == totalStandardTime) {
                                cell.find(".fc-day-number").removeClass("available notConfirmed").addClass("unavailable");
                                cell.find(".unavailable").unbind("click");
                            }
                            /*else */
                            if (obj.appoint_date == fulldate && obj.is_confirm == 0 && obj.is_appointed == 0 && obj.reserved_time.length == 0) {
                                cell.find(".fc-day-number").removeClass("notConfirmed unavailable").addClass("available");
                            }

                            if (obj.appoint_date == fulldate && obj.is_confirm == 1 && obj.is_appointed == 1) {
                                confirmedTime = confirmedTime + obj.reserved_time.length;
                            }

                            if (obj.appoint_date == fulldate && confirmedTime == totalStandardTime) {
                                cell.find(".fc-day-number").removeClass("available notConfirmed").addClass("unavailable");
                            }

                        });
                    }
                }
            });
        }
    });
    var titleYear;
    var d = new Date();
    var y = d.getFullYear();
    $(".prev-year").text(y - 1);
    $(".next-year").text(y + 1);

    $(".fc-button-next").mousedown(function () {
        var title = $(".fc-header-title h2").text();
        titleYear = title.split(" ");
    });

    $(".fc-button-prev").mousedown(function () {
        var title = $(".fc-header-title h2").text();
        titleYear = title.split(" ");
    });

    $(".year").click(function () {
        var prevyear = parseInt($(".prev-year").text());
        var nextyear = parseInt($(".next-year").text());

        if ($(this).hasClass("prev-year")) {
            prevyear--;
            nextyear--;
            $(".fc-button-prevYear").trigger('click');
            $(this).text(prevyear);
            $(".next-year").text(nextyear);
        } else {
            prevyear++;
            nextyear++;
            $(".fc-button-nextYear").trigger('click');
            $(this).text(nextyear);
            $(".prev-year").text(prevyear);
        }
    });


    function callMonth() {
        var title = $(".fc-header-title h2").text();
        var titleYear2 = title.split(" ");
        var chk = (titleYear2[1] - titleYear[1]);
        var nx = $(".next-year").text();
        var pv = $(".prev-year").text();
        $(".next-year").text(chk + parseInt(nx));
        $(".prev-year").text(chk + parseInt(pv));
    }

    $(".fc-button-next").click(function () {
        callMonth();
    });

    $(".fc-button-prev").click(function () {
        callMonth();
    });
});