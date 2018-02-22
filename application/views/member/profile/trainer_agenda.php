<div class="content-header">
    <h2 class="content-header-title"><?php echo $words['DTL_0027']; ?></h2>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url('member/profile') ?>"><?php echo $words['DTL_0205']; ?></a></li>
        <li class="active"><?php echo $words['DTL_0027']; ?></li>
    </ol>
</div>
<div class="pull-right">
    <ul class="calender_note">
        <li><span class="color_spot available"></span><?php echo $words['DTL_0042']; ?></li>
        <li><span class="color_spot notConfirmed"></span><?php echo $words['DTL_0167']; ?></li>
        <!--        <li>  <span class="color_spot Confirmed"></span>--><?php //echo 'Confirmed'; ?><!--</li>-->
        <li><span class="color_spot unavailable"></span><?php echo $words['DTL_0277']; ?></li>
    </ul>
</div>
<br/>
<br/>
<!-----------------------------  NEW CALENDAR ---------------------------->
<script type='text/javascript' src='<?php echo base_url() ?>assets/test/js/jquery-1.10.2.js'></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script type='text/javascript' src='<?php echo base_url() ?>assets/test/js/jquery-ui.js'></script>
<!-- <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script> -->
<script type="text/javascript" src="<?php echo base_url() ;?>assets/test/js/date.js"></script>
<script type='text/javascript' src='<?php echo base_url() ?>assets/test/js/jquery.weekcalendar.js'></script>
<link rel='stylesheet' type='text/css' href='<?php echo base_url() ?>assets/test/css/jquery.weekcalendar.css'/>
<style type='text/css'>
    body {
        font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
        margin: 0;
    }
    h1 {
        margin: 0 0 1em;
        padding: 0.5em;
    }
    p.description {
        font-size: 0.8em;
        padding: 1em;
        position: absolute;
        top: 3.2em;
        margin-right: 400px;
    }
    #message {
        font-size: 0.7em;
        position: absolute;
        top: 1em;
        right: 1em;
        width: 350px;
        display: none;
        padding: 1em;
        background: #ffc;
        border: 1px solid #dda;
    }

    span.remove {
        cursor: pointer;
        height: 30px;
        position: absolute;
        left: -9px;
        top: -9px;
        width: 30px;
    }

    span.remove::after {
        background-color: #000000;
        border-radius: 18px;
        color: #ffffff;
        content: "X";
        display: block;
        font-size: 30px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        vertical-align: middle;
        width: 30px;

    }



</style>
<?php
$business_from_time = $standard_availability['from_time'];
$business_to_time = $standard_availability['to_time'];
$start_days = $standard_availability['from_day'];  // 0,1,2,3,4,5,6
$from_new_day1 = explode(',' ,$standard_availability['from_new_day'] );
$day_schedule = array(0,1,2,3,4,5,6);
$from_new_day = array_diff($day_schedule , $from_new_day1);
$from_new_day_unblocked = array_intersect($day_schedule,$from_new_day1);
function getDateForSpecificDayBetweenDates($startDate, $endDate, $weekdayNumber)
{
    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);
    $dateArr = array();
    do
    {
        if(date("w", $startDate) != $weekdayNumber)
        {
            $startDate += (24 * 3600); // add 1 day
        }
    } while(date("w", $startDate) != $weekdayNumber);
    while($startDate <= $endDate)
    {
        $dateArr[] = date('Y-m-d', $startDate);
        $startDate += (7 * 24 * 3600); // add 7 days
    }
    return($dateArr);
}
$dateArr_sun = getDateForSpecificDayBetweenDates('2015-01-01', '2017-01-01', 6);
$dateArr_mon = getDateForSpecificDayBetweenDates('2015-01-01', '2017-01-01', 1);
$dateArr_tue = getDateForSpecificDayBetweenDates('2015-01-01', '2017-01-01', 2);
$dateArr_wed = getDateForSpecificDayBetweenDates('2015-01-01', '2017-01-01', 3);
$dateArr_thu = getDateForSpecificDayBetweenDates('2015-01-01', '2017-01-01', 4);
$dateArr_fri = getDateForSpecificDayBetweenDates('2015-01-01', '2017-01-01', 5);
$dateArr_sat = getDateForSpecificDayBetweenDates('2015-01-01', '2017-01-01', 0);
$dateArr_[] = getDateForSpecificDayBetweenDates('2015-01-01', '2017-01-01', 0);
$dateArr_[] = getDateForSpecificDayBetweenDates('2015-01-01', '2017-01-01', 1);
$dateArr_[] = getDateForSpecificDayBetweenDates('2015-01-01', '2017-01-01', 2);
$dateArr_[] = getDateForSpecificDayBetweenDates('2015-01-01', '2017-01-01', 3);
$dateArr_[] = getDateForSpecificDayBetweenDates('2015-01-01', '2017-01-01', 4);
$dateArr_[] = getDateForSpecificDayBetweenDates('2015-01-01', '2017-01-01', 5);
$dateArr_[] = getDateForSpecificDayBetweenDates('2015-01-01', '2017-01-01', 6);
$prevent_dates_arr = array();
foreach($from_new_day as $fnd){
    $prevent_dates_arr = array_merge($prevent_dates_arr,$dateArr_[$fnd]);
}
?>
<script type='text/javascript'>



var year = new Date().getFullYear();
var month = ('0' + (new Date().getMonth())).slice(-2);
var day = ("0" + new Date().getDate()).slice(-2);
var user_events = new Array();
<?php
        $standard_db_time = $standard_availability['from_new_time'];
        $standard_time = explode(',' , $standard_db_time);
        foreach($prevent_dates_arr as $k => $v){
                  $sdate = date("Y",strtotime($v));
                  $smonth = date("m",strtotime($v)) - 1;
                  $sday = date("d",strtotime($v));
                  $sstart_hour = 00;
                  $sstart_minute = 00;
                  $send_hour = 24;
                  $send_minute = 00;?>
var event = {'id':<?php echo $k; ?>, 'start': new Date('<?php echo $sdate;?>', '<?php echo $smonth;?>', '<?php echo $sday;?>', <?php echo $sstart_hour;?>, <?php echo $sstart_minute;?>),
    'end': new Date('<?php echo $sdate;?>', '<?php echo $smonth;?>', '<?php echo $sday?>', <?php echo $send_hour;?>, <?php echo $send_minute?>),
    'block' : true ,readOnly : true};
user_events.push(event);

<?php }?>







<?php

function getDateForSpecificDayBetweenPastDates($startDate, $endDate, $weekdayNumber)
{
    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);
    $dateArr = array();
    do
    {
        if(date("w", $startDate) != $weekdayNumber)
        {
            $startDate += (24 * 3600); // add 1 day
        }
    } while(date("w", $startDate) != $weekdayNumber);
    while($startDate <= $endDate)
    {
        $dateArr[] = date('Y-m-d', $startDate);
        $startDate += (7 * 24 * 3600); // add 7 days
    }
    return($dateArr);
}


$past_days = array(0,1,2,3,4,5,6);
$today = date("Y-m-d");
$prev_day = date('Y-m-d', strtotime(' -1 day'));
$prev_month = date('Y-m-d', strtotime(' -2 month'));



$dateArr_past_sun = getDateForSpecificDayBetweenPastDates($prev_month, $prev_day, 6);
$dateArr_past_mon = getDateForSpecificDayBetweenPastDates($prev_month, $prev_day, 1);
$dateArr_past_tue = getDateForSpecificDayBetweenPastDates($prev_month,$prev_day, 2);
$dateArr_past_wed = getDateForSpecificDayBetweenPastDates($prev_month, $prev_day, 3);
$dateArr_past_thu = getDateForSpecificDayBetweenPastDates($prev_month,$prev_day, 4);
$dateArr_past_fri = getDateForSpecificDayBetweenPastDates($prev_month, $prev_day, 5);
$dateArr_past_sat = getDateForSpecificDayBetweenPastDates($prev_month, $prev_day, 0);
$past_dateArr_[] = getDateForSpecificDayBetweenPastDates($prev_month,$prev_day, 0);
$past_dateArr_[] = getDateForSpecificDayBetweenPastDates($prev_month, $prev_day, 1);
$past_dateArr_[] = getDateForSpecificDayBetweenPastDates($prev_month,$prev_day, 2);
$past_dateArr_[] = getDateForSpecificDayBetweenPastDates($prev_month, $prev_day, 3);
$past_dateArr_[] = getDateForSpecificDayBetweenPastDates($prev_month, $prev_day, 4);
$past_dateArr_[] = getDateForSpecificDayBetweenPastDates($prev_month,$prev_day, 5);
$past_dateArr_[] = getDateForSpecificDayBetweenPastDates($prev_month, $prev_day, 6);
$prevent_past_dates_arr = array();
foreach($past_days as $fnd){
    $prevent_past_dates_arr = array_merge($prevent_past_dates_arr,$past_dateArr_[$fnd]);
}



?>


<?php
        foreach($prevent_past_dates_arr as $k => $v){
                  $sdate = date("Y",strtotime($v));
                  $smonth = date("m",strtotime($v)) - 1;
                  $sday = date("d",strtotime($v));
                  $sstart_hour = 00;
                  $sstart_minute = 00;
                  $send_hour = 24;
                  $send_minute = 00;?>
var event = {'id':<?php echo $k; ?>, 'start': new Date('<?php echo $sdate;?>', '<?php echo $smonth;?>', '<?php echo $sday;?>', <?php echo $sstart_hour;?>, <?php echo $sstart_minute;?>),
    'end': new Date('<?php echo $sdate;?>', '<?php echo $smonth;?>', '<?php echo $sday?>', <?php echo $send_hour;?>, <?php echo $send_minute?>),
    'block' : false ,readOnly : true};
user_events.push(event);

<?php }?>


<?php

        foreach($prevent_past_dates_arr as $k => $v){
                  $sdate = date("Y",strtotime($v));
                  $smonth = date("m",strtotime($v)) - 1;
                  $sday = date("d",strtotime($v));
                  $sstart_hour = 00;
                  $sstart_minute = 00;
                  $send_hour = 24;
                  $send_minute = 00;?>
var event = {'id':<?php echo $k; ?>, 'start': new Date('<?php echo $sdate;?>', '<?php echo $smonth;?>', '<?php echo $sday;?>', <?php echo $sstart_hour;?>, <?php echo $sstart_minute;?>),
    'end': new Date('<?php echo $sdate;?>', '<?php echo $smonth;?>', '<?php echo $sday?>', <?php echo $send_hour;?>, <?php echo $send_minute?>),
    'block' : false ,readOnly : true};
user_events.push(event);

<?php }?>
var d = new Date();
var n = d.getHours();

var event = {'id':'', 'start': new Date(year, month, day,0), 'end': new Date(year, month, day,n), 'block' : false ,readOnly : true};
user_events.push(event);



<?php
    $standard_db_time = $standard_availability['from_new_time'];
    $standard_time = explode(',' , $standard_db_time);
    $from_new_day = array();
    foreach($from_new_day_unblocked as $fnd){
    $from_new_day = array_merge($from_new_day,$dateArr_[$fnd]);
    }
$i_array = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23);
$in_time = array_diff($i_array , $standard_time);
                foreach($in_time as $it) {
                $standard_start_time = $it;
                $standard_end_time = $it + 1;
                foreach($from_new_day as $k => $v){
                $sdate = date("Y",strtotime($v));
                $smonth = date("m",strtotime($v)) - 1;
                $sday = date("d",strtotime($v));
                $sstart_hour = $standard_start_time;
                $sstart_minute = 00;
                $send_hour = $standard_end_time;
                $send_minute = 00;    ?>
var event = {'id':<?php echo $k; ?>, 'start': new Date('<?php echo $sdate;?>', '<?php echo $smonth;?>', '<?php echo $sday;?>', <?php echo $sstart_hour;?>, <?php echo $sstart_minute;?>),
    'end': new Date('<?php echo $sdate;?>', '<?php echo $smonth;?>', '<?php echo $sday?>', <?php echo $send_hour;?>, <?php echo $send_minute?>),
    'block' : true ,readOnly : true};
user_events.push(event);
<?php }} ?>
<?php
$index = 1;
foreach($events as $event) {
    $date = date("Y",strtotime($event['appoint_date']));
    $appointment_id = $event['appointment_id'];
    $is_appointed = $event['is_appointed'];
    $is_confirm = $event['is_confirm'];
    $appointed_by = $event['appointed_by'];
    $is_company = $event['is_company'];
    $appoint_date = $event['appoint_date'];
    $actual_month = date("Y-m-d", strtotime( "$appoint_date -1 month" ));
    $month = date("m",strtotime($actual_month));
    $day = date("d",strtotime($event['appoint_date']));
    $start_hour = date("H",strtotime($event['start_date']));
    $start_minute = date("i",strtotime($event['start_date']));
    $end_hour = date("H",strtotime($event['end_date']));
    $end_minute = date("i",strtotime($event['end_date'])); ?>
if("<?php echo $is_confirm ; ?>" == 0){
    var event = {'id':<?php echo $index?>, 'start': new Date('<?php echo $date;?>', '<?php echo $month;?>', '<?php echo $day;?>', <?php echo $start_hour;?>, <?php echo $start_minute;?>), 'end': new Date('<?php echo $date;?>', '<?php echo $month;?>', '<?php echo $day?>', <?php echo $end_hour;?>, <?php echo $end_minute?>), 'title': '<?php echo $event['appointment_name']?>', 'appointment_id':<?php echo $appointment_id ;?>,'readOnly': false ,'appointed_by' : <?php echo $appointed_by ;?> , 'is_company' : <?php echo $is_company ;?>};
}
else{
    var event = {'id':<?php echo $index?>, 'start': new Date('<?php echo $date;?>', '<?php echo $month;?>', '<?php echo $day;?>', <?php echo $start_hour;?>, <?php echo $start_minute;?>), 'end': new Date('<?php echo $date;?>', '<?php echo $month;?>', '<?php echo $day?>', <?php echo $end_hour;?>, <?php echo $end_minute?>), 'title': '<?php echo $event['appointment_name']?>', 'appointment_id':<?php echo $appointment_id ;?> ,'readOnly': true ,'appointed_by' : <?php echo $appointed_by ;?> , 'is_company' : <?php echo $is_company ;?>} ;
}
event.starttimestamp = event.start.getTime();
event.endtimestamp = event.end.getTime();
user_events.push(event);
<?php
$index++;
}?>
var eventData = {events: []};
var eventDataAjax = {events: []};
var eventData = {events: user_events};
var general_id = '<?php echo $this->session->userdata('general_id')?>';
$(document).ready(function () {
    var datepickerOpen = false;
    $('.js-datepicker-toggle').click( function ()
    {
        datepickerOpen = !datepickerOpen;
        $(this).toggleClass('is-active', datepickerOpen);
        $('#datepicker').toggleClass('is-hidden', !datepickerOpen);
    });
    var dateToday = new Date();
    $('#datepicker').datepicker({
        defaultDate: "",
        changeMonth: true,
        numberOfMonths: 1,
        dateFormat: 'yy-mm-dd',
        minDate: dateToday,
        nextText: "→",
        prevText: "←",
        firstDay: 1,
        dayNamesMin : ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
        onSelect: function(dateText, inst) {
            parent.location.hash = dateText;
            location.reload();
        }
    });
    var type = window.location.hash.substr(1);
    if(type){
        trigger_date = type;
    }
    else{
        trigger_date = dateToday;
    }
    $(".prev-week").click(function (e) {
        e.preventDefault();
        $("#calendar").weekCalendar("prevWeek");
    });
    $(".next-week").click(function (e) {
        e.preventDefault();
        $("#calendar").weekCalendar("nextWeek");
    });
    $(".todays_date").click(function (e) {
        e.preventDefault();
        $("#calendar").weekCalendar("today");
    });


    function changeEventDetails(id,calEvent,eventData){
        var full_year = calEvent.start.getFullYear();
        var full_month = ('0' + (calEvent.start.getMonth() + 1)).slice(-2);
        var full_date = ("0" + calEvent.start.getDate()).slice(-2);
        var full_hour = ("0" + calEvent.start.getHours()).slice(-2);
        var full_minutes = ("0" + calEvent.start.getMinutes()).slice(-2);
        var start_date = full_year + "-" + full_month + "-" + full_date + "T" + full_hour + ":" + full_minutes;
        var full_year_end = calEvent.end.getFullYear();
        var full_month_end = ('0' + (calEvent.end.getMonth() + 1)).slice(-2);
        var full_date_end = ("0" + calEvent.end.getDate()).slice(-2);
        var full_hour_end = ("0" + calEvent.end.getHours()).slice(-2);
        var full_minutes_end = ("0" + calEvent.end.getMinutes()).slice(-2);
        eventData.events[id - 1].start = new Date(full_year ,(full_month-1),full_date,full_hour,full_minutes);
        eventData.events[id - 1].end = new Date(full_year_end,(full_month_end-1),full_date_end,full_hour_end,full_minutes_end);
        eventData.events[id - 1].starttimestamp = eventData.events[id - 1].start.getTime();
        eventData.events[id - 1].endtimestamp = eventData.events[id - 1].end.getTime();
        eventData.events[id - 1].readOnly = false;
        return eventData;
    }

    function ajax_submit(eventData,id){

        $.ajax({
            url: '<?php echo base_url('member/profile/edit_appointment' . '/' .  $this->session->userdata('general_id' )); ?>',
            type: 'POST',
            data: {"event_details": eventData},
            dataType: "text",
            beforeSend: function () {
                $('.loader').css("display", "block");
            },
            success: function (response) {
                $('.loader').css("display", "none");
                window.location.href = "<?php echo base_url('member/profile/appointment_page' . '/' .  $this->uri->segment(4));?>";

            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    }

    function displayMessage(message) {
        $('#message').html(message).fadeIn();
    }


    $("body").on("click", ".remove", function (e) {
        var remove = e.target;
        var parent = $(remove).parent();
        $(parent).remove();
        remove_id = remove.id;
        var split = remove_id.split("_");
        var events_id = split[0];
        var event_id = parseInt(events_id);
        eventData.events.splice((event_id - 1), 1);
        for(var i= 0;i<eventDataAjax.events.length;i++){
            if(eventDataAjax.events[i].id == event_id)
                eventDataAjax.events.splice(i, 1);
        }
    });
    $('#calendar').weekCalendar({
        date: trigger_date,
        timeslotsPerHour: 2,
        dateFormat: "M jS",
        businessHours: {start: <?php echo $business_from_time ; ?>, end:  <?php echo $business_to_time ; ?>, limitDisplay: false},
        timeslotHeight: 21,
        firstDayOfWeek: 0,
        hourLine: true,
        daysToShow : <?php echo 7 ; ?>,
        newEventText: '',
        data: eventData,
        overlapEventsSeparate: false,
        buttons: false,
        draggable : function(calEvent, $event) {
            return calEvent.readOnly == false;

        },
        resizable : function(calEvent, $event) {
            return calEvent.readOnly == false;

        },
        freeBusyRender: function(freeBusy, $freeBusy, calendar){
        },
        height: function ($calendar) {
            return $(window).height() - $('h1').outerHeight(true);
        },
        eventRender: function (calEvent, $event) {

            var isCompany = calEvent.is_company;
            if (isCompany == 0) {
                if (calEvent.readOnly == true) {
                    $event.css('backgroundColor', '#ab1409');
                    $event.find('.wc-time').css({'display': 'none'});
                    $event.find('.wc-title').css({'display': 'none'});
                }
                else{
                    $event.css('backgroundColor', '#0275a8');
                    $event.find('.wc-title').css({'display': 'none'});
                    $event.find('.remove').css({'display': 'none'});

                }
            }
            if (isCompany == 1) {
                if ((calEvent.readOnly == true)) {
                    $event.css('backgroundColor', '#ab1409');
                    $event.find('.wc-time').css({'display': 'none'});
                    $event.find('.remove').css({'display': 'none'});
                    $event.find('.wc-title').css({'display': 'none'});
                }
                else if (calEvent.appointed_by != general_id){
                    $event.css('backgroundColor', '#0275a8');
                    $event.find('.remove').css({'display': 'none'});
                    $event.find('.wc-title').css({'display': 'none'});
                }
                else{
                    $event.css('backgroundColor', '#0275a8');
                    $event.find('.wc-title').css({'display': 'none'});
                    $event.find('.remove').css({'display': 'none'});

                }
            }



            if((calEvent.block == false) && (calEvent.readOnly == true)){
                $event.find('.wc-time').css({'display': 'none'});
                $event.css('backgroundColor', '#ab1409');


            }


            if((calEvent.block == true) && (calEvent.readOnly == true)){
                $event.find('.remove').css({'display': 'none'});
                $event.css('backgroundColor', '#ab1409');
                $event.find('.wc-time').css({'display': 'none'});
            }
            else if(calEvent.block == true){
                $event.find('.remove').css({'display': 'none'});
            }
            else if(calEvent.readOnly == true){
                $event.find('.remove').css({'display': 'none'});
            }
        },
        eventAfterRender : function (calEvent, $event) {
            if (calEvent.end.getTime() < new Date().getTime()) {
                if(calEvent.appointed_by){
                    var event_id = calEvent.id;
                    $('#calendar').weekCalendar('removeEvent', event_id);
                }
            }



        },
        eventNew: function (calEvent, $event) {
            $event.css('backgroundColor', '#0275a8');
            $event.css('z-index', '99');

            $event.find('.wc-time').css({'color': 'black', 'border':'1px solid #ab1409'});

            //eventDataAjax = {events : []};
            var json_count = parseInt(eventData.events.length + 1);
            var full_year = calEvent.start.getFullYear();
            var full_month = calEvent.start.getMonth() + 1;
            var full_date = calEvent.start.getDate();
            var full_hour = calEvent.start.getHours();
            var full_minutes = calEvent.start.getMinutes();
            var start_date = full_year + "-" + full_month + "-" + full_date + "T" + full_hour + ":" + full_minutes;

            var full_year_end = calEvent.end.getFullYear();
            var full_month_end = calEvent.end.getMonth() + 1;
            var full_date_end = calEvent.end.getDate();
            var full_hour_end = calEvent.end.getHours();
            var full_minutes_end = calEvent.end.getMinutes();
            var end_date = full_year_end + "-" + full_month_end + "-" + full_date_end + "T" + full_hour_end + ":" + full_minutes_end;
            eventDataAjax.events.push({'id': json_count, 'start_date': full_year + '-' + full_month + '-' + full_date, 'start_time': full_hour + ':' + full_minutes, 'end_date': full_year_end + '-' + full_month_end + '-' + full_date_end, 'end_time': full_hour_end + ':' + full_minutes_end});
            eventData.events.push({'id': json_count, 'start_date': full_year + '-' + full_month + '-' + full_date, 'start_time': full_hour + ':' + full_minutes, 'end_date': full_year_end + '-' + full_month_end + '-' + full_date_end, 'end_time': full_hour_end + ':' + full_minutes_end});
            console.log(eventData)
        },
        eventDrop: function (calEvent, $event) {
            var id = calEvent.id;
            calEvent.starttimestamp = calEvent.start.getTime();
            calEvent.endtimestamp = calEvent.end.getTime();
            ajax_submit(calEvent,id);

        },
        eventResize: function (calEvent, $event) {
            var id = calEvent.id;
            eventData =  changeEventDetails(id,calEvent,eventData);
            ajax_submit(eventData,id);

        },
        eventClick: function (calEvent, $event) {
            if(calEvent.is_company == general_id){
                var id = calEvent.id;
                eventData =  changeEventDetails(id,calEvent,eventData);
                ajax_submit(eventData,id);
            }

        },
        eventMouseover: function (calEvent, $event) {


        },
        eventMouseout: function (calEvent, $event) {
        },
        noEvents: function () {
            displayMessage('There are no events for this week');
        },
        calendarBeforeLoad:function(calendar){
        },
        calendarAfterLoad: function () {
            var tile_date = $("#calendar h1.wc-title").html();
            $("#title_date").html(tile_date);
        }
    });



    $('<div id="message" class="ui-corner-all"></div>').prependTo($('body'));

});
</script>
<div class="loader" style="display:none;">
    <div class="sk-circle">
        <div class="sk-circle1 sk-child"></div>
        <div class="sk-circle2 sk-child"></div>
        <div class="sk-circle3 sk-child"></div>
        <div class="sk-circle4 sk-child"></div>
        <div class="sk-circle5 sk-child"></div>
        <div class="sk-circle6 sk-child"></div>
        <div class="sk-circle7 sk-child"></div>
        <div class="sk-circle8 sk-child"></div>
        <div class="sk-circle9 sk-child"></div>
        <div class="sk-circle10 sk-child"></div>
        <div class="sk-circle11 sk-child"></div>
        <div class="sk-circle12 sk-child"></div>
    </div>
</div>
<div class="date-picker custom-calender-button text-center">
    <a href="#" class="prev-week"><i class="fa fa-arrow-left"></i></a>
    <span id="title_date"></span>
    <a href="#" class="next-week"><i class="fa fa-arrow-right"></i></a>

    <div class="date-picker-custom todays_date_button pull-right" id="date_date">
        <button class="btn todays_date js-datepicker-toggle" title="Todays Date" >
            <span class="fa fa-calendar"></span>
        </button>
        <div class="is-hidden" id="datepicker"></div>
    </div>
</div>


<div id='calendar'></div>
    <div class="text-right">
    <button id="submit" class="btn btn-primary">Done</button>
</div>





<button type="button" class="btn btn-info btn-lg" data-toggle="modal"
        data-target="#selectHours"><?php echo $words['DTL_0175']; ?></button>


<script>

    $("#submit").click(function (e) {
        var event_details = JSON.stringify(eventDataAjax);
        $.ajax({
            url: '<?php echo base_url('member/profile/new_create_appointment' . '/' .  $this->session->userdata('general_id' )); ?>',
            type: 'POST',
            data: {"event_details": event_details},
            dataType: "text",
            beforeSend: function () {
                $('.loader').css("display", "block");
            },
            success: function (response) {
                $('.loader').css("display", "none");
                window.location.href = "<?php echo base_url('member/profile/appointment_page' . '/' .  $this->uri->segment(4)); ?>";

            },
            error: function (xhr, status, error) {
                console.log(error);
                //console.log('something error occur');
            }
        });

    });








</script>
