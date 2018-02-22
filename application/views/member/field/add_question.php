


<body class="account-bg">

	

	  <div class="content-header">
	  <?php if($question_id==null){?>
          <h2 class="content-header-title"><?php echo $words['DTL_0024'];?></h2>
          <?php }
          else {?>
          <h2 class="content-header-title"><?php echo $words['DTL_0096'];?></h2>
          <?php }?>
              <ol class="breadcrumb">
              <li><a href="<?php echo site_url('admin/home') ?>"><?php echo $words['DTL_0122'];?></a></li>
              <li><a href="<?php echo site_url('admin/field') ?>"><?php echo $words['DTL_0117'];?></a></li>
              <?php if($question_id==null){?>
              <li class="active"><?php echo $words['DTL_0024'];?></li>
              <?php }
          	  else {?>
          	  <li class="active"><?php echo $words['DTL_0096'];?></li>
          	  <?php }?>
              </ol>
          </div>

<div class="row">

        <div class="col-md-12">

<div class="account-wrapper">


    <div class="portlet">


            <div class="portlet-header">
				<?php if($question_id==null){?>
				<h3><i class="fa fa-tasks"></i><?php echo $words['DTL_0024'];?></h3>
				<?php }
				else{?>
				<h3><i class="fa fa-tasks"></i><?php echo $words['DTL_0096'];?></h3>
				<?php }?>

            </div> <!-- /.portlet-header -->


			 <div class="portlet-content">


			      <form id="validate-enhanced" class="form parsley-form" method="POST" action="<?php echo site_url('admin/field/save_question');?>">

			        <div class="form-group">
			          <label for="question" class="placeholder-hidden"><?php echo $words['DTL_0206'];?></label>
			          <input type="text" class="form-control" id="question" placeholder="Please Enter Question"  name="question" value="<?php
			          if(!empty($question['name']))
					  	echo $question['name'];
					  else
					  	echo set_value('question') ?>">
					  <?php if(form_error('question')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('question'); ?></li></ul>
                  <?php } ?>
			        </div> <!-- /.form-group -->

					<div class="form-group">

			            <label for="validateSelect">  <?php echo $words['DTL_0116'];?></label>

			              <select id="field_type" class="form-control parsley-validated" name="field_type">
			              	<option value=""><?php echo $words['DTL_0188']?></option>
							<?php foreach($field_types as $field_type)
							{
								if(!empty($question)) {?>
							<option value="<?php echo $field_type['id'];?>" <?php if(!empty($question))
							{
								if($question['type_id']==$field_type['id'])
									echo 'selected';
							}
							?>><?php echo $field_type['type_name'];?></option>
							<?php }
							else{
								?>
								<option value="<?php echo $field_type['id'];?>" <?php echo set_select('field_type', $field_type['id']); ?> > <?php echo $field_type['type_name'];?></option>
								<?php
							}
							} ?>
			              </select>
		              	  <?php if(form_error('field_type')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('field_type'); ?></li></ul>
                  <?php } ?>
			         </div>


					 <div class="form-group">

			             <label> <?php echo $words['DTL_0234'];?> </label>

			              <select id="section" class="form-control parsley-validated" name="section">
			              <option value=""><?php echo $words['DTL_0189']?></option>
							<?php foreach($sections as $section){ 
								if(!empty($question)){?>
							<option value="<?php echo $section['id'];?>" <?php if(!empty($question))
							{
								if($question['section_id']==$section['id'])
									echo 'selected';
							}
							?>><?php echo $section['section_name'];?></option>
							<?php }
							else{ ?>
								<option value="<?php echo $section['id'];?>" <?php echo set_select('section', $section['id']); ?>> <?php echo $section['section_name'];?></option>
							<?php } }?>
			              </select>
			              <?php if(form_error('section')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('section'); ?></li></ul>
                  <?php } ?>

			         </div>
					 
					<div class="form-group">

			                <label class="checkbox-inline">
			                  <input type="checkbox" id="is_required"  value="1" name="is_required" checked> <?php echo $words['DTL_0218'];?>
			                </label>
					</div>
					
					<input type="hidden" name="question_id" id="question_id" value="<?php echo $question_id;?>"/>

			        <div class="form-group">
			          <button type="submit" class="btn btn-primary" tabindex="5">
					  <?php if($question_id==null){?>
			            <?php echo $words['DTL_0006'];?> 
						<?php }
						else{?>
						<?php echo $words['DTL_0091'];?> 
						<?php }?>
			          </button>
			       

						<a href="<?php echo site_url('admin/field')?>"><button type="button" class="btn btn-default"><?php echo $words['DTL_0045'];?>  </button></a>
					</div>
					
					

			      </form>

			  	</div>


  		</div> <!-- /.account-body -->

</div>
</div>
</div>


