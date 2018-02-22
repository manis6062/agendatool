$(document).ready(function(){
	$.ajax({
		url: '<?php echo site_url('member/profile/get_date')?>',
		type: "POST",
		dataType:"JSON",
		data:{"id":<?php echo $trainerdata['id']?>},
		success: function(result){
			$('#widget-calender').fullCalendar({

				header: {
					left: 'prevYear prev',
					center: 'title',
					right: 'next nextYear'
				},
				dayRender: function(date,cell) {
					cell.find(".fc-day-number").addClass("unavailable");
var day = date.getDay();//to get day from 0 to 6
var year = date.getFullYear();
var month = date.getMonth() + 1 ;
if(month < 10 ){
	month = "0"+month;
}
var d = date.getDate();
if(d < 10 ){
	d = "0"+d;
}
var startDay = parseInt(result.standard_availability.from_day);
var endDay = parseInt(result.standard_availability.to_day);
var startTime = parseInt(result.standard_availability.from_time);
var endTime = parseInt(result.standard_availability.to_time);
if(startDay != "" && endDay != ""){
	for(var i = startDay;i <= endDay;i++)
	{
		if(day == i)
			cell.find(".fc-day-number").removeClass("unavailable").addClass("available");
	}
}
else{
	cell.find(".fc-day-number").removeClass("unavailable").addClass("available");
}
var $selectableHours = $('#selectableHours');
$selectableHours.empty();
if(startTime != "" && endTime != "")
{
	for(var j = 0;j < startTime;j++)
	{
		$selectableHours.append("<li>"+j+":00</li>");
	}

	for(var l = startTime;l<=endTime;l++)
	{
		$selectableHours.append("<li class='selectee'>"+l+":00</li>");
	}

	for(var k = endTime+1;k<=23;k++)
	{
		$selectableHours.append("<li>"+k+":00</li>");
	}
}
else
{
	for(var j = 0;j<=23;j++)
		$selectableHours.append("<li>"+j+":00</li>");
}

var day = date.getDay();//to get day from 0 to 6
var year = date.getFullYear();
var month = date.getMonth() + 1 ;
if(month < 10 ){
	month = "0"+month;
}
var d = date.getDate();
if(d < 10 ){
	d = "0"+d;
}
var fulldate = year+"-"+month+"-"+d;

$.each(result.trainer_agenda,function(index,obj){
	if(fulldate == obj.appoint_date && obj.is_confirm == 0)
	{
		cell.find(".fc-day-number").removeClass("unavailable available").addClass("notConfirmed");
	}
	else if(fulldate == obj.appoint_date && obj.is_confirm == 1)
	{
		cell.find(".fc-day-number").addClass("unavailable");

	}
});

},
dayClick: function(date, jsEvent, view) {
	var year = date.getFullYear();
	var month = date.getMonth() + 1 ;
	if(month < 10 ){
		month = "0"+month;
	}
	var d = date.getDate();
	if(d < 10 ){
		d = "0"+d;
	}

// function to set chosen date 
var fullDate = year+"-"+month+"-"+d;
$('.modal-title').html(fullDate);
var startDay = parseInt(result.standard_availability.from_day);
var endDay = parseInt(result.standard_availability.to_day);
var startTime = parseInt(result.standard_availability.from_time);
var endTime = parseInt(result.standard_availability.to_time);
var $selectableHours = $('#selectableHours');
$selectableHours.empty();
if(startTime != "" && endTime != "")
{
	for(var j = 0;j < startTime;j++)
	{
		$selectableHours.append("<li time='"+j+"'>"+j+":00</li>");
	}

	for(var l = startTime;l<=endTime;l++)
	{
		$selectableHours.append("<li class='selectee' time='"+l+"'>"+l+":00</li>");
	}

	for(var k = endTime+1;k<=23;k++)
	{
		$selectableHours.append("<li time='"+k+"'>"+k+":00</li>");
	}
}
else
{
	for(var j = 0;j<=23;j++)
		$selectableHours.append("<li>"+j+":00</li>");
}
$.each(result.trainer_agenda,function(index,obj){ 
	if(fullDate == obj.appoint_date)
	{
		$.each(result.reserved_time,function(index,obj){
			var work_time = obj.work_time;
			$("#selectableHours li").each(function(){
				var li_time = $(this).attr("time");
				if(li_time == work_time) {
					$(this).removeClass("selectee");
				}
			});
		})
	}
});

$("#reserveAppointment-form").append("<input type='hidden' name='date' id='datetime' value='"+fullDate+"'>");
$('button[data-target="#selectHours"]').trigger('click');
}
});
};
});
$(".unavailable").parents(".fc-day").unbind("click");


var titleYear;
var d = new Date();
var y = d.getFullYear();
$(".prev-year").text(y - 1);
$(".next-year").text(y + 1);

$(".fc-button-next").mousedown(function() {
	var title = $(".fc-header-title h2").text();
	titleYear = title.split(" ");
});

$(".fc-button-prev").mousedown(function() {
	var title = $(".fc-header-title h2").text();
	titleYear = title.split(" ");
});

$(".year").click(function() {
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

$(".fc-button-next").click(function() {
	callMonth();
});

$(".fc-button-prev").click(function() {
	callMonth();
});



$("#selectableHours").selectable({
	stop: function() {
		var count = 0;
		var data = new Array();
		$( ".ui-selected", this ).each(function() {
			var index = $( "#selectableHours li" ).index( this );
			data[count] = index;
			count++;
		});
		$("#reserveAppointment-form").append("<input type='hidden' name='selectedTime' value='"+data+"'>");
		$(".proceedBtn ").show(400);
	},
	filter: ".selectee"
});

$("#selectableHours").on("selectablestart", function (event, ui) {
	event.originalEvent.ctrlKey = false;
});



function toggling() {
	$(".toggled").toggle(0);
};
});