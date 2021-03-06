<script>
$("#json-three").change(function() {
		ids = new Array();
		text = new Array();
		$("#json-three option").each(function(){
			if($(this).is(":checked")) {
					ids.push($(this).val());
					text.push($(this).text());
					$(this).remove();
			}
		});
		var $speciality_list = $('#speciality_list');
		if(ids.length >3)
		{
			alert("<?php echo $words['DTL_0336']?>");
		}
		else
		{
			for(var i=0 ; i < ids.length ; i++)
			{	
				$speciality_list.append('<li class="select2-search-choice"><div>'+text[i]+'</div><span class="select2-search-choice-close" tabindex="-1"></span><input type="hidden" name="speciality_ids[]" value="'+ids[i]+'"/></li>');
			}
			$(".select2-search-choice-close").unbind("click");
			$(".select2-search-choice-close").click(function(){
					$("#json-three").append("<option value="+$(this).parent().find("input[type='hidden']").val()+">"+$(this).parent().text()+"</option>");
					$(this).parent().remove();
				});
		}
      });
	  </script>

        <div class="content-header">
            <h2 class="content-header-title"><?php echo $words['DTL_0233'];?></h2>
            <ol class="breadcrumb">
                    <li><a href="<?php echo site_url('member/profile') ?>"><?php echo $words['DTL_0205'];?></a></li>
                    <li class="active"><?php echo $words['DTL_0233'];?></li>
                  </ol>
        </div>


      <div class="portlet">
      
        <div class="portlet-header">
      
          <h3>
            <i class="fa fa-search"></i>
            <?php echo $words['DTL_0226'];?>
          </h3>
      
        </div> <!-- /.portlet-header -->
      
        <div class="portlet-content">
      
          <form class="form-horizontal" role="form" action="<?php echo site_url('member/search/search_user')?>" method="post">

            <div class="form-group">
              <label class="col-md-2"><?php echo $words['DTL_0227'];?>: </label>

              <div class="col-md-5">
                <input type="text" class="form-control" placeholder="Name" id="name" name="name">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-2"><?php echo $words['DTL_0232'];?>: </label>

              <div class="col-md-5">
                <input type="text" class="form-control" placeholder="Speciality" id="search_speciality" name="search_speciality">
              </div>
			  <img src="<?php echo base_url()?>assets/img/loader.gif" style="display: none;" width="25" height="25" id="notification_loader_search_speciality_alone">
            </div>

            <div class="form-group">
              <label class="col-md-2"><?php echo $words['DTL_0230'];?>: </label>
            </div>
            <div class="form-group search-select">
            <div class="col-sm-12 col-md-11 ">
              <div class="row">
              <div class="col-sm-4">
              <div class="search-title"><?php echo $words['DTL_0046'];?></div>
              <div class="form-group">
                  <select multiple="multiple" id="json-one" class="form-control">
                  <?php foreach($main_specialities as $speciality){?>
                  <option value="<?php echo $speciality['id']?>"><?php echo $speciality['category_name']?></option>
                  <?php } ?>
                </select>
                </div>
                </div>
                <div class="col-sm-4">
                <div class="search-title"><?php echo $words['DTL_0254'];?></div>
                <div class="form-group">
                <select multiple="multiple" id="json-two" class="form-control">
                </select>
                </div>
              </div>
              <div class="col-sm-4">
              <div class="search-title"><?php echo $words['DTL_0249'];?></div>
                <div class="form-group">
                <select multiple="multiple" id="json-three" class="form-control" name="speciality_id[]">
                </select>
                </div>
              </div>
              </div>
              </div>
			  <img src="<?php echo base_url()?>assets/img/loader.gif" style="display: none;" width="25" height="25" id="notification_loader_search_speciality">
              </div>
            <div class="form-group search-blck">
              <label class="col-md-2"><?php echo $words['DTL_0142'];?>: </label> 
              
              <div class="col-md-10">
                <div class="row">
                  <div class="col-md-4">
                    <input type="text" id="location" name="location" class="form-control">
                  </div>
                  <label class="col-md-2"><?php echo $words['DTL_0208'];?>: </label>
                  <div class="col-md-4">
                  <select id="range" name="range" class="form-control">   
					  <option value=""><?php echo $words['DTL_0055'];?></option>
                      <option value="10">10</option>
                      <option value="15">15</option>
                      <option value="25">25</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                  </select>
              </div>
                </div>
              </div>
            </div>
			<input type="hidden" id="longitude" name="longitude" value="<?php echo set_value('longitude'); ?>"/>
			<input type="hidden" id="latitude" name="latitude" value="<?php echo set_value('latitude'); ?>" />


            <div class="form-group search-blck">
              <label class="col-md-2"><?php echo $words['DTL_0043'];?>: </label> 
              
              <div class="col-md-10">
                <div class="row">
                  <div class="col-md-4">
					<div class="available_date_wrapper" >
						 <input id="available_date" class="form-control" type="text" name="availability" id="availability">
						<!-- <span class="input-group-addon"><i class="fa fa-calendar"></i></span>-->
                	</div>
                  </div>
                  <label class="col-md-2"><?php echo $words['DTL_0263'];?>: </label>
                  <div class="col-md-4">
                   <div class="input-group bootstrap-timepicker">
                    <input id="tp-ex-1" type="text" class="form-control" name="search_time">
                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
					</div>
                  </div>
                </div>
            </div>
			<input type="hidden" id="address_longitude" name="address_longitude"/>
			<input type="hidden" id="address_latitude" name="address_latitude"/>
            <div class="col-md-10">
           
            	<input type="submit" class="btn btn-danger" value="Search" name="submit" id="submit">
            
            </div>
		</div>
	  </form>
</div>

        </div> <!-- /.portlet-content -->
      
      </div> <!-- /.portlet -->        

    </div> <!-- /.content-container -->
      
  </div> <!-- /.content -->

</div> <!-- /.container -->
