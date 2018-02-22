<div class="row">

   

        <div class="col-md-12">
      
      

      <div class="content-header">
      <h2 class="content-header-title"><?php echo $words['DTL_0286'];?></h2>
      <ol class="breadcrumb">
          <li><a href="<?php echo site_url('admin/home') ?>"><?php echo $words['DTL_0122'];?></a></li>
          <li class="active"><?php echo $words['DTL_0286'];?></li>
        </ol>
      </div>
<form class="form" action="<?php echo site_url('admin/day/save_day')?>" method="post">
	<input type="hidden" name="sunday" value="0">
	<input type="hidden" name="monday" value="0">
	<input type="hidden" name="tuesday" value="0">
	<input type="hidden" name="wednesday" value="0">
	<input type="hidden" name="thursday" value="0">
	<input type="hidden" name="friday" value="0">
	<input type="hidden" name="saturday" value="0">
	<input type="checkbox" name="sunday" value='1' <?php if($sunday == 1) echo "checked"?>>Sunday<br>
	<input type="checkbox" name="monday" value='1' <?php if($monday == 1) echo "checked"?>>Monday<br>
	<input type="checkbox" name="tuesday" value='1' <?php if($tuesday == 1) echo "checked"?>>Tuesday<br>
	<input type="checkbox" name="wednesday" value='1' <?php if($wednesday == 1) echo "checked"?>>Wednesday<br>
	<input type="checkbox" name="thursday" value='1' <?php if($thursday == 1) echo "checked"?>>Thursday<br>
	<input type="checkbox" name="friday" value='1' <?php if($friday == 1) echo "checked"?>>Friday<br>
	<input type="checkbox" name="saturday" value='1' <?php if($saturday == 1) echo "checked"?>>Saturday<br>
	<input type="submit" value="Save">
</form>

 </div> <!-- /.col -->

      </div> <!-- /.row -->



    </div> <!-- /.content-container -->
      
  </div> <!-- /.content -->

</div> <!-- /.container -->
