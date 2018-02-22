<div class="account-wrapper">

    <div class="account-logo" style="width:100%;">
        <img src="<?php echo base_url()?>/assets/img/logo-login.png" alt="Target Admin" style="width:75%;">
    </div>

    <div class="account-body">


        <h2><?php echo $words['DTL_0357'];?></h2>


    </div> <!-- /.account-body -->


    <div class="form-group">
        <a type="button" href="<?php echo base_url('auth') ; ?>" class="btn btn-primary btn-block btn-lg" tabindex="4"><?php echo $words['DTL_0247'];?>&nbsp; <i class="fa fa-play-circle"></i></a>
    </div> <!-- /.form-group -->

    <div class="account-footer">
        <p>
            <?php echo $words["DTL_0089"];?>? &nbsp;
            <a href="<?php echo site_url('auth/register_trainer')?>" class=""><?php echo $words['DTL_0076'];?>!</a><br/>
            <a href="<?php echo site_url('auth/register_company')?>" class=""><?php echo $words['DTL_0075'];?>!</a>
        </p>
    </div> <!-- /.account-footer -->

</div> <!-- /.account-wrapper -->