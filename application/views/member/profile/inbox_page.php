<div class="content-header">
    <h2 class="content-header-title"><?php echo $words['DTL_0152'];?></h2>
    <ol class="breadcrumb">
          <li><a href="<?php echo site_url('member/profile') ?>"><?php echo $words['DTL_0205'];?></a></li>
          <li class="active"><?php echo $words['DTL_0152'];?></li>
        </ol>
</div>
<?php if(isset($mails)){
	?>
        <section class="inbox">
                <div class="table-inbox-wrap table-responsive">
                    <table class="table table-inbox table-hover table-bordered table-highlight">
                        <thead>
                      <tr>
                        <th>#</th>
                        <th>Sent / Received</th>
                        <th><?php echo $words['DTL_0155'];?></th>
                        <th><?php echo $words['DTL_0151'];?></th>
                        <th><?php echo $words['DTL_0082'];?></th>
                       
                      </tr>
                    </thead>
                <tbody>
                <?php 
                foreach($mails as $message){
                if($message['email_to_id'] == $userdata['id']){?>
                <tr class="">
                      <td>1</td>
                <td class="view-message"><a href="<?php echo site_url('member/profile/inbox_detail').'/'.$message['id']?>"><!--  <strong><?php echo $words['DTL_0209'];?>: </strong> --><i class="fa fa-reply icon_received" aria-hidden="true"></i></a></td> 
                  
                    <td class="view-message dont-show"><a href="<?php echo site_url('member/profile/inbox_detail').'/'.$message['id']?>"><?php echo $message['sent_by']['name']?></a></td>
                    <td class="view-message"><a href="<?php echo site_url('member/profile/inbox_detail').'/'.$message['id']?>">
                    <?php 
                    $limited_msg = character_limiter($message['message'],50);
                    echo $limited_msg;
                    ?></a></td>
                  <!--   <td class="view-message inbox-small-cells"></td> -->
                     <!-- <td class="view-message  inbox-small-cells"><img src="<?php echo base_url()?>uploads/userimage/<?php echo $message['sent_by']['photo_logo']?>" width="125"></td> -->
                    <td class="view-message text-right"><a href="<?php echo site_url('member/profile/inbox_detail').'/'.$message['id']?>"><?php echo $message['created_date']?></a></td>
                </tr>
                
                <?php } elseif($message['email_by_id'] == $userdata['id']){?>
                <tr class="">
                    <td>1</td>
                     <td class="view-message"><a href="<?php echo site_url('member/profile/inbox_detail').'/'.$message['id']?>"><!-- <strong><?php echo $words['DTL_0245'];?>: </strong> --> <i class="fa fa-share icon_sent" aria-hidden="true"></i></a></td> 
                    <td class="view-message dont-show"><a href="<?php echo site_url('member/profile/inbox_detail').'/'.$message['id']?>"><?php echo $message['sent_to']['name']?></a></td>
                    <td class="view-message"><a href="<?php echo site_url('member/profile/inbox_detail').'/'.$message['id']?>">
                    <?php 
                    $limited_msg= character_limiter($message['message'],50); 
                    echo $limited_msg;
                    ?></a></td>
                  <!--   <td class="view-message inbox-small-cells"></td> -->
                     <!-- <td class="view-message  inbox-small-cells"><img src="<?php echo base_url()?>uploads/userimage/<?php echo $message['sent_to']['photo_logo']?>" width="125"></td> -->
                    <td class="view-message text-right"><a href="<?php echo site_url('member/profile/inbox_detail').'/'.$message['id']?>"><?php echo $message['created_date']?></a></td>
                </tr>
                <?php }}?>
                </tbody>
                </table>
        
                </div>
        </section>
<?php } else{?>

<div class="alert alert-danger">
        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
        <?php echo $words['DTL_0163'];?>
      </div>

<?php }?> 