<script>

$(document).ready(function(){

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
			alert("<?php echo $words['DTL_0336'];?>");
		}
		else
		{
			for(var i=0 ; i < ids.length ; i++)
			{	
				$speciality_list.append('<li class="select2-search-choice"><div>'+text[i]+'</div><span class="select2-search-choice-close" tabindex="-1"></span><input type="hidden" class = "chosen" name="speciality_ids[]" value="'+ids[i]+'"/></li>');
			}
			$(".select2-search-choice-close").unbind("click");
			$(".select2-search-choice-close").click(function(){
					$("#json-three").append("<option value="+$(this).parent().find("input[type='hidden']").val()+">"+$(this).parent().text()+"</option>");
					$(this).parent().remove();
				});
		}
      });
	  var speciality_list = $('#speciality_list');
	  <?php if(($this->session->userdata('speciality'))!='')
		{
			foreach($this->session->userdata('speciality') as $s)
			{
				
		?>	
						speciality_list.append('<li class="select2-search-choice"><div>'+'<?php echo $s['category_name'];?>'+'</div><span class="select2-search-choice-close" tabindex="-1"></span><input type="hidden" name="speciality_ids[]" value="'+<?php echo $s['id']?>+'"/></li>');
		<?php
			}
		}
		?>
		$(".select2-search-choice-close").unbind("click");
		$(".select2-search-choice-close").click(function(){
		$("#json-three").append("<option value="+$(this).parent().find("input[type='hidden']").val()+">"+$(this).parent().text()+"</option>");
		$(this).parent().remove();
		});
});
</script>




<div class="account-wrapper">

  <div class="account-logo" style="width:100%;">
    <img src="<?php echo base_url()?>assets/img/logo-login.png" alt="Target Admin" style="width:75%;">
  </div>

<?php $tabindex=1;?>
    <div class="account-body register-form">

      <h3 class="account-body-title"><?php echo $words['DTL_0212'];?>.</h3>

      <form class="form" method="post"action="<?php echo site_url('auth/save')?>" enctype="multipart/form-data">

        <?php if(($this->session->userdata('linkedin_id'))==null){?>
        <div class="form-group">
		<!--   <?php if(form_error('name')){?><span class="validation_error_msg"><?php echo form_error('name'); ?></span><?php } ?> -->
          <label for="signup-fullname" class="placeholder"><?php echo $words['DTL_0297'];?></label>
          <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" tabindex="<?php echo $tabindex; $tabindex++;?>" value="<?php echo set_value('name'); ?>">

          <?php if(form_error('name')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('name'); ?></li></ul>
                  <?php } ?>
        </div> <!-- /.form-group -->

 

        <div class="form-group">
		<!--   <?php if(form_error('email_address')){?><span class="validation_error_msg"><?php echo form_error('email_address'); ?></span><?php } ?> -->
          <label for="signup-email" class="placeholder"><?php echo $words['DTL_0105'];?></label>
          <input type="text" class="form-control" id="email_address" name="email_address" placeholder="Your Email" value="<?php echo set_value('email_address'); ?>" tabindex="<?php echo $tabindex; $tabindex++;?>">

            <?php if(form_error('email_address')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('email_address'); ?></li></ul>
                  <?php } ?>
        </div> <!-- /.form-group -->

        <div class="form-group">
          <label for="login-password" class="placeholder"><?php echo $words['DTL_0179'];?></label>
		<!--   <?php if(form_error('password')){?><span class="validation_error_msg"><?php echo form_error('password'); ?></span><?php }?> -->
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?php echo set_value('password'); ?>"  tabindex="<?php echo $tabindex; $tabindex++;?>">

           <?php if(form_error('password')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('password'); ?></li></ul>
                  <?php } ?>
        </div> <!-- /.form-group -->
		<?php }
		else{?>
		<div class="form-group">
			<label for="signup-fullname" class="placeholder"><?php echo $words['DTL_0297'];?></label>
			<p><?php echo $this->session->userdata('first_name')." ".$this->session->userdata('last_name')?></p>
			<input type="hidden" id="name" name="name" tabindex="<?php echo $tabindex; $tabindex++;?>" value="<?php echo $this->session->userdata('first_name')." ".$this->session->userdata('last_name')?>">
		</div>
		
		<div class="form-group">
			<label for="signup-fullname" class="placeholder"><?php echo $words['DTL_0281'];?></label>
			<p><?php echo $this->session->userdata('linkedin_username')?></p>
			<input type="hidden" id="username" name="username" value="<?php echo $this->session->userdata('linkedin_username')?>" tabindex="<?php echo $tabindex; $tabindex++;?>">
		</div>
		
		<div class="form-group">
			<label for="signup-email" class="placeholder"><?php echo $words['DTL_0105'];?></label>
          <p><?php echo $this->session->userdata('linkedin_email_address')?></p>
		  <input type="hidden" id="email_address" name="email_address" value="<?php echo $this->session->userdata('linkedin_email_address')?>" tabindex="<?php echo $tabindex; $tabindex++;?>">
		</div>
		<?php 
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < 10; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		?>
		<input type="hidden" name="password" id="password" value="<?php echo $randomString;?>">
		<?php }?>

        <div class="form-group">
      <!--   <?php if(form_error('contact_person')){?><span class="validation_error_msg"><?php echo form_error('contact_person'); ?></span><?php }?> -->
          <label for="signup-contact-person" class="placeholder"><?php echo $words['DTL_0069'];?></label>
          <input type="text" class="form-control" id="contact_person" name="contact_person" placeholder="Contact Person" tabindex="<?php echo $tabindex; $tabindex++;?>" value="<?php echo set_value('contact_person'); ?>">

           <?php if(form_error('contact_person')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('contact_person'); ?></li></ul>
                  <?php } ?>
        </div> <!-- /.form-group -->

        <div class="form-group">
		<!--  <?php if(form_error('address_longitude')){?><span class="validation_error_msg">
		  	<?php echo form_error('address_longitude');?>
          </span><?php } ?> -->
          <label for="signup-address" class="placeholder"><?php echo $words['DTL_0007'];?></label>
          <input type="text" class="form-control" id="address" name="address" placeholder="Address" tabindex="<?php echo $tabindex; $tabindex++;?>" value="<?php echo set_value('address'); ?>">

			<?php if(form_error('address_longitude')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('address_longitude'); ?></li></ul>
                  <?php } ?>          
        </div> <!-- /.form-group -->

        <div class="form-group">
		<!--  <?php if(form_error('zip_code')){?><span class="validation_error_msg"><?php echo form_error('zip_code'); ?></span><?php } ?> -->
          <label for="signup-zip-code" class="placeholder"><?php echo $words['DTL_0299'];?></label>
          <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="Zip Code" tabindex="<?php echo $tabindex; $tabindex++;?>" value="<?php echo set_value('zip_code'); ?>">

          <?php if(form_error('zip_code')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('zip_code'); ?></li></ul>
                  <?php } ?> 
        </div> <!-- /.form-group -->

        <div class="form-group">
	<!-- 	<?php if(form_error('phone_number')){?><span class="validation_error_msg"><?php echo form_error('phone_number'); ?></span><?php } ?> -->
          <label for="signup-phone-number" class="placeholder"><?php echo $words['DTL_0186'];?></label>
          <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number" tabindex="<?php echo $tabindex; $tabindex++;?>" value="<?php echo set_value('phone_number'); ?>">

           <?php if(form_error('phone_number')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('phone_number'); ?></li></ul>
                  <?php } ?> 
        </div> <!-- /.form-group -->

        <div class="form-group">
	<!-- 	<?php if(form_error('iban_number')){?><span class="validation_error_msg"><?php echo form_error('iban_number'); ?></span><?php } ?> -->
          <label for="signup-iban-number" class="placeholder">IBAN <?php echo $words['DTL_0169'];?></label>
          <input type="text" class="form-control" id="iban_number" name="iban_number" placeholder="IBAN Number" tabindex="<?php echo $tabindex; $tabindex++;?>" value="<?php echo set_value('iban_number'); ?>">

           <?php if(form_error('iban_number')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('iban_number'); ?></li></ul>
                  <?php } ?> 
        </div> <!-- /.form-group -->

        <div class="form-group">
	<!-- 	<?php if(form_error('bic_number')){?><span class="validation_error_msg"><?php echo form_error('bic_number'); ?></span><?php } ?> -->
          <label for="signup-bic-number" class="placeholder">BIC <?php echo $words['DTL_0169'];?></label>
          <input type="text" class="form-control" id="bic_number" name="bic_number" placeholder="BIC Number" tabindex="<?php echo $tabindex; $tabindex++;?>" value="<?php echo set_value('bic_number'); ?>">

            <?php if(form_error('bic_number')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('bic_number'); ?></li></ul>
                  <?php } ?> 
        </div> <!-- /.form-group -->
		
		<?php if(($this->session->userdata('linkedin_id'))==null){?>
		
         <div class="form-group fileupload fileupload-new" data-provides="fileupload"> 
	<!-- 	<?php if(form_error('photo_logo')){?><span class="validation_error_msg"><?php echo form_error('photo_logo'); ?></span><?php } ?> -->
        	<label for="photo_logo" class="placeholder"><?php echo $words['DTL_0238'];?></label>

        	 <div class="input-group">
                  <div class="form-control">
                      <i class="fa fa-file fileupload-exists"></i> <span class="fileupload-preview"></span>
                  </div>
                  <div class="input-group-btn">
                    <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                    <span class="btn btn-default btn-file">
                      <span class="fileupload-new">Select file</span>
                      <span class="fileupload-exists">Change</span>
                      <input type="file" id="photo_logo" name="photo_logo" value="<?php echo set_value('photo_logo'); ?>"  tabindex="<?php echo $tabindex; $tabindex++;?>" />
                    </span>
                  </div>
                </div>

                  <?php if(form_error('photo_logo')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('photo_logo'); ?></li></ul>
                  <?php } ?> 
        </div>

              
		<?php }?>
		<div class="form-group">
			<label for=""><?php echo $words['DTL_0243'];?>:</label>
				<div class="select2-container select2-container-multi form-control" id="s2id_s2_tokenization">
					
					<ul class="select2-choices" id="speciality_list">
						<?php if($this->session->userdata('speciality')!='')
						{
							$specialities_session = $this->session->userdata('speciality');
							foreach($specialities_session as $s)
							{
						?>
						<?php
							}
						}
						?>
					</ul>
					
				</div>

				    <?php if(form_error('speciality_ids')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('speciality_ids'); ?></li></ul>
                  <?php } ?> 
		</div>
		
		
        <div class="form-group select_speciality_field">
		<img src="<?php echo base_url()?>assets/img/loader.gif" style="display: none;" width="25" height="25" id="notification_loader_speciality">
        	<label for="" class="placeholder"><?php echo $words['DTL_0240'];?></label>
			
             <div class="col-sm-4">
                <p><?php echo $words['DTL_0046'];?></p>
                <select multiple="multiple" id="json-one" class="form-control" tabindex="<?php echo $tabindex; $tabindex++;?>">
                	<?php foreach($main_specialities as $speciality){?>
                  	<option value="<?php echo $speciality['id']?>"><?php echo $speciality['category_name']?></option>
                  	<?php } ?>
            	</select>
            </div>
            <div class="col-sm-4">
            	<p><?php echo $words['DTL_0254'];?></p>
            	<select multiple="multiple" id="json-two" class="form-control" tabindex="<?php echo $tabindex; $tabindex++;?>">
            	</select>
            </div>
              
            <div class="col-sm-4">
                <p><?php echo $words['DTL_0249'];?></p>
                <select multiple="multiple" id="json-three" class="form-control" tabindex="<?php echo $tabindex; $tabindex++;?>">                  
                </select>
            </div>
        </div>
        
		<!-- For Dynamic Fields -->
		<?php if(isset($dynamic_fields)){
			foreach($dynamic_fields as $dynamic_field){ 
		?>
		<?php if($dynamic_field['type_id']==1){?>
			<div class="form-group">
		<!-- 	<?php if(form_error('input_text['.$dynamic_field['id'].']')){?><span class="validation_error_msg"><?php echo form_error('input_text['.$dynamic_field['id'].']'); ?></span><?php } ?> -->
			  <label for="signup-<?php echo $dynamic_field['name'];?>" class="placeholder"><?php echo $dynamic_field['name'];?></label>
			  <?php 
				$input_field_value = set_value('input_text['.$dynamic_field['id'].']');
			  ?>
			  <input type="text" class="form-control" name="input_text[<?php echo $dynamic_field['id']?>]" placeholder="<?php echo $dynamic_field['name'];?>"value="<?php echo $input_field_value; ?>" tabindex="<?php echo $tabindex;?>">

        <?php if(form_error('input_text['.$dynamic_field['id'].']')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('input_text['.$dynamic_field['id'].']'); ?></li></ul>
                  <?php } ?>
			</div> <!-- /.form-group -->
		<?php 
		$tabindex++;
		}
		elseif($dynamic_field['type_id']==2){
		?>
    <?php if(!empty($dynamic_field['options'])){?>
			<div class="form-group">
		<!-- 	<?php if(form_error('option['.$dynamic_field['id'].']')){?><span class="validation_error_msg"><?php echo form_error('option['.$dynamic_field['id'].']'); ?></span><?php } ?> -->
              <label><?php echo $dynamic_field['name'];?></label>

			 <div class="form-group">

			  <?php foreach($dynamic_field['options'] as $option){?>
				  <label class="checkbox-inline">
                  <input type="checkbox"  name="options[<?php echo $dynamic_field['id']?>][]" value="<?php echo $option['id']?>" <?php echo set_checkbox('options['.$dynamic_field['id'].'][]',$option['id']) ?> tabindex="<?php echo $tabindex;?>" > <?php echo $option['value'];?>
                </label>
			   <?php }?>
			 
				</div>
			       <?php if(form_error('options['.$dynamic_field['id'].']')){?> <ul class="checkbox-inline-error parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('options['.$dynamic_field['id'].']'); ?></li></ul>
                  <?php } ?> 

			 </div>
       <?php }?>
		<?php
			$tabindex++;
		}
		elseif($dynamic_field['type_id']==3){
		?>
		<?php if(!empty($dynamic_field['options'])){?>
		<div class="form-group">
		<!-- 	<?php if(form_error('select_option['.$dynamic_field['id'].']')){?><span class="validation_error_msg"><?php echo form_error('select_option['.$dynamic_field['id'].']'); ?></span><?php } ?> -->
			<strong><?php echo $dynamic_field['name'];?></strong>

			<select id="" class="form-control" name="select_option[<?php echo $dynamic_field['id']?>]" tabindex="<?php echo $tabindex;?>">
			<option value=""><?php echo $words['DTL_0197'];?></option>
			<?php foreach($dynamic_field['options'] as $option){?>
			<option value="<?php echo $option['id']?>" <?php echo set_select('select_option['.$dynamic_field['id'].']',$option['id'])?>><?php echo $option['value'];?></option>
			<?php 
			$tabindex++;
			}?>
			</select>

      <?php if(form_error('select_option['.$dynamic_field['id'].']')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('select_option['.$dynamic_field['id'].']'); ?></li></ul>
                  <?php } ?>

        </div> <!-- /.col -->
        <?php }?>
		
		<?php
		}
		?>
		<?php }}?>
		<!-- Dynamic Fields End -->
		
		<input type="hidden" id="address_longitude" name="address_longitude" value="<?php echo set_value('address_longitude'); ?>"/>
		<input type="hidden" id="address_latitude" name="address_latitude" value="<?php echo set_value('address_latitude'); ?>" />
        <input type="hidden" id="is_company" name="is_company" value="1" />

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="<?php echo $tabindex; $tabindex++;?>" id="submit_btn">
          <?php echo $words['DTL_0077'];?>&nbsp; <i class="fa fa-play-circle"></i>
          </button>
        </div> <!-- /.form-group -->

      </form>

    </div> <!-- /.account-body -->

    <div class="account-footer">
      <p>
      <?php echo $words['DTL_0035'];?>? &nbsp;
      <a href="<?php echo site_url('auth')?>" class="" tabindex="<?php echo $tabindex; $tabindex++;?>"><?php echo $words['DTL_0144'];?> !</a>
      </p>
    </div> <!-- /.account-footer -->

  </div> <!-- /.account-wrapper -->
