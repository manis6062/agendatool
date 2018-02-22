<div class="col-sm-12">


         <div class="content-header">
          <h2 class="content-header-title"><?php if(!empty($speciality)){ echo $words['DTL_0090']; } else{ echo $words['DTL_0019'];}?></h2>
              <ol class="breadcrumb">
              <li><a href="<?php echo site_url('admin/home') ?>"><?php echo $words['DTL_0122'];?></a></li>
              <li><a href="<?php echo site_url('admin/speciality') ?>"><?php echo $words['DTL_0248'];?></a></li>
              <li class="active"><?php if(!empty($speciality)){ echo $words['DTL_0091']; } else{ echo $words['DTL_0019'];}?></h2></li>
              </ol>
          </div>

          <div class="account-wrapper">

          <div class="portlet">

            <div class="portlet-header">

              <h3>
                <i class="fa fa-tasks"></i>
                <?php if(!empty($speciality)){ echo $words['DTL_0090']; } else{ echo $words['DTL_0019'];}?></h2>
              </h3>

            </div> <!-- /.portlet-header -->

            <div class="portlet-content">

              <form id="validate-basic" action="<?php echo site_url('admin/speciality/save_speciality')?>" class="form parsley-form" method="post" onsubmit="return validate_speciality_form();">

                <div class="form-group">  
                  <label for="validateSelect"><?php echo $words['DTL_0241'];?></label>
                  <select id="parent_id" name="parent_id" class="form-control">
                    <option value=""><?php echo $words['DTL_0202'];?></option>
                    <?php foreach($sub_categories as $sub_category){?>
						<option value="<?php echo $sub_category['id']?>"  <?php if(!empty($speciality)){
							if($speciality['parent_id'] == $sub_category['id']){
								echo 'selected';
							}
						}?>
            <?php echo  set_select('parent_id', $sub_category['category_name']); ?>><?php echo $sub_category['category_name'];?></option>
					<?php }?>
                  </select>
                  <?php if(form_error('parent_id')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('parent_id'); ?></li></ul>
                  <?php } ?>
                </div>


                <div class="form-group">
                  <label for="name"><?php echo $words['DTL_0250'];?></label>
                  <input type="text" id="category_name" name="category_name" class="form-control" value="<?php if(!empty($speciality)){
            echo $speciality['category_name'];
          }
          elseif(set_value('category_name'))
            echo set_value('category_name');
          ?>">
          <?php if(form_error('category_name')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('category_name'); ?></li></ul>
                  <?php } ?>
                </div>
                
                <input type="hidden" name="id" value="<?php if(!empty($speciality)){ echo $speciality['id']; }?>">
                <div class="form-group">
                  <button type="submit" class="btn btn-primary"><?php if(!empty($speciality)){ echo $words['DTL_0223']; } else{echo $words['DTL_0006'];}?></button>
                </div>

              </form>

            </div> <!-- /.portlet-content -->

          </div> <!-- /.portlet -->
        </div>
          
        </div> <!-- /.col -->