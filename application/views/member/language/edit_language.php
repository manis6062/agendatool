<script>
$(document).ready(function(){
	$('#cancel').on('click',function(){
		location.href = '<?php echo site_url('admin/language')?>';
	});
});
function validate_edit_language(){
		var result;
		var old_ext = $('#old_photo').attr('src').split('.').pop();
		var ext = $('#photo_logo').val().split('.').pop();
		if($('#lang_name').val()=="" || $('#short_name').val()=="")
		{
			if($('#lang_name').val()=="")
			{
				alert('Language Name '+"<?php echo $words['DTL_0301'];?>");
				result =  false;
			}
			else if($('#short_name').val()=="")
			{
				alert('Short Name '+"<?php echo $words['DTL_0301'];?>");
				result =  false;
			}
		}
		else if((old_ext != 'jpg' && old_ext != 'jpeg' && old_ext != 'png' && old_ext != 'gif') && (ext != 'jpg' && ext != 'jpeg' && ext != 'png' && ext != 'gif'))
		{
			alert("<?php echo $words['DTL_0199'];?>");
			result = false;
		}
		else
			result = true;
		return result;
	}
</script>
<div class="content-header">
        <h2 class="content-header-title"><?php echo $words['DTL_0356'];?></h2>
        <ol class="breadcrumb">
          <li><a href="<?php echo site_url('admin/home') ?>"><?php echo $words['DTL_0122'];?></a></li>
          <li><a href="<?php echo site_url('admin/language') ?>"><?php echo $words['DTL_0137'];?></a></li>
          <li class="active"><?php echo $words['DTL_0091'];?></li>
        </ol>
      </div>
				<form action="<?php echo site_url('admin/language/edit_language')?>" class="form-horizontal" method="post" enctype="multipart/form-data" onsubmit="return validate_edit_language();">
					<div class="form-group">
					  <label class="col-md-3"><?php echo $words['DTL_0135'];?></label>
					  <div class="col-md-7">
						<input type="text" id="lang_name" name="lang_name" class="form-control" value="<?php echo @$edit_language['lang_name']?>"/>
					  </div> <!-- /.col -->
					</div> <!-- /.form-group -->
					
					<div class="form-group">
					  <label class="col-md-3"><?php echo $words['DTL_0136'];?></label>
					  <div class="col-md-7">
						<input type="text" id="short_name" name="short_name" class="form-control" value="<?php echo @$edit_language['short_name']?>"/>
					  </div> <!-- /.col -->
					</div> <!-- /.form-group -->
					
					<div class='form-group'>
					<label class="col-md-3"><?php echo $words['DTL_0338'];?></label>
					<div class="col-md-7">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                      <div class="fileupload-new thumbnail" style="width: 180px; height: 180px;"><img id="old_photo" src="<?php echo base_url()?>uploads/flags/<?php echo $edit_language['flag'];?>" alt="Profile Avatar" /></div>
                      <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
                      <div>
                        <span class="btn btn-default btn-file"><span class="fileupload-new"><?php echo $words['DTL_0238']?></span><span class="fileupload-exists"><?php echo $words['DTL_0047']?></span><input type="file" id="photo_logo" name="photo_logo" value="<?php echo $edit_language['flag'];?>"></span>
                        <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload"><?php echo $words['DTL_0339']?></a>
                      </div>
                    </div>
                  </div>
				  </div>
					
					<br />

					<div class="form-group">
						<input type="hidden" name="id" value="<?php echo @$edit_language['id']?>">
					  <div class="col-md-7 col-md-push-3">
						<button type="submit" class="btn btn-primary"><?php echo $words['DTL_0223'];?></button>
						&nbsp;
						<button  type="reset" class="btn btn-default" id="cancel"><?php echo $words['DTL_0045'];?></button>
					  </div> <!-- /.col -->

					</div> <!-- /.form-group -->
				</form>