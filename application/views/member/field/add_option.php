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
              <li class="active"><?php echo $words['DTL_0016'];?></li>
              </ol>
          </div>





	<div class="account-wrapper">


    <div class="portlet">


            <div class="portlet-header">
			<h3><i class="fa fa-tasks"></i><?php echo $words['DTL_0017'];?></h3>
				
            </div> <!-- /.portlet-header -->


			 <div class="portlet-content">


			        <form class="form account-form" method="POST" action="<?php echo site_url('admin/field/save_option');?>" onsubmit="return validate_myform();">

			        <div class="form-group">
			          <label for="option" class="placeholder-hidden"><?php echo $words['DTL_0176'];?></label>
			          <input type="text" class="form-control option" placeholder="Option" name="options[]">
			           <?php if(form_error('options[]')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('options[]'); ?></li></ul>
                  <?php } ?>
			        </div> <!-- /.form-group -->
					
					<div id="insert_options">
					</div>
					
					<div class="form-group pull-right-md" style="z-index: 999;">
						<button class="btn btn-secondary" type="button" id="more_options" ><?php echo $words['DTL_0013'];?>&nbsp; <i class="fa fa-plus"></i></button>
					</div>
					
					<input type="hidden" id="question_id" name="question_id" value="<?php echo $question_id; ?>" />
					
			        <div class="form-group">
			          <button  type="submit" class="btn btn-primary" id="option_save_btn">
			            <?php echo $words['DTL_0225'];?>
			          </button>
						 <a href="<?php echo site_url('admin/field/')?>"><button type="button" class="btn btn-default"><?php echo $words['DTL_0045'];?> <!-- &nbsp;<i class="fa fa-times-circle"></i>  --></button></a>
					</div>
					
			      </form>

			  	</div>


  		</div> <!-- /.account-body -->

</div>


