<div class="account-wrapper">

  <div class="account-logo">
    <img src="<?php echo base_url()?>assets/img/logo-login.png" alt="Target Admin">
  </div>

    <div class="account-body">

      <h3 class="account-body-title"><?php echo $words['DTL_0181'];?></h3>

      <h5 class="account-body-subtitle"><?php echo $words['DTL_0290'];?>.</h5>

        <form class="form account-form" method="POST" action="<?php echo site_url('auth/forgot_password'); ?>">

            <div class="form-group">
                <label for="forgot-email" class="placeholder-hidden"><?php echo $words['DTL_0296']; ?></label>
                <input type="text" class="form-control" id="forgot-email" placeholder="Your Email" name="email_address"
                       tabindex="1">
            </div>
            <!-- /.form-group -->

            <div class="form-group">


                <button type="submit" class="btn btn-secondary btn-block btn-lg" tabindex="2" id="forgot_password_btn">
                    <?php echo $words['DTL_0221']; ?>&nbsp; <i id="icon_forgot_password" class="fa fa-refresh"></i>
                </button>


            </div>
            <!-- /.form-group -->

            <div class="form-group">
                <a href="



          <?php echo site_url('auth'); ?>"><i class="fa fa-angle-double-left"></i>
                    &nbsp;<?php echo $words['DTL_0044']; ?></a>
            </div>
            <!-- /.form-group -->
        </form>

<script>
$(document).ready(function(){
	$("#forgot_password_btn").click(function(){
		$(this).parent().find("#icon_forgot_password").addClass("spin-icon");
	});
});
</script>

  </div>
</div>