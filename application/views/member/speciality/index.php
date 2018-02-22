	    <script>

		$(document).ready(function(){
      $("#category_btn").on('click',function(){
        $.ajax({
           type: "POST",
           url: "<?php echo site_url('admin/home/set_session')?>"+'/category'
         });
      });
      $("#sub_category_btn").on('click',function(){
        $.ajax({
           type: "POST",
           url: "<?php echo site_url('admin/home/set_session')?>"+'/sub_category'
         });
      });
      $("#speciality_btn").on('click',function(){
        $.ajax({
           type: "POST",
           url: "<?php echo site_url('admin/home/set_session')?>"+'/speciality'
         });
      });
    });

    function get_main_pagination(main_limit,main_offset) {
    var searchByMainCategory = '<?php echo @$searchByMainCategory;?>';
    $.ajax({
        url: '<?php echo site_url("admin/speciality/get_main_pagination")?>',
        type: "POST",
        data: {
            "main_offset": main_offset,
            "main_limit": main_limit,
            "searchByMainCategory" : searchByMainCategory
        },
        dataType: "html",
        success: function(result) {
            $("#main_category_table").html(result);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // alert('An error has occured');
        }
    });
}
    function get_sub_pagination(sub_limit,sub_offset) {
    $.ajax({
        url: '<?php echo site_url("admin/speciality/get_sub_pagination")?>',
        type: "POST",
        data: {
            "sub_offset": sub_offset,
            "sub_limit": sub_limit,
            "searchBySubCategory" : '<?php echo @$searchBySubCategory;?>'
        },
        dataType: "html",
        success: function(result) {
            $("#sub_category_table").html(result);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // alert('An error has occured');
        }
    });
}
      function get_speciality_pagination(speciality_limit,speciality_offset) {
          $.ajax({
              url: '<?php echo site_url("admin/speciality/get_speciality_pagination")?>',
              type: "POST",
              data: {
                  "speciality_offset": speciality_offset,
                  "speciality_limit": speciality_limit,
                  "searchBySpeciality" : '<?php echo @$searchBySpeciality;?>'
              },
              dataType: "html",
              success: function(result) {
                  $("#speciality_table").html(result);
              },
              error: function(jqXHR, textStatus, errorThrown) {
                  // alert('An error has occured');
              }
          });
      }
			
		</script>
		<div class="content-header">
          <h2 class="content-header-title"><?php echo $words['DTL_0248'];?></h2>
          <ol class="breadcrumb">
          <li><a href="<?php echo site_url('admin/home') ?>"><?php echo $words['DTL_0122'];?></a></li>
          <li class="active"><?php echo $words['DTL_0248'];?></li>
        </ol>
          </div>




              <div class="row">

                <div class="col-md-3 col-xs-12">
              
                  <ul id="myTab" class="nav nav-pills nav-stacked">
                      <li class="<?php if($this->session->userdata('tab_index')=='' || $this->session->userdata('tab_index')=='category') echo 'active';?>"><a href="#category" data-toggle="tab" id="category_btn"> &nbsp;&nbsp; <?php echo $words['DTL_0046'];?></a></li>
                      <li class="<?php if($this->session->userdata('tab_index')=='sub_category') echo 'active';?>"><a href="#sub_category" data-toggle="tab" id="sub_category_btn"> &nbsp;&nbsp;<?php echo $words['DTL_0254'];?></a></li>
                      <li class="<?php if($this->session->userdata('tab_index')=='speciality') echo 'active';?>"><a href="#speciality" data-toggle="tab" id="speciality_btn"> &nbsp;&nbsp;<?php echo $words['DTL_0249'];?></a></li>
                    </ul>

              </div> <!-- /.col -->

              <div class="col-md-9 col-xs-12">

                <div id="myTabContent" class="tab-content stacked-content">
                  <div class="tab-pane fade <?php if($this->session->userdata('tab_index')=='' || $this->session->userdata('tab_index')=='category') echo 'active in';?>" id="category">
                 

          
                      <div class="heading-wrapper">
                          <h3 class="heading">
                            <?php echo $words['DTL_0046'];?>
                          </h3>			
                          <a href="<?php echo site_url('admin/speciality/main_category_page');?>"><button type="button" id="add_main_category" class="btn btn-secondary pull-right-md"><?php echo $words['DTL_0125'];?> &nbsp<i class="fa fa-plus-circle"></i></button></a>
                      </div>
					      <form class="form-group user-search filter_block" method="post" action="<?php echo site_url('admin/speciality')?>" >

                <div class="row">

                      <div class="col-md-4">

                        <div class="form-group">
                           <label><?php echo $words['DTL_0046'];?>: </label>
                            <input type="text" id="" name="searchByMainCategory" value="<?php //if($this->session->userdata('searchByName')!=''){ echo $this->session->userdata('searchByName');}?>" class="form-control">
                        </div>

                      </div>
          <div class="col-md-4">
              
                               <div class="form-group">
                              <label><?php echo $words['DTL_0140'];?></label>

                            <select id=""  name="main_per_page" class="form-control">
                              <option value="10" <?php if($this->session->userdata('main_per_page') == 10){ ?>selected = "selected"<?php } ?>>10</option>
                              <option value="20" <?php if($this->session->userdata('main_per_page') == 20){ ?>selected = "selected"<?php } ?>>20</option>
                              <option value="50" <?php if($this->session->userdata('main_per_page') == 50){ ?>selected = "selected"<?php } ?>>50</option>
                              <option value="100" <?php if($this->session->userdata('main_per_page') == 100){ ?>selected = "selected"<?php } ?>>100</option>
                              <option value="500" <?php if($this->session->userdata('main_per_page') == 500){ ?>selected = "selected"<?php } ?>>500</option>
                            </select>

                             </div>
                          </div>

                    </div>
                       
           
                       <input type="submit" name="submit" id="submit_btn" class="btn btn-primary">
             
              </form>
          

         <?php if(!empty($main_specialities)){?>

                      <div class="table-responsive pagination_inside">

              <table 
                class="table table-striped table-bordered table-hover table-highlight table-checkable" 
                data-provide="datatable" 
                data-display-rows="10"
                data-info="true"
                data-search="true"
                data-length-change="true"
                data-paginate="true"
              >
                  <thead>
                    <tr>
                      <th data-filterable="false" data-sortable="true" data-direction="asc">#</th>
                      <th data-direction="asc" data-filterable="false" data-sortable="true"><?php echo $words['DTL_0046'];?></th>
					  <th><?php echo $words['DTL_0002'];?></th>
                    </tr>
                  </thead>
                  <tbody id="main_category_table">
					<?php $count = 1;
          
						foreach($main_specialities as $main_speciality){?>
						<tr>
						  <td><?php echo $count;?></td>						  
						  <td><?php echo $main_speciality['category_name'];?></td>						  
						  <td class="double_icons">
							  <a href="<?php echo site_url('admin/speciality/main_category_page')?>/<?php echo $main_speciality['id']?>"><button class="btn btn-xs btn-secondary"><i class="fa fa-pencil"></i></button></a>
							 
							  <a href="<?php echo site_url('admin/speciality/delete_main_category')?>/<?php echo $main_speciality['id']?>" onclick="return confirm('<?php echo $words['DTL_0322'];?>');"><button class="btn btn-xs btn-primary"><i class="fa fa-trash-o"></i></button></a>
							</td>
						</tr>
					<?php $count++;
					} ?>
                    <tr><td colspan="4"><?php echo create_ajax_paging('get_main_pagination',$total_main_speciality,$main_offset,array($main_limit),$main_limit);?></td></tr>

                  </tbody>
                </table>
              </div> <!-- /.table-responsive -->


         <?php }
          else
            {
              echo '<div class="alert alert-danger">
        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
       No Main Specialties
      </div>';
              }?>



                  </div>

                  <div class="tab-pane fade <?php if($this->session->userdata('tab_index')=='sub_category') echo 'active in';?>" id="sub_category">
             
                      <div class="heading-wrapper">
                          <h3 class="heading">
                            <?php echo $words['DTL_0254'];?>
                          </h3>
                          <a href="<?php echo site_url('admin/speciality/sub_category_page')?>"><button type="button" id="add_sub_category" class="btn btn-secondary pull-right-md"><?php echo $words['DTL_0127'];?> &nbsp<i class="fa fa-plus-circle"></i></button></a>
                      </div>
            
					
                <form class="form-group user-search filter_block" method="post" action="<?php echo site_url('admin/speciality')?>" >

                <div class="row">

                      <div class="col-md-4">

                        <div class="form-group">
                           <label><?php echo $words['DTL_0254'];?>: </label> <input type="text" id="" name="searchBySubCategory" value="<?php //if($this->session->userdata('searchByName')!=''){ echo $this->session->userdata('searchByName');}?>" class="form-control">
                        </div>

                      </div>
         
                            
                             <div class="col-md-4">
                              <div class="form-group">
             
                              <label><?php echo $words['DTL_0140'];?></label>

                            <select id=""  name="sub_per_page" class="form-control">
                              <option value="10" <?php if($this->session->userdata('sub_per_page') == 10){ ?>selected = "selected"<?php } ?>>10</option>
                              <option value="20" <?php if($this->session->userdata('sub_per_page') == 20){ ?>selected = "selected"<?php } ?>>20</option>
                              <option value="50" <?php if($this->session->userdata('sub_per_page') == 50){ ?>selected = "selected"<?php } ?>>50</option>
                              <option value="100" <?php if($this->session->userdata('sub_per_page') == 100){ ?>selected = "selected"<?php } ?>>100</option>
                              <option value="500" <?php if($this->session->userdata('sub_per_page') == 500){ ?>selected = "selected"<?php } ?>>500</option>
                            </select>

                          </div>

                          </div>
                    </div>
                       
           
                       <input type="submit" name="submit" id="submit_btn" class="btn btn-primary">
             
              </form>
            <?php 
          if(!empty($sub_categories)){?>

                      <div class="table-responsive pagination_inside">

              <table 
                class="table table-striped table-bordered table-hover table-highlight table-checkable" 
                data-provide="datatable" 
                data-display-rows="10"
                data-info="true"
                data-search="true"
                data-length-change="true"
                data-paginate="true"
              >
                  <thead>
                    <tr>
                      <th data-filterable="false" data-sortable="true" data-direction="asc">#</th>
                      <th data-direction="asc" data-filterable="false" data-sortable="true"><?php echo $words['DTL_0046'];?></th>
                      <th data-filterable="false" data-sortable="true"><?php echo $words['DTL_0254'];?></th>
					  <th><?php echo $words['DTL_0002'];?></th>
                    </tr>
                  </thead>
                  <tbody id="sub_category_table">
					<?php $count = 1;
						foreach($sub_categories as $sub_category){?>
						<tr>
						  <td><?php echo $count;?></td>
                            <td><?php echo $sub_category['parent_category_name'];?></td>
                            <td><?php echo $sub_category['category_name'];?></td>
						  <td class="double_icons">
							  <a href="<?php echo site_url('admin/speciality/sub_category_page')?>/<?php echo $sub_category['id']?>"><button class="btn btn-xs btn-secondary"><i class="fa fa-pencil"></i></button></a>
							 
							  <a href="<?php echo site_url('admin/speciality/delete_sub_category')?>/<?php echo $sub_category['id']?>" onclick="return confirm('<?php echo $words['DTL_0322'];?>');"><button class="btn btn-xs btn-primary"><i class="fa fa-trash-o"></i></button></a>
							</td>
						</tr>
					<?php $count++;
					}?>
                    <tr><td colspan="4"><?php echo create_ajax_paging('get_sub_pagination',$total_sub_speciality,$sub_offset,array($sub_limit),$sub_limit);?></td></tr>

                  </tbody>
                </table>
              </div> <!-- /.table-responsive -->
              <?php }else{

             echo '   <div class="alert alert-danger">
        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
      No Sub Categories
      </div>';
         
        }
          ?>

                  </div>

                  <div class="tab-pane fade <?php if($this->session->userdata('tab_index')=='speciality') echo 'active in';?>" id="speciality">
         
                      <div class="heading-wrapper">
                          <h3 class="heading">
                            <?php echo $words['DTL_0249'];?>
                          </h3>
                          <a href="<?php echo site_url('admin/speciality/speciality_category_page')?>"><button type="button" id="add_speciality" class="btn btn-secondary pull-right-md"><?php echo $words['DTL_0126'];?> &nbsp<i class="fa fa-plus-circle"></i></button></a>
                      </div>
           
					
        

             <form class="form-group user-search filter_block" method="post" action="<?php echo site_url('admin/speciality')?>" >

                <div class="row">
                      <div class="col-md-4">

                        <div class="form-group">
                           <label><?php echo $words['DTL_0249'];?>: </label> <input type="text" id="" name="searchBySpeciality" value="<?php //if($this->session->userdata('searchByName')!=''){ echo $this->session->userdata('searchByName');}?>" class="form-control">
                        </div>

                      </div>

                         

                             <div class="col-md-4">
              
                               <div class="form-group">
                              <label><?php echo $words['DTL_0140'];?></label>

                            <select id=""  name="speciality_per_page" class="form-control">
                              <option value="10" <?php if($this->session->userdata('speciality_per_page') == 10){ ?>selected = "selected"<?php } ?>>10</option>
                              <option value="20" <?php if($this->session->userdata('speciality_per_page') == 20){ ?>selected = "selected"<?php } ?>>20</option>
                              <option value="50" <?php if($this->session->userdata('speciality_per_page') == 50){ ?>selected = "selected"<?php } ?>>50</option>
                              <option value="100" <?php if($this->session->userdata('speciality_per_page') == 100){ ?>selected = "selected"<?php } ?>>100</option>
                              <option value="500" <?php if($this->session->userdata('speciality_per_page') == 500){ ?>selected = "selected"<?php } ?>>500</option>
                            </select>

                            </div>
                          </div>

                    </div>
                       
           
                       <input type="submit" name="submit" id="submit_btn" class="btn btn-primary">
             
              </form>
                <?php 
          if(!empty($specialities)){?>
                      <div class="table-responsive pagination_inside">

              <table 
                class="table table-striped table-bordered table-hover table-highlight table-checkable" 
                data-provide="datatable" 
                data-display-rows="10"
                data-info="true"
                data-search="true"
                data-length-change="true"
                data-paginate="true"
              >
                  <thead>
                    <tr>
                      <th data-filterable="false" data-sortable="true" data-direction="asc">#</th>
                      <th data-filterable="false" data-sortable="true"><?php echo $words['DTL_0254'];?></th>
                      <th data-filterable="false" data-sortable="true"><?php echo $words['DTL_0249'];?></th>
					  <th><?php echo $words['DTL_0002'];?></th>
                    </tr>
                  </thead>
                  <tbody id="speciality_table">
					<?php $count = 1;
						foreach($specialities as $speciality){?>
						<tr>
						  <td><?php echo $count;?></td>
						  <td><?php echo $speciality['parent_category_name'];?></td>
                            <td><?php echo $speciality['category_name'];?></td>

                            <td class="double_icons">
							  <a href="<?php echo site_url('admin/speciality/speciality_category_page')?>/<?php echo $speciality['id']?>"><button class="btn btn-xs btn-secondary"><i class="fa fa-pencil"></i></button></a>
							
							  <a href="<?php echo site_url('admin/speciality/delete_speciality')?>/<?php echo $speciality['id']?>" onclick="return confirm('<?php echo $words['DTL_0322'];?>');"><button class="btn btn-xs btn-primary"><i class="fa fa-trash-o"></i></button></a>
							</td>
						</tr>
					<?php $count++;
					} ?>
                    <tr><td colspan="4"><?php echo create_ajax_paging('get_speciality_pagination',$total_speciality,$sub_offset,array($sub_limit),$sub_limit);?></td></tr>


                  </tbody>
                </table>
              </div> <!-- /.table-responsive -->
              
<?php } else
          {

            echo '<div class="alert alert-danger">
        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
       No Specialties
      </div> ';
            }?>
          
                  </div>

                 
                </div>

              </div> <!-- /.col -->

            </div> <!-- /.row -->