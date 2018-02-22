<button type="button" class="btn btn-secondary" id="add_subscription_btn"><?php echo $words['DTL_0022'];?></button>
<button type="button" class="btn btn-secondary" onclick="location.href='<?php echo site_url('admin/payment/subscription_edit_page')?>'"><?php echo $words['DTL_0097'];?></button>

<br/>
<br/>
<br/>
<div class="col-md-12">

  <form id="search_form" class="form" method="post" style="display:none">

	<div class="form-group">

	  <div class="input-group">
		<input id="search_user_txt" type="search" class="form-control input-lg" name="search" placeholder="Search User">

		<span class="input-group-btn">
			  <button id="search_user_btn" class="btn btn-primary btn-lg" type="button"><i class="fa fa-search"></i>&nbsp; <?php echo $words['DTL_0226'];?></button>
			</span>
	  </div>

	</div>

  </form>

</div>
<div id='no_user' style="display:none">
<?php echo $words['DTL_0165'];?>
</div>
<br/>