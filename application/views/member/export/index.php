
<div class="content-header">
  <h2 class="content-header-title"><?php echo $words['DTL_0111'];?></h2>
      <ol class="breadcrumb">
      <li><a href="<?php echo site_url('admin/home') ?>"><?php echo $words['DTL_0122'];?></a></li>
      <li class="active"><?php echo $words['DTL_0111'];?></li>
      </ol>
  </div>

<button type="button" class="btn btn-secondary" id="export_btn" onClick="location.href='<?php echo site_url('admin/export/create_csv_trainer');?>'"><?php echo $words['DTL_0113'];?></button>
<button type="button" class="btn btn-secondary" id="export_btn" onClick="location.href='<?php echo site_url('admin/export/create_csv_company');?>'"><?php echo $words['DTL_0112'];?></button>