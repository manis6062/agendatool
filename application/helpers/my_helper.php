<?php
if(!function_exists('create_ajax_paging')) {
  function create_ajax_paging($function_name,$no_of_rows,$offset,$argument_arr = array(),$limit = 10)
  {
    if($no_of_rows>$limit)
    {

      $arugment_var = "";
      foreach($argument_arr as $arguments){
        $arugment_var .= $arguments.",";
      }
      $links = "";
      $limitstr = "";
      $url = $links;
      $page = $offset;
      $per_page = $limit;
      $start = ($page - 1) * $per_page;               
      $adjacents = "2"; 
      $prev = $page - 1;              
      $next = $page + 1;
      $lastpage = ceil($no_of_rows/$per_page);
      $lpm1 = $lastpage - 1;

      $pagination = "";
      if($lastpage > 1)
      {
        $pagination .= "<ul class='pagination'>";
        //$pagination .= "<li class='details'>Page ".($page+1)." of $lastpage</li>";
        if($page != 0)
        {
          //$pagination.= "<li><a onclick='".$function_name."(".$arugment_var.(0).")'>First</a></li>";
          $pagination.= "<li><a href='javascript:void(0)' onclick='".$function_name."(".$arugment_var.($prev).")'>← Previous</a></li>";
        }
        else
        {
          //$pagination.= "<li class='active'><a>First</a></li>";
          $pagination.= "<li class='active'><a>← Previous</a></li>";
        }
        if ($lastpage < 7 + ($adjacents * 2))
        {
          for ($counter = 1; $counter <= $lastpage; $counter++)
          {
            if ($counter == $page + 1 )
              $pagination.= "<li class='active'><a>$counter</a></li>";
            else
              $pagination.= "<li><a href='javascript:void(0)' onclick='".$function_name."(".$arugment_var.($counter-1).")'>$counter</a></li>";          
          }
        }
        elseif($lastpage > 5 + ($adjacents * 2))
        {
          if($page < 1 + ($adjacents * 2))    
          {
            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
            {
              if ($counter == $page + 1)
                $pagination.= "<li class='active'><a>$counter</a></li>";
              else
                $pagination.= "<li><a href='javascript:void(0)' onclick='".$function_name."(".$arugment_var.($counter-1).")'>$counter</a></li>";
            }
            $pagination.= "<li class=''>...</li>";
            $pagination.= "<li><a href='javascript:void(0)' onclick='".$function_name."(".$arugment_var.($lpm1-1).")'>$lpm1</a></li>";
            $pagination.= "<li><a href='javascript:void(0)' onclick='".$function_name."(".$arugment_var.($lastpage- 1).")'>$lastpage</a></li>";   
          }
          elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
          {
            $pagination.= "<li><a href='javascript:void(0)' onclick='".$function_name."(".$arugment_var.(1).")'>1</a></li>";
            $pagination.= "<li><a href='javascript:void(0)' onclick='".$function_name."(".$arugment_var.(2).")'>2</a></li>";
            $pagination.= "<li class=''>...</li>";
            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
            {
              if ($counter == $page + 1)
                $pagination.= "<li class='active'><a>$counter</a></li>";
              else
                $pagination.= "<li><a href='javascript:void(0)' onclick='".$function_name."(".$arugment_var.($counter-1).")'>$counter</a></li>";          
            }
            $pagination.= "<li class='dot'>..</li>";
            $pagination.= "<li><a href='javascript:void(0)' onclick='".$function_name."(".$arugment_var.($lpm1 - 1).")'>$lpm1</a></li>";
            $pagination.= "<li><a href='javascript:void(0)' onclick='".$function_name."(".$arugment_var.($lastpage - 1).")'>$lastpage</a></li>";    
          }
          else
          {
            $pagination.= "<li><a href='javascript:void(0)' onclick='".$function_name."(".$arugment_var.(0).")'>1</a></li>";
            $pagination.= "<li><a href='javascript:void(0)' onclick='".$function_name."(".$arugment_var.(1).")'>2</a></li>";
            $pagination.= "<li class=''>....</li>";
            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
            {
              if ($counter == $page + 1)
                $pagination.= "<li  class='active'><a>$counter</a></li>";
              else
                $pagination.= "<li><a href='javascript:void(0)' onclick='".$function_name."(".$arugment_var.($counter-1).")'>$counter</a></li>";          
            }
          }
        }
        if ($page < $counter - 2)
        {
          $pagination.= "<li><a href='javascript:void(0)' onclick='".$function_name."(".$arugment_var.($next).")'>Next →</a></li>";
          //$pagination.= "<li><a onclick='".$function_name."(".$arugment_var.($lastpage - 1).")'>Last</a></li>";
        }else
        {
          $pagination.= "<li class='active'><a>Next →</a></li>";
          //$pagination.= "<li class='active'><a>Last</a></li>";
        }
        $pagination.= "</ul>\n";
      }
      return $pagination;
    }
  }
}
function getUserSpecialities($general_id) {
	$ci = & get_instance();
	$specialities = $ci->db->select("*")
	->from("speciality")
	->join("speciality_detail","speciality_detail.speciality_id = speciality.id")
	->where("general_reg_info_id",$general_id)
	->get()
	->result();
	return $specialities;
}

function getProfileImage($details) {
	$user_profile_image =  '';
	if ($details['photo_logo'] == null) {
		$user_profile_image = '<img class="img-responsive" src="'.base_url().'assets/img/default.jpg" alt="Profile Picture">';
	}
	elseif ($details['linkedin_image_status'] == 0) {
		$user_profile_image = '<img class="img-responsive" src="'.base_url().'uploads/userimage/'.$details['photo_logo'].'" alt="Profile Picture">';
	}
	elseif ($details['linkedin_image_status'] == 1) {
		$user_profile_image = '<img class="img-responsive" src="'.$details['linkedin_image'].'" alt="Profile Picture">';
	}
	return $user_profile_image;
}
function createPagination($link,$total_rows,$limit,$uri_segment) {
	$ci = & get_instance();
	$config['total_rows'] = $total_rows;
	$config['per_page'] = $limit;
	$config['num_links'] = 2;

	$config['uri_segment'] = $uri_segment; /* segment of your uri which contains the page number */
	$config['base_url'] = $link;
	$config['full_tag_open'] = '<ul class="pagination">';
	$config['full_tag_close'] = '</ul>';

	$config['next_link'] = 'Next →';
	$config['next_tag_open'] = '<li>';
	$config['next_tag_close'] = '</li>';

	$config['prev_link'] = '← Previous';
	$config['prev_tag_open'] = '<li>';
	$config['prev_tag_close'] = '</li>';

	$config['cur_tag_open'] = '<li class="active"><a href="javascript:;">';
	$config['cur_tag_close'] = '</a></li>';

	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';

	$config['first_link'] = false;
	$config['last_link'] = false;

	$ci->pagination->initialize($config);
	return $ci->pagination->create_links();
}