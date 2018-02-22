    $(document).ready(function(){


      $("#json-one").change(function() {
		ids = new Array();
		$("#json-one option").each(function(){
			if($(this).is(":checked")) {
				ids.push($(this).val());
			}
		});
        ids = $("#json-one").val();
        $.ajax({
         url: site_url+'jsondata/get_sub_category',
         type: "POST",
         dataType:"JSON",
         data:{"ids":ids},
		 beforeSend: function(){
				$('#notification_loader_speciality').show('slow');
		},
		complete: function() {
			$('#notification_loader_speciality').hide('slow');
		},
         success: function(result)
         { 
             var $jsontwo = $("#json-two");
			 $jsontwo.empty();
             for(var i=0;i<result.length;i++){
              $jsontwo.append("<option value="+result[i].id+">" + result[i].category_name + "</option>");

           }
         },
         error : function(jqXHR,textStatus,errorThrown)
         {
          alert('An error has occured');
         }
       });
      });

      $("#json-two").change(function() {
		ids = new Array();
		$("#json-two option").each(function(){
			if($(this).is(":checked")) {
				ids.push($(this).val());
			}
		});
        var ids = $("#json-two").val();
        $.ajax({
         url: site_url+'jsondata/get_sub_category',
         type: "POST",
         dataType:"JSON",
         data:{"ids":ids},
		 beforeSend: function(){
				$('#notification_loader_speciality').show('slow');
		},
		complete: function() {
			$('#notification_loader_speciality').hide('slow');
		},
         success: function(result)
         { 
             var $jsonthree = $("#json-three");
             $jsonthree.empty();
             for(var i=0;i<result.length;i++){
              $jsonthree.append("<option value="+result[i].id+">" + result[i].category_name + "</option>");

           }
         },
         error : function(jqXHR,textStatus,errorThrown)
         {
          alert('An error has occured');
         }
       });
      });
		
	$('.edit_btn').on('click',function(){
		var obj = $(this);
		var id = obj.attr('info');
		
		$('#edit_id').val(id);
		$('#trial_period_modal').modal('show');
	});
	
	
	
	$('#add_subscription_btn').on('click',function(){
		$('#search_form').show();
	});
	
	$('#search_user_btn').on('click',function(){
		var search_param = $('#search_user_txt').val();
		$.ajax({
			url: site_url+'admin/payment/search_user',
			type: "POST",
			dataType:"JSON",
			data: {"search_param": search_param},
			success: function(result){
				if(result!=null)
				{
					$('#no_user').hide();
					$('#username_subs').html(result.name);
					$('#amount').val("");
					$('#general_id_for_subs').val(result.general_id);
					$('#user_type_id_for_subs').val(result.user_type_id);
					$('#add_subscription_modal').modal('show');
				}
				else
				{
					$('#no_user').show();
				}
			}
		});
	});
	
    });