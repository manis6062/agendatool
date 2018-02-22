
      <br>
    
      <div class="row">

   

        <div class="col-md-12">

          <h4 class="heading">
            <?php echo $words['DTL_0256'];?>
          </h4>

          <table class="table table-bordered table-highlight media-table">
            <thead>
              <tr>
                <th>#</th>
                <th><?php echo $words['DTL_0264'];?></th>
                <th><?php echo $words['DTL_0284'];?></th>
                <th><?php echo $words['DTL_0253'];?></th>
              </tr>
            </thead>
            <tbody>
            <?php 
              $count=1;
              foreach($subscriptions as $subscription){
            ?>
              <tr>
                <td><?php echo $count?></td>
                <td><?php echo $subscription['trial_period_time']?></td>
                <td><?php if($subscription['user_type_id']==2)
							{
								echo "Company";
							}
							elseif($subscription['user_type_id']==3)
							{
								echo "Trainer";
							}
				?></td>
				<td>
                  <button class="btn btn-xs btn-secondary edit_sub_btn" info="<?php echo $subscription['id']?>"><i class="fa fa-pencil"></i></button>
				</td>
              </tr>
              <?php 
                $count++;
                }
              ?>
            </tbody>
          </table>              

        </div> <!-- /.col -->

      </div> <!-- /.row -->



    </div> <!-- /.content-container -->
      
  </div> <!-- /.content -->

</div> <!-- /.container -->


