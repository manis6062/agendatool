<style>
#overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #000;
    filter:alpha(opacity=50);
    -moz-opacity:0.5;
    -khtml-opacity: 0.5;
    opacity: 0.5;
    z-index: 10000;
    text-align: center;
}
#overlay img {
  max-width: 100%;
}
</style>
<script>
$(document).ready(function(){
  $('.deactivate_btn').on('click',function(){
    var overlay = jQuery('<div id="overlay"><img src="<?php echo base_url()?>assets/img/loading.gif"></div>');
    overlay.appendTo(document.body);
    var obj = $(this);
    var general_id = $(this).attr('result');
    $.ajax({
          url: site_url+'admin/user/deactivate',
          type: "POST",
          data: {"id": general_id},
          success: function(result){
            window.location.reload();
          }
        });
  });
  $('.activate_btn').on('click',function(){
     var overlay = jQuery('<div id="overlay"><img src="<?php echo base_url()?>assets/img/loading.gif"></div>');
    overlay.appendTo(document.body);
    var obj = $(this);
    var general_id = $(this).attr('result');
    $.ajax({
          url: site_url+'admin/user/activate',
          type: "POST",
          data: {"id": general_id},
          success: function(result){
            window.location.reload();
          }
        });
  });
});
</script>


    
      <div class="row">

   

        <div class="col-md-12">
      
      

      <div class="content-header">
      <h2 class="content-header-title"><?php echo $words['DTL_0286'];?></h2>
      <ol class="breadcrumb">
          <li><a href="<?php echo site_url('admin/home') ?>"><?php echo $words['DTL_0122'];?></a></li>
          <li class="active"><?php echo $words['DTL_0286'];?></li>
        </ol>
      </div>

      <h3 class="heading">
             <?php echo $words['DTL_0231'];?>:
      </h3>
		
		  <form class="form-group user-search filter_block" method="post" action="<?php echo site_url('admin/user')?>" >



        <div class="row">

              <div class="col-md-4">

                <div class="form-group">
                   <label><?php echo $words['DTL_0155'];?>: </label> <input type="text" id="" name="searchByName" value="<?php if($this->session->userdata('searchByName')!=''){ echo $this->session->userdata('searchByName');}?>" class="form-control">
                </div>

              </div>


               <div class="col-md-4">

                <div class="form-group">
                 <label> <?php echo $words['DTL_0285'];?>: </label><input type="text" id="" name="searchByUserName" value="<?php if($this->session->userdata('searchByUserName')!=''){ echo $this->session->userdata('searchByUserName');}?>" class="form-control">
                </div>

              </div>



               <div class="col-md-4">

                <div class="form-group">
                  <label> <?php echo $words['DTL_0105'];?>: </label><input type="text" id="" name="searchByEmailAddress" value="<?php if($this->session->userdata('searchByEmailAddress')!=''){ echo $this->session->userdata('searchByEmailAddress');}?>" class="form-control">
                </div>

              </div>

            </div>
			
     

      <div class="notification_filter form-group">
		  
           <div class="row">
               <div class="col-md-12">

               
                   <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
       <label><?php echo $words['DTL_0280'];?></label>
		  <select id=""  name="filter" class="form-control">
				<option value="All" <?php //if($this->session->userdata('filter') == 'All'){ echo 'selected = "selected"'; } ?>><?php echo $words['DTL_0034'];?></option>
				<option value="Trainer" <?php //if($this->session->userdata('filter') == 'Trainer'){ echo 'selected = "selected"'; } ?>><?php echo $words['DTL_0267'];?></option>
				<option value="Company" <?php //if($this->session->userdata('filter') == 'Company'){ echo 'selected = "selected"'; } ?>><?php echo $words['DTL_0062'];?></option>
		  </select>
       </div>
       </div>
      <div class="col-md-4">
                        <div class="form-group">
      <label><?php echo $words['DTL_0003'];?></label>
      <select id=""  name="active_status" class="form-control">
              <option value="All" <?php //if($this->session->userdata('active_status') == 'All'){ echo 'selected = "selected"'; } ?>><?php echo $words['DTL_0034'];?></option>
              <option value="Active" <?php //if($this->session->userdata('active_status') == 'Active'){ echo 'selected = "selected"'; } ?>><?php echo $words['DTL_0003'];?></option>
              <option value="Inactive" <?php //if($this->session->userdata('active_status') == 'Inactive'){ echo 'selected = "selected"'; } ?>><?php echo $words['DTL_0341'];?></option>
        </select>
		    </div>
		
        </div>
        <div class="col-md-4">
            <div class="form-group">
        <label><?php echo $words['DTL_0140'];?></label>

		  <select id=""  name="per_page" class="form-control">
				<option value="10" <?php if($this->session->userdata('per_page') == 10){ ?>selected = "selected"<?php } ?>>10</option>
				<option value="20" <?php if($this->session->userdata('per_page') == 20){ ?>selected = "selected"<?php } ?>>20</option>
				<option value="50" <?php if($this->session->userdata('per_page') == 50){ ?>selected = "selected"<?php } ?>>50</option>
				<option value="100" <?php if($this->session->userdata('per_page') == 100){ ?>selected = "selected"<?php } ?>>100</option>
				<option value="500" <?php if($this->session->userdata('per_page') == 500){ ?>selected = "selected"<?php } ?>>500</option>
		  </select>

          </div>
        </div>
                  </div>
               
             
            </div>
            </div>
            </div>
            <input type="submit" value="<?php echo $words['DTL_0259']?>" name="submit" id="submit_btn" class="btn btn-primary">
      </form>

   
    <?php if(!empty($users)){?>
          <div class="table-responsive user-list-table">
          <table class="table table-bordered table-highlight media-table">
            <thead>
              <tr>
				<th>#</th>
                <th><?php echo $words['DTL_0155'];?></th>
                <th><?php echo $words['DTL_0285'];?></th>
                <th><?php echo $words['DTL_0104'];?></th>
                <th><?php echo $words['DTL_0185'];?></th>
				<th><?php echo $words['DTL_0284'];?></th>
                <th style="width:120px;min-width:120px;"><?php echo $words['DTL_0002'];?></th>
                <th><?php echo $words['DTL_0253'];?></th>
              </tr>
            </thead>
            <tbody>
            <?php 
				$count = 1;
              foreach($users as $user){
            ?>
              <tr>
				<td><?php echo $count;?></td>
                <td><?php echo $user['name']?></td>
                <td><?php echo $user['username']?></td>
                <td><?php echo $user['email_address']?></td>
                <td><?php echo $user['phone_no']?></td>
				<td><?php echo $user['type_name']?></td>
              
                <td class="double_icons">
					<a href="<?php echo site_url('member/profile/view_profile')?>/<?php echo $user['general_id']?>">
					<button class="btn btn-xs btn-info"><i class="fa fa-eye"></i></button>
					</a>
					<a href="<?php echo site_url('member/profile/edit_profile')?>/<?php echo $user['general_id']?>">
					<button class="btn btn-xs btn-secondary"><i class="fa fa-pencil"></i></button>
					</a>
<!--					<a href="--><?php //echo site_url('admin/user/change_to_inactive')?><!--/--><?php //echo $user['general_id']?><!--" onclick="return confirm('Are you sure you want to delete user');"><button class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></button></a>-->
					<a href="<?php echo site_url('admin/user/delete_user')?>/<?php echo $user['general_id']?>" onclick="return confirm('Are you sure you want to delete user');"><button class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></button></a>
                </td>

                  <td><?php if($user['is_active'] == 1){?>
                  <button class="btn btn-xs btn-success deactivate_btn" result="<?php echo $user['general_id']?>"><?php echo $words['DTL_0003'];?></button>
                  <?php }
                  elseif($user['is_active'] == 0){?>
                  <button class="btn btn-xs btn-primary activate_btn" result="<?php echo $user['general_id']?>"><?php echo $words['DTL_0341'];?></button>
                  <?php }?>
                  </td>
              </tr>
              <?php 
				$count++;
                }
              ?>
            </tbody>
          </table> 
			
          </div>     

          <?php echo $pagination;
          }
          else{ 

            ?>
            
            <div class="alert alert-danger">
        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
            <?php 
            echo $words['DTL_0165']; }?>
            </div>
            
        </div> <!-- /.col -->

      </div> <!-- /.row -->



    </div> <!-- /.content-container -->
      
  </div> <!-- /.content -->

</div> <!-- /.container -->
