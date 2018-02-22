
      <br>
    
      <div class="row">

   

        <div class="col-md-12">

          <h4 class="heading">
            <?php echo $words['DTL_0273'];?>
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
              foreach($trial_periods as $trial_period){
            ?>
              <tr>
                <td><?php echo $count?></td>
                <td><?php echo $trial_period['trial_period_time']?></td>
                <td><?php if($trial_period['user_type_id']==2)
							{
								echo "Company";
							}
							elseif($trial_period['user_type_id']==3)
							{
								echo "Trainer";
							}
				?></td>
				<td>
                  <button class="btn btn-xs btn-secondary edit_btn" info="<?php echo $trial_period['id']?>"><i class="fa fa-pencil"></i></button>
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


