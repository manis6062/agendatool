<body class="account-bg">

<div class="account-wrapper">


    <div class="account-body">
		<h3><?php echo $words['DTL_0048'];?></h3>

      <form class="form account-form" method="POST" action="<?php echo site_url('admin/home/change_admin_email');?>">

        <div class="form-group">
		<?php if(form_error('email_address')){?><span class="validation_error_msg"><?php echo form_error('email_address'); ?></span><?php } ?>
          <label for="option" class="placeholder-hidden"><?php echo $words['DTL_0105'];?></label>
          <input type="text" class="form-control option" placeholder="Email Address" name="email_address">
        </div> <!-- /.form-group -->
		
		
        <div class="form-group">
          <button  type="submit" class="btn btn-success btn-block btn-lg" id="save_email_address_admin">
            <?php echo $words['DTL_0223'];?> &nbsp; <i class="fa fa-plus"></i>
          </button>
        </div> <!-- /.form-group -->
		
		<div class="form-group">
			<a href="<?php echo site_url('admin/home')?>" class="btn btn-primary btn-block btn-lg"><?php echo $words['DTL_0045'];?> &nbsp;<i class="fa fa-times-circle"></i> </a>
		</div>
		
      </form>

		
    </div> <!-- /.account-body -->
	
	
	