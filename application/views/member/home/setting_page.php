      <script>
      $('#api_submit_btn').on('click',function(){
    var id = $('#api_id').val();
    var api_key = $('#api_key').val();
    if(api_key == "")
    {
      alert('Api key field should not be empty.');
    }
    else
    {
      $.ajax({
        url: site_url+'admin/home/save_api',
        type: "POST",
        dataType: "JSON",
        data:{"id":id,"api_key":api_key},
        success: function(result){
          window.location.reload();
          //$("#apis").reload();
        }
      });
    }
  });
    $(document).ready(function(){
      $("#change_password_tab").on('click',function(){
        $.ajax({
           type: "POST",
           url: "<?php echo site_url('admin/home/set_session')?>"+'/change_password'
         });
      });
      $("#export_tab").on('click',function(){
        $.ajax({
           type: "POST",
           url: "<?php echo site_url('admin/home/set_session')?>"+'/export'
         });
      });
      $("#email_address_tab").on('click',function(){
        $.ajax({
           type: "POST",
           url: "<?php echo site_url('admin/home/set_session')?>"+'/email_address'
         });
      });
      $("#trial_period_tab").on('click',function(){
        $.ajax({
           type: "POST",
           url: "<?php echo site_url('admin/home/set_session')?>"+'/trail_period'
         });
      });
      $("#active_time_tab").on('click',function(){
        $.ajax({
           type: "POST",
           url: "<?php echo site_url('admin/home/set_session')?>"+'/active_time'
         });
      });
      $("#api_tab").on('click',function(){
        $.ajax({
           type: "POST",
           url: "<?php echo site_url('admin/home/set_session')?>"+'/api'
         });
      });
      $("#day_tab").on('click',function(){
        $.ajax({
           type: "POST",
           url: "<?php echo site_url('admin/home/set_session')?>"+'/day'
         });
      });
      $("#time_tab").on('click',function(){
        $.ajax({
           type: "POST",
           url: "<?php echo site_url('admin/home/set_session')?>"+'/time'
         });
      });
      $('.edit_sub_btn').on('click',function(){
        var obj = $(this);
        var id = obj.attr('info');
        if(id == 4)
        {
          $('#modal_title').html("<?php echo $words['DTL_0099'];?> <?php echo $words['DTL_0267'];?>");
        }
        else if(id == 3)
        {
          $('#modal_title').html("<?php echo $words['DTL_0099'];?> <?php echo $words['DTL_0062'];?>");
        }
        $('#edit_sub_id').val(id);
        $('#subscription_time_modal').modal('show');
      });
    });

    </script>
<div class="content-header">
<h2 class="content-header-title"> <?php echo $words['DTL_0246'];?></h2>
<ol class="breadcrumb">
          <li><a href="<?php echo site_url('admin/home') ?>"><?php echo $words['DTL_0122'];?></a></li>
          <li class="active"><?php echo $words['DTL_0246'];?></li>
        </ol>
</div>
<div class="row">
    <div class="col-md-3 col-sm-5">       
          <ul id="myTab1" class="nav nav-pills nav-stacked" >
            <li class="<?php if($this->session->userdata('tab_index')=='' || $this->session->userdata('tab_index')=='email_address') echo 'active';?>" id="email_address_tab">
              <a href="#admin_email" data-toggle="tab"><i class="fa fa-envelope"></i>&nbsp;&nbsp;<?php echo $words['DTL_0026'];?></a>
            </li>
            <li class="<?php if($this->session->userdata('tab_index')=='trail_period') echo 'active';?>" id="trial_period_tab">
              <a href="#trial_period" data-toggle="tab"><i class="fa fa-list-alt"></i>&nbsp;&nbsp;<?php echo $words['DTL_0272'];?></a>
            </li>
			<li class="<?php if($this->session->userdata('tab_index')=='active_time') echo 'active';?>" id="active_time_tab">
				<a href="#active_time" data-toggle="tab"><i class="fa fa-bolt"></i>&nbsp;&nbsp;<?php echo $words['DTL_0004'];?></a>
			</li>
			<li class="<?php if($this->session->userdata('tab_index')=='export') echo 'active';?>" id="export_tab">
              <a href="#export" data-toggle="tab"><i class="fa fa-external-link-square"></i>&nbsp;&nbsp;<?php echo $words['DTL_0111'];?></a>
            </li>
			<li class="<?php if($this->session->userdata('tab_index')=='change_password') echo 'active';?>" id="change_password_tab">
              <a href="#change_password" data-toggle="tab"><i class="fa fa-lock"></i>&nbsp;&nbsp;<?php echo $words['DTL_0051'];?></a>
            </li>
			<li class="<?php if($this->session->userdata('tab_index')=='api') echo 'active';?>" id="api_tab">
              <a href="#apis" data-toggle="tab"><i class="fa fa-cogs"></i>&nbsp;&nbsp;APIs</a>
            </li>
            <li class="<?php if($this->session->userdata('tab_index')=='day') echo 'active';?>" id="day_tab">
              <a href="#days" data-toggle="tab"><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Day Selection</a>
            </li>
            <li class="<?php if($this->session->userdata('tab_index')=='time') echo 'active';?>" id="time_tab">
              <a href="#times" data-toggle="tab"><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Time Selection</a>
            </li>
          </ul>
	</div>
	<div class="col-md-9 col-sm-7">
          <div id="myTab1Content" class="tab-content">
            <div class="tab-pane fade <?php if($this->session->userdata('tab_index')=='' || $this->session->userdata('tab_index')=='email_address') echo 'active in';?>" id="admin_email">

      
              <h3>
                <?php echo $words['DTL_0048'];?>
              </h3>

              <form class="form form-horizontal" method="POST" action="<?php echo site_url('admin/home/change_admin_email');?>">

                <div class="form-group">
                <label class="col-md-3"><?php echo $words['DTL_0079'];?>: </label> <div class="col-md-7">
                <input type="text" value="<?php echo $userdata['email_address'];?>" class="form-control" disabled=""> </div>
                
                 </div>

                 <hr>

                <div class="form-group">

        		
                  <label for="option" class="col-md-3"><?php echo $words['DTL_0105'];?></label>

                  <div class="col-md-7">
				 <!--  <?php if(form_error('email_address')){?><span class="validation_error_msg"><?php echo form_error('email_address'); ?></span><?php } ?> -->
                  <input type="text" class="form-control option" placeholder="Email Address" name="email_address">
                  <?php if(form_error('email_address')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('email_address'); ?></li></ul>
                  <?php } ?>
                   </div>

                </div> <!-- /.form-group -->
        		
        		<br/>
              <div class="form-group">
                  
                <div class="col-md-7 col-md-push-3">
                  <button  type="submit" class="btn btn-primary" id="save_email_address_admin">
                    <?php echo $words['DTL_0224'];?>
                  </button>

            			<a href="<?php echo site_url('admin/home')?>" class="btn btn-default"><?php echo $words['DTL_0045'];?></a>

                  </div>
            		</div>
            		
              </form>
          
  
            </div>

            <div class="tab-pane fade <?php if($this->session->userdata('tab_index')=='trail_period') echo 'active in';?>" id="trial_period">
              <div class="row">

					<div class="col-md-12">

					  <h3>
					   <?php echo $words['DTL_0273'];?>
					  </h3>

					  <table class="table table-bordered table-highlight media-table">
						<thead>
						  <tr>
							<th>#</th>
							<th><?php echo $words['DTL_0171'];?></th>
							<th><?php echo $words['DTL_0284'];?></th>
							<th><?php echo $words['DTL_0253'];?></th>
						  </tr>
						</thead>
						<tbody>
						<?php 
						  $count=1;
						  foreach($trial_periods as $trial_period){
						?>
						  <tr>
							<td><?php echo $count?></td>
							<td><?php echo $trial_period['trial_period_time']?></td>
							<td><?php if($trial_period['user_type_id']==2)
										{
											echo "Company";
										}
										elseif($trial_period['user_type_id']==3)
										{
											echo "Trainer";
										}
							?></td>
							<td>
							  <button class="btn btn-xs btn-secondary edit_btn" info="<?php echo $trial_period['id']?>"><i class="fa fa-pencil"></i></button>
							</td>
						  </tr>
						  <?php 
							$count++;
							}
						  ?>
						</tbody>
					  </table>              

					</div> <!-- /.col -->

				  </div> <!-- /.row -->
            </div>
			<div class="tab-pane fade <?php if($this->session->userdata('tab_index')=='active_time') echo 'active in';?>" id="active_time">
			<div class="row">

   

        <div class="col-md-12">

          <h3>
           <?php echo $words['DTL_0005'];?>
          </h3>

          <table class="table table-bordered table-highlight media-table">
            <thead>
              <tr>
                <th>#</th>
                <th><?php echo $words['DTL_0171'];?></th>
                <th><?php echo $words['DTL_0284'];?></th>
                <th><?php echo $words['DTL_0253'];?></th>
              </tr>
            </thead>
            <tbody>
            <?php 
              $count=1;
              foreach($subscriptions as $subscription){
            ?>
              <tr>
                <td><?php echo $count?></td>
                <td><?php echo $subscription['trial_period_time']?></td>
                <td><?php if($subscription['user_type_id']==2)
							{
								echo "Company";
							}
							elseif($subscription['user_type_id']==3)
							{
								echo "Trainer";
							}
				?></td>
				<td>
                  <button class="btn btn-xs btn-secondary edit_sub_btn" info="<?php echo $subscription['id']?>"><i class="fa fa-pencil"></i></button>
				</td>
              </tr>
              <?php 
                $count++;
                }
              ?>
            </tbody>
          </table>              

        </div> <!-- /.col -->

      </div> <!-- /.row -->
			</div>

			
			  <div class="tab-pane fade <?php if($this->session->userdata('tab_index')=='export') echo 'active in';?>" id="export">


         <h3>
           <?php echo $words['DTL_0286'];?>
          </h3>
            <form class="filter_block" method="post" action="<?php echo site_url('admin/export/csv_create')?>" >
             <div class="row">

              <div class="col-md-4">

                <div class="form-group">
                    <label><?php echo $words['DTL_0155'];?>: </label> <input type="text" id="" name="searchByName"  class="form-control"> 
                </div>

              </div>


               <div class="col-md-4">

                <div class="form-group">
                 <label> <?php echo $words['DTL_0285'];?>: </label><input type="text" id="" name="searchByUserName"  class="form-control">
                </div>

              </div>



               <div class="col-md-4">

                <div class="form-group">
                 <label> <?php echo $words['DTL_0105'];?>: </label><input type="text" id="" name="searchByEmailAddress"  class="form-control">
                </div>

              </div>

            </div>
          
          <div class="form-group double-input">
          
            <label>Select Date:</label> 
             
            <div class="row">
              <div class="col-md-12">
    
                  <div class="row">
                      <div class="col-sm-6 col-md-4">
                        <input class="form-control" type="text" placeholder="Start date" id="dpStart" data-date-format="yyyy-mm-dd" data-date-autoclose="true" name="from_date" >
                      </div>

                      <div class="col-sm-6 col-md-4">
                        <input class="form-control" type="text" placeholder="End date" id="dpEnd" data-date-format="yyyy-mm-dd" data-date-autoclose="true" name="to_date" >
                      </div>
                  </div>
              </div> <!-- /.col -->
            </div>



        </div> <!-- /.row -->
            

            <div class="notification_filter form-group double-input">

            
              <label> <?php echo $words['DTL_0280'];?>:</label>
                


                <div class="row">
               <div class="col-md-12">

               
                   <div class="row">
                  <div class="col-sm-6 col-md-4">
                <select   name="filter" class="form-control">
                  <option value="Trainer"><?php echo $words['DTL_0267'];?></option>
                  <option value="Company"><?php echo $words['DTL_0062'];?></option>
                </select>
                    </div>
                     <div class="col-sm-6 col-md-4">
                <select   name="active_status" class="form-control">
                  <option value="Active"><?php echo $words['DTL_0003'];?></option>
                  <option value="Inactive"><?php echo $words['DTL_0341'];?></option>
                </select>

                  </div>
                  </div>
               
             
            </div>
            </div>
            </div>  

              <input type="submit" name="submit" class="btn btn-primary">
                
            </div>
			</form>
			  <div class="tab-pane fade <?php if($this->session->userdata('tab_index')=='change_password') echo 'active in';?>" id="change_password">
              
		
     <h3>
           <?php echo $words['DTL_0049'];?>
          </h3>
	  
      <form class="form-horizontal" method="POST" action="<?php echo site_url('admin/home/change_admin_password');?>">
	  
	  <div class="form-group">

                  <label class="col-md-3"><?php echo $words['DTL_0172'];?></label>

                  <div class="col-md-7">
                    <input type="password" name="current_password" class="form-control option" value="" />
                    <?php if(form_error('current_password')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('current_password'); ?></li></ul>
                  <?php } ?>
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->

                <hr />
		
		
		 <div class="form-group">

                  <label class="col-md-3"><?php echo $words['DTL_0158'];?></label>

                  <div class="col-md-7">
                    <input type="password" name="new_password" class="form-control option" value=""/>
                    <?php if(form_error('new_password')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('new_password'); ?></li></ul>
                  <?php } ?>
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->
		
		<div class="form-group">

                  <label class="col-md-3"><?php echo $words['DTL_0159'];?></label>

                  <div class="col-md-7">
                    <input type="password" name="confirm_password" class="form-control option" value=""/>
                    <?php if(form_error('confirm_password')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('confirm_password'); ?></li></ul>
                  <?php } ?>
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->
		 <br />

                <div class="form-group">

                  <div class="col-md-7 col-md-push-3">
                    <button type="submit" class="btn btn-primary" id="save_email_address_admin" ><?php echo $words['DTL_0224'];?></button>
                    &nbsp;
                    <a href="<?php echo site_url('admin/home')?>"><button type="reset" class="btn btn-default"><?php echo $words['DTL_0045'];?></button></a>
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->

		
		
		
      </form>
            </div>
			
			  <div class="tab-pane fade <?php if($this->session->userdata('tab_index')=='api') echo 'active in';?>" id="apis">

           <h3>
            
            API <?php echo $words['DTL_0131'];?>
          </h3>

              <div class="portlet">
      
        <div class="portlet-header">
      
          <h3>
            <i class="fa fa-tasks"></i>
            Linkedin
          </h3>
      
        </div> <!-- /.portlet-header -->
      
        <div class="portlet-content">
		<div class="form-horizontal">
			<?php 
      $count = 1;
			if(!empty($apis))
			foreach($apis as $api){
        if($count == 5)
          break;?>
            <div class="form-group">
              <label class="col-md-3 api_name_class"><?php echo $api['api_name'];?></label>

              <div class="col-md-7">
                <p class="api_key_class"><?php echo $api['api_key'];?></p>
              </div>
			  
			  <div class="col-md-2 text-center-md">
			  <button class="btn btn-secondary api_edit_btn" api_id="<?php echo $api['id']?>"><?php echo $words['DTL_0091'];?></button>
			  </div>
            </div>
			<?php $count++; }?>
			</div>
        </div> <!-- /.portlet-content -->
      
      </div> <!-- /.portlet -->
      <div class="portlet">
      
        <div class="portlet-header">
      
          <h3>
            <i class="fa fa-tasks"></i>
            Google
          </h3>
      
        </div> <!-- /.portlet-header -->
      
        <div class="portlet-content">
    <div class="form-horizontal">
      <?php 
      $count = 1;
      if(!empty($apis))
      foreach($apis as $api){
        if($count < 5)
        {
          $count++;
          continue; }?>
            <div class="form-group">
              <label class="col-md-3 api_name_class"><?php echo $api['api_name'];?></label>

              <div class="col-md-7">
                <p class="api_key_class"><?php echo $api['api_key'];?></p>
              </div>
        
        <div class="col-md-2 text-center-md">
        <button class="btn btn-secondary api_edit_btn" api_id="<?php echo $api['id']?>"><?php echo $words['DTL_0091'];?></button>
        </div>
            </div>
      <?php  }?>
      </div>
        </div> <!-- /.portlet-content -->
      
      </div> <!-- /.portlet -->
            </div>
            <div class="tab-pane fade <?php if($this->session->userdata('tab_index')=='day') echo 'active in';?>" id="days">


              <div class="portlet">
      
        <div class="portlet-header">
            
           <h3>
            <i class="fa fa-tasks"></i>
            Day Selection
          </h3>

             </div> <!-- /.portlet-header -->

               <div class="portlet-content">

          <form class="form" action="<?php echo site_url('admin/day/save_day')?>" method="post">
          <input type="hidden" name="sunday" value="0">
          <input type="hidden" name="monday" value="0">
          <input type="hidden" name="tuesday" value="0">
          <input type="hidden" name="wednesday" value="0">
          <input type="hidden" name="thursday" value="0">
          <input type="hidden" name="friday" value="0">
          <input type="hidden" name="saturday" value="0">
            <div class="form-group">
           <div class="checkbox">
                    <label>
          <input type="checkbox" name="sunday" value='1' <?php if($sunday == 1) echo "checked"?>>Sunday
            </label>
          </div>
           <div class="checkbox">
                    <label>
          <input type="checkbox" name="monday" value='1' <?php if($monday == 1) echo "checked"?>>Monday 
              </label>
          </div>
           <div class="checkbox">
                    <label>
          <input type="checkbox" name="tuesday" value='1' <?php if($tuesday == 1) echo "checked"?>>Tuesday
               </label>
          </div>
           <div class="checkbox">
                    <label>
          <input type="checkbox" name="wednesday" value='1' <?php if($wednesday == 1) echo "checked"?>>Wednesday
               </label>
          </div>
           <div class="checkbox">
                    <label>
          <input type="checkbox" name="thursday" value='1' <?php if($thursday == 1) echo "checked"?>>Thursday     </label>
          </div>
           <div class="checkbox">
                    <label>
          <input type="checkbox" name="friday" value='1' <?php if($friday == 1) echo "checked"?>>Friday
               </label>
          </div>
           <div class="checkbox">
                    <label>
          <input type="checkbox" name="saturday" value='1' <?php if($saturday == 1) echo "checked"?>>Saturday     </label>
          </div>
          
          <input class="btn btn-primary" type="submit" value="Save">
        </div>
        </form>
            </div>
            </div>
            </div>

            <div class="tab-pane fade <?php if($this->session->userdata('tab_index')=='time') echo 'active in';?>" id="times">

                <div class="portlet">
      
        <div class="portlet-header">
            

          

           <h3>
             <i class="fa fa-tasks"></i>
            Time Selection
          </h3>

             </div> <!-- /.portlet-header -->
              <div class="portlet-content">

          <form class="form" action="<?php echo site_url('admin/day/save_time');?>" method="post">
          <?php for($i = 0; $i <= 23; $i++){?>
            <input type="hidden" name="<?php echo $i;?>" value="0">
          <?php }?>
            <div class="time_wrapper">
          <?php for($i = 0; $i <= 23; $i++){?>
              <div class="form-group">
                 <div class="checkbox">
                    <label>
                      <input type="checkbox" name="<?php echo $i;?>" value='1' <?php if($time[$i] == 1) echo "checked"?>>
                          <?php echo $i.":00";?> -
                          <?php
                          if($i == 23) {
                              echo "0:00";
                          }
                          else {
                              echo ($i+1).":00";
                          }?>
                    </label>
                </div>
              </div>
          <?php }?>
            </div>
              <input class="btn btn-primary" type="submit" value="Save">

              <!--   <input class="btn btn-primary" type="submit" value="Save"> -->
        </form>
            </div>
            </div>
            </div>
          </div>
    
    

  
           
         
              </div> <!-- /.col -->

            </div> <!-- /.row -->

            <script>
              function validate_agendaform(){
                var result;
                if($('#appointmentName').val()=="" || $('#location').val()=="")
                {
                  if($('#appointmentName').val()=="")
                    alert('Appointment name '+"<?php echo $words['DTL_0301'];?>");
                  if($('#location').val()=="")
                    alert('Location '+"<?php echo $words['DTL_0301'];?>");
                  result =  false;
                }
                
                else
                  result = true;
                return result;
                
              }
</script>