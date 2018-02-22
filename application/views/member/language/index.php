<script>
$(document).ready(function(){
	
	$('.edit_translation').on('click',function(){
		var keyword_id = $(this).attr('result');
		$('#keywords_id').val(keyword_id);
		var keyword_name = $(this).parent().parent().find('.keyword_column').text();
		$.ajax({
			url: '<?php echo site_url('admin/language/get_data_from_keyword_id')?>',
			type: "POST",
			dataType:"JSON",
			data:{"keywords_id":keyword_id},
			success: function(result){
				console.log(result);
				if(result.length>0){
					for(i=0;i<result.length;i++){
						var prop = result[i];
						$(".language_"+prop.language_type_id).val(prop.text);
					}
				}
				else{
					$('.text_array').val('');
				}
				$('#modal_title').html('Translation For '+keyword_name);
				$('#translation_modal').modal('show');
			},
		});
		
	});
});

function get_pagination(limit,offset) {
    $.ajax({
        url: '<?php echo site_url("admin/language/get_translation_pagination")?>',
        type: "POST",
        data: {
            "offset": offset,
            "limit": limit,
            "searchByEnglishTranslation" : '<?php echo $searchByEnglishTranslation;?>'
        },
        dataType: "html",
        success: function(result) {
            $("#translation_table").html(result);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // alert('An error has occured');
        }
    });
}

function validate_add_language(){
		var result;
		var ext = $('#photo_logo').val().split('.').pop();
		if($('#lang_name').val()=="" || $('#short_name').val()=="")
		{
			if($('#lang_name').val()=="")
			{
				alert('Language Name '+"<?php echo $words['DTL_0301'];?>");
				result =  false;
			}
			if($('#short_name').val()=="")
			{
				alert('Short Name '+"<?php echo $words['DTL_0301'];?>");
				result =  false;
			}
		}
		else if(ext != 'jpg' && ext != 'jpeg' && ext != 'png' && ext != 'gif')
		{
			alert("<?php echo $words['DTL_0199'];?>");
			result = false;
		}
		else
			result = true;
		return result;
	}
function validate_add_keyword(){
	var result;
	if($('#keyword').val()=="" || $('#text_name').val()=="")
	{
		if($('#keyword').val()=="")
		{
			alert('Keyword field '+"<?php echo $words['DTL_0301'];?>");
			result =  false;
		}
		if($('#text_name').val()=="")
		{
			alert('English Translation '+"<?php echo $words['DTL_0301'];?>");
			result =  false;
		}
	}
	else
		result = true;
	return result;
}
$(document).ready(function(){
      $("#language_list_tab").on('click',function(){
        $.ajax({
           type: "POST",
           url: "<?php echo site_url('admin/home/set_session')?>"+'/language_list'
         });
      });
      $("#add_language_tab").on('click',function(){
        $.ajax({
           type: "POST",
           url: "<?php echo site_url('admin/home/set_session')?>"+'/add_language'
         });
      });
      $("#add_translation_tab").on('click',function(){
        $.ajax({
           type: "POST",
           url: "<?php echo site_url('admin/home/set_session')?>"+'/add_translation'
         });
      });
      $("#add_keyword_tab").on('click',function(){
        $.ajax({
           type: "POST",
           url: "<?php echo site_url('admin/home/set_session')?>"+'/add_keyword'
         });
      });
    });
</script>
<div class="content-header">
<h2 class="content-header-title"> <?php echo $words['DTL_0137'];?></h2>
<ol class="breadcrumb">
  <li><a href="<?php echo site_url('admin/home') ?>"><?php echo $words['DTL_0122'];?></a></li>
  <li class="active"><?php echo $words['DTL_0137'];?></li>
</ol>
</div>
<div class="row">
	<div class="col-md-3 col-xs-12">       
          <ul id="myTab1" class="nav nav-pills nav-stacked" >
		  <li class="<?php if($this->session->userdata('tab_index')=='' || $this->session->userdata('tab_index')=='language_list') echo 'active';?>" id="language_list_tab">
              <a href="#language_list" data-toggle="tab"><?php echo $words['DTL_0134'];?></a>
            </li>
            <li class="<?php if($this->session->userdata('tab_index')=='add_language') echo 'active';?>" id="add_language_tab">
              <a href="#add_language" data-toggle="tab"><?php echo $words['DTL_0011'];?></a>
            </li>
			<li class="<?php if($this->session->userdata('tab_index')=='add_translation') echo 'active';?>" id="add_translation_tab">
              <a href="#add_translation" data-toggle="tab"><?php echo $words['DTL_0271'];?></a>
            </li>
			<li class="<?php if($this->session->userdata('tab_index')=='add_keyword') echo 'active';?>" id="add_keyword_tab">
              <a href="#add_keyword" data-toggle="tab"><?php echo $words['DTL_0010'];?></a>
            </li>
          </ul>
	</div>
	<div class="col-md-9 col-xs-12">
		<div id="myTab1Content" class="tab-content">
			<div class="tab-pane fade <?php if($this->session->userdata('tab_index')=='' || $this->session->userdata('tab_index')=='language_list') echo 'active in';?>" id="language_list">
				<div class="heading-wrapper">
					<h3 class="heading"><?php echo $words['DTL_0138'];?></h3>
				</div>
				<?php  if(!empty($languages)){?>
				<table class="table table-bordered table-highlight">
					<thead>
					  <tr>
						<th>#</th>
						<th><?php echo $words['DTL_0135'];?></th>
						<th><?php echo $words['DTL_0136'];?></th>
						<th><?php echo $words['DTL_0002'];?></th>
					  </tr>
					</thead>
					<tbody>
					<?php
						$count = 1;
							foreach($languages as $language)
							{
					?>
					  <tr>
						<td><?php echo $count?></td>
						<td><?php echo $language['lang_name']?></td>
						<td><?php echo $language['short_name']?></td>
						<td class="double_icons">
						<?php if($language['id'] == 1){?>
						<label><?php echo $words['DTL_0084'];?></label>
						<?php }
						else{?>
						<a href="<?php echo site_url('admin/language/edit_language_page').'/'.$language['id']?>"><button class="btn btn-xs btn-secondary edit_langugage" result="<?php echo $language['id']?>"><i class="fa fa-pencil"></i><!-- &nbsp;<?php echo $words['DTL_0091'];?> --></button></a>
						<a href="<?php echo site_url('admin/language/delete_language').'/'.$language['id']?>"><button class="btn btn-xs btn-primary" onclick="return confirm('Are you sure you want to delete language');"><i class="fa fa-trash-o"></i><!-- &nbsp;<?php echo $words['DTL_0085'];?> --></button></a>
						<?php }?>
						</td>
					  </tr>		
					<?php 	
							$count++;
							}
						}?>						
					</tbody>
				  </table>          
			</div>
			<div class="tab-pane fade <?php if($this->session->userdata('tab_index')=='add_language') echo 'active in';?>" id="add_language">
				<div class="heading-wrapper">
					<h3 class="heading"><?php echo $words['DTL_0015'];?></h3>
				</div>
				<form action="<?php echo site_url('admin/language/add_language')?>" class="form-horizontal" method="post" enctype="multipart/form-data" onsubmit="return validate_add_language();">
					<div class="form-group">
					  <label class="col-md-3"><?php echo $words['DTL_0135'];?></label>
					  <div class="col-md-7">
						<input type="text" id="lang_name" name="lang_name" class="form-control"/>
					  </div> <!-- /.col -->
					</div> <!-- /.form-group -->
					
					<div class="form-group">
					  <label class="col-md-3"><?php echo $words['DTL_0136'];?></label>
					  <div class="col-md-7">
						<input type="text" id="short_name" name="short_name" class="form-control"/>
					  </div> <!-- /.col -->
					</div> <!-- /.form-group -->
					
					<div class='form-group'>
					<label class="col-md-3"><?php echo $words['DTL_0338'];?></label>
					<div class="col-md-7">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                      <div class="fileupload-new thumbnail" style="width: 180px; height: 180px;"><?php echo $words['DTL_0238']?></div>
                      <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
                      <div>
                        <span class="btn btn-default btn-file"><span class="fileupload-new"><?php echo $words['DTL_0238']?></span><span class="fileupload-exists"><?php echo $words['DTL_0047']?></span><input type="file" id="photo_logo" name="photo_logo"></span>
                        <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload"><?php echo $words['DTL_0339']?></a>
                      </div>
                    </div>
                  </div>
				  </div>
					
					<br />

					<div class="form-group">

					  <div class="col-md-7 col-md-push-3">
						<button type="submit" class="btn btn-primary"><?php echo $words['DTL_0223'];?></button>
						&nbsp;
						<button type="reset" class="btn btn-default"><?php echo $words['DTL_0045'];?></button>
					  </div> <!-- /.col -->

					</div> <!-- /.form-group -->
				</form>
				
			</div>
			<div class="tab-pane fade <?php if($this->session->userdata('tab_index')=='add_keyword') echo 'active in';?>" id="add_keyword">
				<div class="heading-wrapper">
					<h3 class="heading"><?php echo $words['DTL_0014'];?></h3>
				</div>
				<form action="<?php echo site_url('admin/language/add_keyword')?>" class="form-horizontal" method="post" onsubmit="return validate_add_keyword();">
					<div class="form-group">
					  <label class="col-md-3"><?php echo $words['DTL_0132'];?></label>
					  <div class="col-md-7">
						<input type="text" id="keyword" name="keyword" class="form-control"/>
					  </div> <!-- /.col -->
					</div> <!-- /.form-group -->

					<div class="form-group">
					  <label class="col-md-3"><?php echo $words['DTL_0340'];?></label>
					  <div class="col-md-7">
						<input type="text" name="text" id="text_name" class="form-control"/>
					  </div> <!-- /.col -->
					</div> <!-- /.form-group -->
					
					<br />

					<div class="form-group">

					  <div class="col-md-7 col-md-push-3">
						<button type="submit" class="btn btn-primary"><?php echo $words['DTL_0223'];?></button>
						&nbsp;
						<button type="reset" class="btn btn-default"><?php echo $words['DTL_0045'];?></button>
					  </div> <!-- /.col -->

					</div> <!-- /.form-group -->
				</form>
			
			</div>
			<div class="tab-pane fade <?php if($this->session->userdata('tab_index')=='add_translation') echo 'active in';?>" id="add_translation">
				<div class="heading-wrapper">
					<h3 class="heading"><?php echo $words['DTL_0133'];?></h3>
				</div>
				 <form class="form-group user-search filter_block" method="post" action="<?php echo site_url('admin/language')?>" >
        <div class="row">

              <div class="col-md-4">

                <div class="form-group">
                   <label><?php echo $words['DTL_0340'];?>: </label> <input type="text" id="" name="searchByEnglishTranslation" value="<?php //if($this->session->userdata('searchByName')!=''){ echo $this->session->userdata('searchByName');}?>" class="form-control">
                </div>

              </div>

                 
                    
                    
                     <div class="col-md-4">
      					 <div class="form-group">
     
                      <label><?php echo $words['DTL_0140'];?></label>

                    <select id=""  name="limit" class="form-control">
                      <option value="10" <?php if($this->session->userdata('limit') == 10){ ?>selected = "selected"<?php } ?>>10</option>
                      <option value="20" <?php if($this->session->userdata('limit') == 20){ ?>selected = "selected"<?php } ?>>20</option>
                      <option value="50" <?php if($this->session->userdata('limit') == 50){ ?>selected = "selected"<?php } ?>>50</option>
                      <option value="100" <?php if($this->session->userdata('limit') == 100){ ?>selected = "selected"<?php } ?>>100</option>
                      <option value="500" <?php if($this->session->userdata('limit') == 500){ ?>selected = "selected"<?php } ?>>500</option>
                    </select>

                  	</div>
                  </div>
            </div>
               
   
               <input type="submit" value="<?php echo $words['DTL_0259']?>" name="submit" id="submit_btn" class="btn btn-primary">
     
      </form>

				<?php  if(!empty($keywords)){?>
			<div class="table-responsive pagination_inside">
				<table class="table table-bordered table-highlight">
					<thead>
					  <tr>
						<th>#</th>
						<th><?php echo $words['DTL_0133'];?></th>
						<th><?php echo $words['DTL_0340'];?></th>
						<th><?php echo $words['DTL_0002'];?></th>
					  </tr>
					</thead>
					<tbody  id="translation_table">
					<?php
						$count = 1;
							foreach($keywords as $keyword)
							{
					?>
					  <tr>
						<td><?php echo $count?></td>
						<td class='keyword_column'><?php echo $keyword['keyword']?></td>
						<td><?php echo $keyword['text']?></td>
						<td>
						<button class="btn btn-xs btn-secondary edit_translation" result="<?php echo $keyword['keywords_id']?>"><i class="fa fa-pencil"></i>&nbsp;<?php echo $words['DTL_0271'];?></button>
						</td>
					  </tr>		
					<?php 	
							$count++;
							}
							
						}
						else{
							?>
							<div class="alert alert-danger">
						        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
						       <?php echo $words['DTL_0162'];?>
						      </div>
						
						<?php }?>	
						<tr><td colspan="4"><?php echo create_ajax_paging('get_pagination',$total_translation,$offset,array($limit),$limit);?></td></tr>					
					</tbody>
				  </table> 
				</div>
				           
			</div>
		</div>
	</div>
</div>
</div>
</div>
<form method="post" action="<?php echo site_url('admin/language/add_translation')?>" accept-charset="UTF-8">
<div id="translation_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
		  	<div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal">&times;</button>
			    <h4 class="modal-title" id="modal_title"></h4>
		  	</div>
		  	<div class="modal-body">
			<div class="row">
				<div class="col-sm-4">
					<label><?php echo $words['DTL_0137'];?></label>
				</div>
				<div class="col-sm-4">
					<label><?php echo $words['DTL_0271'];?></label>
				</div>
			</div>
			
				<?php if(!empty($languages)){
					foreach($languages as $language){?>
					<div class="row">
						<div class="col-sm-4">
							<?php echo $language['lang_name']?>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<input type="text" class="form-control text_array language_<?php echo $language['id']?>"  name="text_array[<?php echo $language['id']?>]" value="">
							  </div>
						</div>
					</div>
				<?php }}?>
				<input type="hidden" name="keywords_id" id="keywords_id" value="">
			    <div class="row">
			    	<div class="col-sm-12">
			    		<button id="translation_save_btn" class="btn btn-primary"><?php echo $words['DTL_0223'];?></button>
			    	</div>
			    </div>
				
			</div> 
		</div>
	</div>
</div>
</form>
