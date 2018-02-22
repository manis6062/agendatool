
      <div class="row">

   

        <div class="col-md-12">

          <div class="content-header">
          <h2 class="content-header-title"><?php echo $words['DTL_0184'];?></h2>

          <ol class="breadcrumb">
          <li><a href="<?php echo site_url('admin/home') ?>"><?php echo $words['DTL_0122'];?></a></li>
          <li class="active"><?php echo $words['DTL_0184'];?></li>
        </ol>
         
          </div>

    <div class="heading-wrapper">
      <h3 class="heading">
                  <?php echo $words['DTL_0231'];?>
      </h3>
        <button type="button" class="btn btn-secondary pull-right-md add_btn" id="add_subscription_btn"> <?php echo $words['DTL_0022'];?></button>
    </div>
     <form class="form-group user-search filter_block" method="post" action="<?php echo site_url('admin/payment')?>" >



        <div class="row">

              <div class="col-md-4">

                <div class="form-group">
                   <label><?php echo $words['DTL_0155'];?>: </label> <input type="text" id="" name="searchByName" value="<?php //if($this->session->userdata('searchByName')!=''){ echo $this->session->userdata('searchByName');}?>" class="form-control">
                </div>

              </div>


               <div class="col-md-4">

                <div class="form-group">
                 <label> <?php echo $words['DTL_0036'];?>: </label><input type="text" id="" name="searchByAmount" value="<?php //if($this->session->userdata('searchByAmount')!=''){ echo $this->session->userdata('searchByAmount');}?>" class="form-control"> 
                </div>

              </div>



               <div class="col-md-4">

                <div class="form-group">
                  <label> <?php echo $words['DTL_0252'];?>: </label><input type="text" id="dp-ex-3" data-date-format="yyyy-mm-dd" name="searchByStartDate" value="<?php //if($this->session->userdata('searchByStartDate')!=''){ echo $this->session->userdata('searchByStartDate');}?>" class="form-control" data-auto-close="true" data-date-autoclose="true">
                </div>

              </div>

            </div>



      <div class="notification_filter form-group">
      
           <div class="row">
               <div class="col-md-12">

               
                   <div class="row">
                 
                    <div class="col-md-4">

                      <div class="form-group">
                        <label> <?php echo $words['DTL_0110'];?>: </label><input type="text" id="dp-ex-4" data-date-format="yyyy-mm-dd" name="searchByExpiryDate" value="<?php //if($this->session->userdata('searchByExpiryDate')!=''){ echo $this->session->userdata('searchByExpiryDate');}?>" class="form-control" data-auto-close="true" data-date-autoclose="true">
                      </div>

                    </div>
                    
                     <div class="col-md-4">
      
     
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
     
     
      
    

               <input type="submit" name="submit" id="submit_btn" class="btn btn-primary">
     
      </form>
      <?php if(!empty($subscriptions)){?>
		<div id='no_user' style="display:none">
		<?php echo $words['DTL_0165'];?>
		</div>
          <div class="table-responsive">
          <table class="table table-bordered table-highlight media-table">
            <thead>
              <tr>
                <th>#</th>
                <th><?php echo $words['DTL_0155'];?></th>
                <th><?php echo $words['DTL_0036'];?></th>
                <th><?php echo $words['DTL_0252'];?></th>
                <th><?php echo $words['DTL_0110'];?></th>
				<th><?php echo $words['DTL_0002'];?></th>
              </tr>
            </thead>
            <tbody>
            <?php 
              $count=1;
              foreach($subscriptions as $subscription){
            ?>
              <tr>
                <td><?php echo $count?></td>
                <td class="name_class"><?php echo $subscription['name']?></td>
                <td><?php echo $subscription['amount']?></td>
                <td><?php echo $subscription['from_date']?></td>
                <td><?php echo $subscription['to_date']?></td>
				<td class="double_icons">
				  <button class="btn btn-xs btn-secondary edit_subscription_btn" result="<?php echo $subscription['subscription_id']?>"><i class="fa fa-pencil"></i></button>
				  <a href="<?php echo site_url('admin/payment/delete_subscription')?>/<?php echo $subscription['subscription_id']?>" onclick="return confirm('Are you sure you want to delete subscription');"><button class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></button></a>
				</td>
              </tr>
              <?php 
                $count++;
                }
              ?>
            </tbody>
          </table>      
          </div>        

        </div> <!-- /.col -->
        <?php echo $pagination;?>

      </div> <!-- /.row -->
      <?php }  else { ?>
      <div class="alert alert-danger">
          <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
          <?php echo $words['DTL_0358']; }?>
      </div>




    </div> <!-- /.content-container -->
      
  </div> <!-- /.content -->

</div> <!-- /.container -->

<script>
function validate_add_subscription_form(){
	var result;
	if($('#select_user').val()=="")
	{
		alert("<?php echo $words['DTL_0337']?>");
		result = false;
	}
	if($('#add_subscription_amount').val()=="")
	{
		alert('Amount '+"<?php echo $words['DTL_0301']?>");
		if(isNaN($('#add_subscription_amount').val()))
			alert('Amount '+"<?php echo $words['DTL_0303']?>");
		result =  false;
	}
	
	else
		result = true;
	return result;
}
</script>

<script>
function validate_edit_subscription_amount_form(){
	var result;
	if($('#edit_subscription_amount').val()=="")
	{
		alert('Amount '+"<?php echo $words['DTL_0301']?>");
		if(isNaN($('#edit_subscription_amount').val()))
			alert('Amount '+"<?php echo $words['DTL_0303']?>");
		result =  false;
	}
	
	else
		result = true;
	return result;
}
</script>


<div id="all_subscription_modal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title"><?php echo $words['DTL_0022'];?></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="<?php echo site_url('admin/payment/add_subscription')?>" class="add_subscription" onsubmit="return validate_add_subscription_form();">
        
      


        <div class="row form-group">
          <div class="col-xs-12 col-sm-3">
          <label><?php echo $words['DTL_0237'];?>:</label> 
          </div>
           <div class="col-xs-12 col-sm-8">
             <input type="text" class="form-control" id="select_user" name="select_user">
           </div>
        </div>
  			<div class="row">
  			<div class="col-xs-12 col-sm-3">
  			  <label><?php echo $words['DTL_0036'];?>:</label> 
  			  </div>		
  			   <div class="col-xs-12 col-sm-8">
  						<input type="text" id="add_subscription_amount" class="form-control" name="amount">
  					
  				</div>
  			</div>
			<input type="hidden" name="general_id" id="general_id_add_subscription" value="">
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $words['DTL_0057'];?></button>
        <button type="submit" name="submit" id="submit" class="btn btn-primary"><?php echo $words['DTL_0021'];?></button>
      </div>

      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="edit_subscription_modal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title"><?php echo $words['DTL_0097'];?></h3>
      </div>
      <div class="modal-body">
        <form method="post" action="<?php echo site_url('admin/payment/edit_subscription')?>" class="add_subscription" onsubmit="return validate_edit_subscription_amount_form();">
        <div class="row form-group">
          <div class="col-xs-12 col-sm-3">
          <label><?php echo $words['DTL_0279'];?>: </label>
          </div>
           <div class="col-xs-12 col-sm-8">
            <input id="user_name" type="text" value="" class="form-control" disabled="">
           </div>
        </div>
			<div class="row">
				<div class="col-xs-12 col-sm-3">
			  <label><?php echo $words['DTL_0036'];?>:  </label>
			  </div>		
			   <div class="col-xs-12 col-sm-8">
						<input type="text" id="edit_subscription_amount" class="form-control" name="amount">
					
				</div>
			</div>
			<input type="hidden" name="id" id="edit_subscription_id" value="">
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $words['DTL_0057'];?></button>
        <button type="submit" name="submit" id="submit" class="btn btn-primary"><?php echo $words['DTL_0098'];?></button>
      </div>

      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



      <script>
          $("#select_user").keydown(function (event) {
              if (event.keyCode === $.ui.keyCode.TAB){ //&& $(this).data("autocomplete").menu.active){
                  var nextEl = findNextTabStop(element);
                  nextEl.focus();
                  event.preventDefault();

              }
          });

          function findNextTabStop(el) {
              var universe = document.querySelectorAll('input, button, select, textarea, a[href]');
              var list = Array.prototype.filter.call(universe, function(item) {return item.tabIndex >= "0"});
              var index = list.indexOf(el);
              return list[index + 1] || list[0];
          }

      </script>
