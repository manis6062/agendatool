$(document).ready(function(){
	$(".unavailable").parents(".fc-day").unbind("click");
	$(".oldDate").parents(".fc-day").unbind("click");
	$(".fc-other-month").unbind("click");
	$.ajax({
		url: site_url+'member/profile/get_date',
		type: "POST",
		dataType: "JSON",
		data: {"id":trainerid},
		success: function(result){
			console.log(result);
			if(result.standard_availability != null)
			{
				var startStandardTime = (result.standard_availability.from_time);
				var endStandardTime = (result.standard_availability.to_time);
				
				var totalStandardTime = parseInt(endStandardTime) - parseInt(startStandardTime) + 1;
				
				$('#widget-calender').fullCalendar({
					header: {
						left: 'prevYear prev',
						center: 'title',
						right: 'next nextYear'
					},
					dayRender: function(date,cell) {
						cell.find(".fc-day-number").addClass("unavailable");
						cell.find(".unavailable").unbind("click");
						var day = date.getDay();//to get day from 0 to 6
						var year = date.getFullYear();
						var $selectableHours = $('#selectableHours');
						$selectableHours.empty();
						if(result.standard_availability!=null)
						{
							var startDay = parseInt(result.standard_availability.from_day);
							var endDay = parseInt(result.standard_availability.to_day);
							var startTime = parseInt(result.standard_availability.from_time);
							var endTime = parseInt(result.standard_availability.to_time);
							if(startDay != "" && endDay != ""){
								for(var i = startDay;i <= endDay;i++){
									if(day == i)
										cell.find(".fc-day-number").removeClass("notConfirmed unavailable").addClass("available");
								}
							}
							else{
								cell.find(".fc-day-number").removeClass("notConfirmed unavailable").addClass("available");
							}
							if(result.days!=null)
							{
								$.each(result.days,function(index,obj){
									if(day == obj)
										cell.find(".fc-day-number").removeClass("notConfirmed available").addClass("unavailable");
								});
							}
							
							if(startTime != "" && endTime != ""){
								for(var j = 0;j < startTime;j++){
									$selectableHours.append("<li>"+j+":00</li>");
								}

								for(var l = startTime;l<=endTime;l++){
									$selectableHours.append("<li class='selectee'>"+l+":00</li>");
								}

								for(var k = endTime+1;k<=23;k++){
									$selectableHours.append("<li>"+k+":00</li>");
								}
							}
							else{
								for(var j = 0;j<=23;j++)
									$selectableHours.append("<li class='selectee'>"+j+":00</li>");
							}
						}
						else
						{
							for(var j = 0;j<=23;j++)
									$selectableHours.append("<li class='selectee'>"+j+":00</li>");
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
						
						var currentDateObject = new Date();
						
						var currentDate = currentDateObject.getDate();
						var currentMonth = currentDateObject.getMonth() + 1;
						if(currentMonth < 10){
							currentMonth = "0"+currentMonth
						}
						var currentYear = currentDateObject.getFullYear();
						if(currentDate < 10)
						{
							currentDate = "0"+currentDate;
						}
						
						var currentFullDate = currentYear+"-"+currentMonth+"-"+currentDate;
						
						if(fulldate < currentFullDate)
						{
							cell.find(".fc-day-number").addClass("oldDate");
							cell.find(".oldDate").unbind("click");
						}
						
						var startStandardTime = (result.standard_availability.from_time);
						var endStandardTime = (result.standard_availability.to_time);
						
						var totalStandardTime = parseInt(endStandardTime) - parseInt(startStandardTime) + 1;
						
						
						if(result.trainer_agenda!=null)
						{
							var confirmedTime = 0;
							$.each(result.trainer_agenda,function(index,obj){
								if(obj.appoint_date == fulldate && obj.reserved_time.length != 0){
									cell.find(".fc-day-number").removeClass("unavailable available").addClass("notConfirmed");
								}
								/*else if(obj.appoint_date == fulldate && obj.is_confirm == 1 && obj.is_appointed == 1 && obj.reserved_time.length != 0){
									cell.find(".fc-day-number").removeClass("unavailable available").addClass("notConfirmed");
								}*/
								/*else */if(obj.appoint_date == fulldate && obj.is_confirm == 1 && obj.is_appointed == 1 && obj.reserved_time.length == totalStandardTime){
									cell.find(".fc-day-number").removeClass("available notConfirmed").addClass("unavailable");
									cell.find(".unavailable").unbind("click");
								}
								/*else */if(obj.appoint_date == fulldate && obj.is_confirm == 0 && obj.is_appointed == 0 && obj.reserved_time.length == 0){
									cell.find(".fc-day-number").removeClass("notConfirmed unavailable").addClass("available");
								}
								
								if(obj.appoint_date == fulldate && obj.is_confirm == 1 && obj.is_appointed == 1)
								{
									confirmedTime = confirmedTime + obj.reserved_time.length;
								}
								
								if(obj.appoint_date == fulldate && confirmedTime == totalStandardTime)
								{
									cell.find(".fc-day-number").removeClass("available notConfirmed").addClass("unavailable");
								}
								
							});
							
						}	

						//if(result.)
						
						cell.find(".unavailable").unbind("click");
						cell.find(".oldDate").unbind("click");
						cell.find(".fc-other-month").unbind("click");
					},
					dayClick: function(date) {
						$(".unavailable").parents(".fc-day").unbind("click");
						$(".unselect").unbind("click");
						$(".oldDate").parents(".fc-day").unbind("click");
						$(".fc-other-month").unbind("click");
						$(".proceedBtn ").hide(400);
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
						var $selectableHours = $('#selectableHours');
						$selectableHours.empty();

						if(result.standard_availability!=null)
						{
							var startDay = parseInt(result.standard_availability.from_day);
							var endDay = parseInt(result.standard_availability.to_day);
							var startTime = parseInt(result.standard_availability.from_time);
							var endTime = parseInt(result.standard_availability.to_time);
							if(startTime != "" && endTime != ""){
								for(var j = 0;j < startTime;j++){
									$selectableHours.append("<li class='unselect' time='"+j+"'>"+j+":00</li>");
								}

								for(var l = startTime;l<=endTime;l++){
									$selectableHours.append("<li class='selectee' time='"+l+"'>"+l+":00</li>");
								}

								for(var k = endTime+1;k<=23;k++){
									$selectableHours.append("<li class='unselect' time='"+k+"'>"+k+":00</li>");
								}
							}
							else{
								for(var j = 0;j<=23;j++)
									$selectableHours.append("<li class='selectee'>"+j+":00</li>");
							}
						}	
						else{
							for(var j = 0;j<=23;j++)
									$selectableHours.append("<li class='selectee'>"+j+":00</li>");
						}
						if(result.trainer_agenda!=null)
						{
							$.each(result.trainer_agenda,function(index,obj){
								if(fullDate == obj.appoint_date){
									$.each(obj.reserved_time,function(index,obje){
										var work_time = obje.work_time;
										$("#selectableHours li").each(function(){
											var li_time = $(this).attr("time");
											if(li_time == work_time){
												$(this).removeClass("selectee");
											}
										});
									});
								}
							});
						}
						if(result.times!=null)
						{
							$.each(result.times,function(index,obje){
								var removed_time = obje;
								$("#selectableHours li").each(function(){
									var li_time = $(this).attr("time");
									if(li_time == removed_time){
										$(this).removeClass("selectee");
									}
								});
							});
						}
						$("#datetime").val(fullDate);
						$("#selected_date").val(fullDate);
						$("#upload").change(function(){
							var filename = $(this).val();
							var lastIndex = filename.lastIndexOf("\\");
							if (lastIndex >= 0) {
								filename = filename.substring(lastIndex + 1);
							}
							$('#filename').val(filename);
						});
						$('button[data-target="#selectHours"]').trigger('click');
					}
				});	
			}
		},
		complete: function() {
			$(".unavailable").parents(".fc-day").unbind("click");
			$(".oldDate").parents(".fc-day").unbind("click");
			$(".fc-other-month").unbind("click");
			$(".fc-button-prev").click (function(){
				$(".unavailable").parents(".fc-day").unbind("click");
				$(".oldDate").parents(".fc-day").unbind("click");
				$(".fc-other-month").unbind("click");
			});
			$(".fc-button-next").click(function(){
				$(".unavailable").parents(".fc-day").unbind("click");
				$(".oldDate").parents(".fc-day").unbind("click");
				$(".fc-other-month").unbind("click");
			});
		},
		beforeSend: function() {
			$(".unavailable").parents(".fc-day").unbind("click");
			$(".oldDate").parents(".fc-day").unbind("click");
			$(".fc-other-month").unbind("click");
		}
	}).done(function(){
		$(".unavailable").parents(".fc-day").unbind("click");
		$(".oldDate").parents(".fc-day").unbind("click");
		$(".fc-other-month").unbind("click");
	});

	
	
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
	
	//Control key disable while clicking
	/*$("#selectableHours").on("selectablestart", function (event, ui) {
		event.originalEvent.ctrlKey = false;
	});*/
	
	
	$('#selectHours').on('hide.bs.modal', function(e){
		if($('#reserve_form').is(':visible')){
			toggling();
		}
	});
	

});



function toggling() {
		$(".toggled").toggle(0);
};

