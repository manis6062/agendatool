<body class="account-bg">


  <div class="content-header">
        <h2 class="content-header-title"><?php echo $words['DTL_0094'];?></h2>
        <ol class="breadcrumb">
              <li><a href="<?php echo site_url('admin/home') ?>"><?php echo $words['DTL_0122'];?></a></li>
              <li><a href="<?php echo site_url('admin/field') ?>"><?php echo $words['DTL_0117'];?></a></li>
           <!--    <li class="active"><?php echo $words['DTL_0016'];?></li>
             -->
              <li class="active"><?php echo $words['DTL_0094'];?></li>
            
              </ol>
      </div>

<div class="account-wrapper">
<div class="portlet">



    <div class="portlet-header">
                <h3><i class="fa fa-tasks"></i><?php echo $words['DTL_0094'];?></h3>
        
    </div>

    <div class="portlet-content">
	<!-- 	<h3><?php echo $words['DTL_0094'];?></h3> -->

      <form class="form account-form" method="POST" action="<?php echo site_url('admin/field/edit_option');?>">

        <div class="form-group">
          <label for="option" class="placeholder-hidden"><?php echo $words['DTL_0173'];?></label>
          <input type="text" class="form-control" placeholder="Option" name="value" value="<?php echo $option['value'];?>">
          <?php if(form_error('value')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('value'); ?></li></ul>
                  <?php } ?>
        </div> <!-- /.form-group -->
		
		<input type="hidden" id="question_id" name="question_id" value="<?php echo $option['question_id']; ?>" />
		<input type="hidden" id="is_deleted" name="is_deleted" value="<?php echo $option['is_deleted'];?>" />
		<input type="hidden" id="id" name="id" value="<?php echo $option['id'];?>" />
		
      <!--   <div class="form-group">
          <button type="submit" class="btn btn-success btn-block btn-lg" >
            <?php echo $words['DTL_0094'];?> &nbsp; <i class="fa fa-plus"></i>
          </button>
        </div> --> <!-- /.form-group -->

        <div class="form-group">
          <button type="submit" class="btn btn-primary" >
            <?php echo $words['DTL_0094'];?> <!-- &nbsp; <i class="fa fa-plus"></i> -->
          </button>
          <a href="<?php echo site_url('admin/field/option')?>/<?php echo $option['question_id'];?>"><button type="button" class="btn btn-default"><?php echo $words['DTL_0045'];?> <!-- &nbsp;<i class="fa fa-times-circle"></i>  --></button></a>
        </div>
		
	<!-- 	<div class="form-group">
			<a href="<?php echo site_url('admin/field/option')?>/<?php echo $option['id'];?>"><button type="button" class="btn btn-primary btn-block btn-lg"><?php echo $words['DTL_0045'];?> &nbsp;<i class="fa fa-times-circle"></i> </button></a>
		</div> -->

      </form>


    </div> <!-- /.account-body -->


  </div>
  </div>
	
	