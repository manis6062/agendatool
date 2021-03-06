<div class="account-wrapper">

  <div class="account-logo" style="width:100%;">
    <img src="<?php echo base_url()?>/assets/img/logo-login.png" alt="Target Admin" style="width:75%;">
  </div>

    <?php
    if(!empty($expire_flashdata)){
        echo $expire_flashdata;
    }

    ?>

    <div class="account-body">

      <h3><?php echo $words['DTL_0292'];?>.</h3>

      <h5><?php echo $words['DTL_0204'];?>.</h5>

      <br>


          <div class="form-group linkedin-btn">
      <a class="btn btn-primary" href="<?php echo site_url('linkedin/auth')?>"><input type="button" value="<?php echo $words['DTL_0145'];?> Linkedin"></a> 
    </div>


      <span class="account-or-social text-muted"><?php echo $words['DTL_0177'];?></span>


      <form class="form" method="post" action="<?php echo site_url('auth/login')?>">

        <div class="form-group">
      <!--    <?php if($this->session->flashdata('error')){?>
          <span class="validation_error_msg"><p>
		        <?php echo $this->session->flashdata('error'); ?>
          </p></span><?php }?> -->
		 <!--  <?php if(form_error('email_address')){?><span class="validation_error_msg"><?php echo form_error('email_address'); ?></span><?php } ?> -->
          <label for="login-username" class="placeholder"><?php echo $words['DTL_0285'];?></label>
          <input type="text" class="form-control" id="login-username" placeholder="<?php echo $words['DTL_0285'];?>" tabindex="1" name="email_address">

          <?php if(form_error('email_address')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('email_address'); ?></li></ul>
                  <?php } ?>
    <!--               
          <?php if($this->session->flashdata('error')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo $this->session->flashdata('error') ?></li></ul>
                  <?php } ?> -->


        </div> <!-- /.form-group -->

        <div class="form-group">
		<!--  <?php if(form_error('password')){?><span class="validation_error_msg"><?php echo form_error('password'); ?></span><?php } ?> -->
          <label for="login-password" class="placeholder"><?php echo $words['DTL_0179'];?></label>
          <input type="password" class="form-control" id="login-password" placeholder="Password" tabindex="2" name="password">

          <?php if(form_error('password')){?> <ul class="parsley-error-list validation_error_msg" style="display: block;"><li class="required" style="display: list-item;"><?php echo form_error('password'); ?></li></ul>
                  <?php } ?>

        </div> <!-- /.form-group -->


		
        <div class="form-group clearfix">
          <div class="pull-right">
            <a href="<?php echo site_url('auth/forgot_password_page')?>"><?php echo $words['DTL_0118'];?>?</a>
          </div>
        </div> <!-- /.form-group -->
		

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block btn-lg" tabindex="4"><?php echo $words['DTL_0247'];?>&nbsp; <i class="fa fa-play-circle"></i></button>
        </div> <!-- /.form-group -->
		
		
      </form>
    </div> <!-- /.account-body -->

    <div class="account-footer">
      <p>
      <?php echo $words["DTL_0089"];?>? &nbsp;
      <a href="<?php echo site_url('auth/register_trainer')?>" class=""><?php echo $words['DTL_0076'];?>!</a><br/>
      <a href="<?php echo site_url('auth/register_company')?>" class=""><?php echo $words['DTL_0075'];?>!</a>
      </p>
    </div> <!-- /.account-footer -->

  </div> <!-- /.account-wrapper -->