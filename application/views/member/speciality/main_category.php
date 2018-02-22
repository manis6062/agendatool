<div class="col-sm-12">

        <div class="content-header">
          <h2 class="content-header-title"><?php if(!empty($main_category)){ echo $words['DTL_0090']; } else {echo $words['DTL_0012'];}?></h2>
              <ol class="breadcrumb">
              <li><a href="<?php echo site_url('admin/home') ?>"><?php echo $words['DTL_0122'];?></a></li>
              <li><a href="<?php echo site_url('admin/speciality') ?>"><?php echo $words['DTL_0248'];?></a></li>
              <li class="active"><?php if(!empty($main_category)){ echo $words['DTL_0091']; } else {echo $words['DTL_0012'];}?></li>
              </ol>
          </div>

          <div class="account-wrapper">

          <div class="portlet">

            <div class="portlet-header">

              <h3>
                <i class="fa fa-tasks"></i>
                <?php if(!empty($main_category)){ echo $words['DTL_0090']; } else {echo $words['DTL_0012'];}?>
              </h3>

            </div> <!-- /.portlet-header -->

            <div class="portlet-content">

              <form id="validate-basic" action="<?php echo site_url('admin/speciality/save_main_category')?>" class="form parsley-form" method="post" onsubmit="return validate_main_category_form();">

                <div class="form-group">
                  <label for="name"><?php echo $words['DTL_0148'];?></label>
                  <input type="text" id="category_name" name="category_name" class="form-control" value="<?php if(!empty($main_category)){
					  echo $main_category['category_name'];
				  }
          elseif(set_value('category_name'))
            echo set_value('category_name');
          ?>" >
          <?php if(form_error('category_name')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('category_name'); ?></li></ul>
                  <?php } ?>
                </div>
                
				<input type="hidden" id="parent_id" name="parent_id" value="0">
				<input type="hidden" name="id" value="<?php if(!empty($main_category)){ echo $main_category['id']; }?>">
                <div class="form-group">
                  <button type="submit" class="btn btn-primary"><?php if(!empty($main_category)){ echo $words['DTL_0223']; } else{echo $words['DTL_0006'];}?></button>
                </div>

              </form>

            </div> <!-- /.portlet-content -->

          </div> <!-- /.portlet -->

        </div>
          
        </div> <!-- /.col -->