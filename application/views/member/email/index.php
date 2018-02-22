      <br>
    
      <div class="row">

   

        <div class="col-md-12">

            <div class="content-header">
          <h2 class="content-header-title"><?php echo $words['DTL_0108'];?></h2>
          <ol class="breadcrumb">
          <li><a href="<?php echo site_url('admin/home') ?>"><?php echo $words['DTL_0122'];?></a></li>
          <li class="active"><?php echo $words['DTL_0108'];?></li>
        </ol>
          </div>

          <div class="table-responsive">
          <table class="table table-bordered table-highlight media-table">
            <thead>
              <tr>
                <th>#</th>
				<th><?php echo $words['DTL_0258'];?></th>
                <th><?php echo $words['DTL_0104'];?></th>
                <th><?php echo $words['DTL_0284'];?></th>
                <th><?php echo $words['DTL_0078'];?></th>
                <th><?php echo $words['DTL_0107'];?></th>
                <th><?php echo $words['DTL_0002'];?></th>
                <th><?php echo $words['DTL_0253'];?></th>
              </tr>
            </thead>
            <tbody>
            <?php 
              $count=1;
              foreach($emails as $email){
            ?>
              <tr>
                <td><?php echo $count?></td>
				<td class="suject_class"><?php echo $email['subject']?></td>
                <td class="email_class"><?php echo $email['email']?></td>
                <td><?php echo $email['type_name']?></td>
                <td><?php echo $email['email_date']?></td>
                <td><?php echo $email['email_type_name']?></td>
                <td class="double_icons">
                  <button class="btn btn-xs btn-secondary email_send_button" id_attribute="<?php echo $email['email_id'];?>"><i class="fa fa-pencil"></i></button>
                </td>
				<td class="double_icons">
					<?php if($email['flag_bit'] == 1) {?> 
						<a href="<?php echo base_url() ?>admin/email/changeStatus/<?php echo $email['email_id']?>/0">
							<button class="btn btn-xs btn-success" title="<?php echo $words['DTL_0361']?>" id_attribute="<?php echo $email['email_id'];?>">		<?php echo $words['DTL_0003'];?>
							</button>
						</a>
					<?php }
					else {?>
						<a href="<?php echo base_url() ?>admin/email/changeStatus/<?php echo $email['email_id']?>/1">
							<button class="btn btn-xs btn-danger" title="<?php echo $words['DTL_0360']?>" id_attribute="<?php echo $email['email_id'];?>">		<?php echo $words['DTL_0341'];?>
							</button>
						</a>
					<?php } ?>
                </td>
              </tr>
              <?php 
                $count++;
                }
              ?>

            </tbody>
          </table>
              <?php if (!empty($pagination)) {
                  echo $pagination;
              }?>
          </div>        

        </div> <!-- /.col -->

      </div> <!-- /.row -->



    </div> <!-- /.content-container -->
      
  </div> <!-- /.content -->

</div> <!-- /.container -->


