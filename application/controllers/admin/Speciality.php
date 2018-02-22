<?php
class Speciality extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('speciality_model');
        $this->load->model('language_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }


    public function index()
    {
        if ($this->session->userdata('tab_index') != 'category' && $this->session->userdata('tab_index') != 'sub_category' && $this->session->userdata('tab_index') != 'speciality')
            $this->session->set_userdata('tab_index', 'category');
        $main_limit = 10;
        $main_offset = 0;
        $searchByMainCategory = null;
        $sub_limit = 10;
        $sub_offset = 0;
        $searchBySubCategory = null;
        $speciality_limit = 10;
        $speciality_offset = 0;

        $searchBySpeciality = null;

        if ($this->input->post()) {
            if ($this->input->post('searchByMainCategory')) {
                $searchByMainCategory = $this->input->post('searchByMainCategory');
                $this->session->set_userdata('searchByMainCategory', $searchByMainCategory);
            } else
                $this->session->unset_userdata('searchByMainCategory');

            if ($this->input->post('searchBySubCategory')) {
                $searchBySubCategory = $this->input->post('searchBySubCategory');
                $this->session->set_userdata('searchBySubCategory', $searchBySubCategory);
            } else
                $this->session->unset_userdata('searchBySubCategory');

            if ($this->input->post('searchBySpeciality')) {
                $searchBySpeciality = $this->input->post('searchBySpeciality');
                $this->session->set_userdata('searchBySpeciality', $searchBySpeciality);
            } else
                $this->session->unset_userdata('searchBySpeciality');
            if ($this->session->userdata('searchByMainCategory') != '')
                $searchByMainCategory = $this->session->userdata('searchByMainCategory');
            if ($this->session->userdata('searchBySubCategory') != '')
                $searchBySubCategory = $this->session->userdata('searchBySubCategory');
            if ($this->session->userdata('searchBySpeciality') != '')
                $searchBySpeciality = $this->session->userdata('searchBySpeciality');
        } else {
            if ($this->session->userdata('searchByMainCategory') != '')
                $searchByMainCategory = $this->session->userdata('searchByMainCategory');
            if ($this->session->userdata('searchBySubCategory') != '')
                $searchBySubCategory = $this->session->userdata('searchBySubCategory');
            if ($this->session->userdata('searchBySpeciality') != '')
                $searchBySpeciality = $this->session->userdata('searchBySpeciality');
        }


        if($this->input->post('main_per_page'))
        {
            $main_per_page = $this->input->post('main_per_page');
            $this->session->set_userdata('main_per_page',$main_per_page);
            $main_limit = $main_per_page;

        }
        else
            $this->session->unset_userdata('main_per_page');


        if($this->input->post('sub_per_page'))
        {
            $sub_per_page = $this->input->post('sub_per_page');
            $this->session->set_userdata('sub_per_page',$sub_per_page);
            $sub_limit = $sub_per_page;

        }
        else
            $this->session->unset_userdata('sub_per_page');


        if($this->input->post('speciality_per_page'))
        {
            $speciality_per_page = $this->input->post('speciality_per_page');
            $this->session->set_userdata('speciality_per_page',$speciality_per_page);
            $speciality_limit= $speciality_per_page;

        }
        else
            $this->session->unset_userdata('speciality_per_page');


        if ($searchByMainCategory) {
            $data['main_specialities'] = $this->speciality_model->get_main_specialities(null, null, $searchByMainCategory)->result_array();
        }
        else {
            $data['main_specialities'] = $this->speciality_model->get_main_specialities($main_limit, $main_offset)->result_array();
        }

        if ($searchBySubCategory){
           $data['sub_categories'] = $this->speciality_model->get_all_subcategories(null, null, $searchBySubCategory);
        }
        else{
            $data['sub_categories'] = $this->speciality_model->get_all_subcategories($sub_limit, $sub_offset);
        }

        if ($searchBySpeciality){
            $specialities = $this->speciality_model->get_all_specialities(null, null, $searchBySpeciality);
        }
        else{
            $specialities = $this->speciality_model->get_all_specialities($speciality_limit, $speciality_offset);
        }
        $data['specialities'] = $specialities;


        if ($searchByMainCategory){
            $data['main'] = $this->speciality_model->get_main_specialities($searchByMainCategory)->result_array();
        }
        else{
            $data['main'] = $this->speciality_model->get_main_specialities(null, null, null)->result_array();
        }

        if ($searchBySubCategory){
            $data['sub'] = $this->speciality_model->get_all_subcategories(null,null,$searchBySubCategory);
        }
        else{
            $data['sub'] = $this->speciality_model->get_all_subcategories(null, null, null);
        }

        if ($searchBySpeciality)
            $data['speciality'] = $this->speciality_model->get_all_specialities(null,null,$searchBySpeciality);
        else
            $data['speciality'] = $this->speciality_model->get_all_specialities(null, null, null);




        $data['total_main_speciality'] = count($data['main']);
        $data['total_sub_speciality'] = count($data['sub']);
        $data['total_speciality'] = count($data['speciality']);


        $data['main_limit'] = $main_limit;
        $data['main_offset'] = $main_offset;

        $data['sub_limit'] = $sub_limit;
        $data['sub_offset'] = $sub_offset;

        $data['speciality_limit'] = $speciality_limit;
        $data['speciality_offset'] = $speciality_offset;


        $data['page'] = 'member/speciality/index';
        $data['words'] = $this->_language;
        $data['header'] = 'Specialities';
        $data['searchByMainCategory'] = $searchByMainCategory;
        $data['searchBySubCategory'] = $searchBySubCategory;
        $data['searchBySpeciality'] = $searchBySpeciality;
        $data['photo_logo'] = $this->session->userdata('photo');
        $id = $this->session->userdata('general_id');
        $data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array(); //general info id
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $this->load->view($this->_container, $data);
    }

    public function main_category_page($id = null)
    {
        $data['page'] = 'member/speciality/main_category';
        $data['words'] = $this->_language;
        $data['header'] = 'Insert Main Category';
        $data['photo_logo'] = $this->session->userdata('photo');
        $data['main_category'] = $this->speciality_model->get_speciality($id)->row_array();
        $id = $this->session->userdata('general_id');
        $data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array(); //general info id
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $this->load->view($this->_container, $data);
    }

    public function sub_category_page($id = null)
    {
        $data['page'] = 'member/speciality/sub_category';
        $data['words'] = $this->_language;
        $data['header'] = 'Insert Sub Category';
        $data['main_categories'] = $this->speciality_model->get_main_specialities()->result_array();
        $data['photo_logo'] = $this->session->userdata('photo');
        $data['sub_category'] = $this->speciality_model->get_speciality($id)->row_array();
        $id = $this->session->userdata('general_id');
        $data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array(); //general info id
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $this->load->view($this->_container, $data);
    }

    public function speciality_category_page($id = null)
    {
        $data['page'] = 'member/speciality/speciality';
        $data['words'] = $this->_language;
        $data['header'] = 'Insert Speciality';
        $data['sub_categories'] = $this->speciality_model->get_sub_category();
        $data['speciality'] = $this->speciality_model->get_speciality($id)->row_array();
        $data['photo_logo'] = $this->session->userdata('photo');
        $id = $this->session->userdata('general_id');
        $data['userdata'] = $data['userdata'] = $this->user_model->get_user_info($id)->row_array(); //general info id
        $data['language'] = $this->language_model->select_specific_language()->result_array();
        $this->load->view($this->_container, $data);
    }

    public function save_main_category()
    {
        $data = $this->_get_posted_data();
        $data['is_publish'] = 1;
        $data['display_order'] = 1;
        $data['is_deleted'] = 0;
        $this->form_validation->set_rules('category_name', 'Category Name', 'required');
        $this->form_validation->set_rules('parent_id', 'Category Name', 'required');
        $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
        if ($this->form_validation->run() == FALSE) {
            if ($data['id'] != '')
                $this->main_category_page();
            else
                $this->main_category_page($data['id']);
        } else {
            if ($data['id'] != '') {
                $this->speciality_model->update('speciality', $data, array('id' => $data['id']));
                $flashdata = array("type" => "success", "message" => $this->_language['DTL_0324']);
            } else {
                $this->speciality_model->insert('speciality', $data);
                $flashdata = array("type" => "success", "message" => $this->_language['DTL_0325']);
            }
            $this->session->set_flashdata('flash_message', $flashdata);
            redirect(site_url('admin/speciality'));
        }

    }

    public function save_speciality()
    {
        $data = $this->_get_posted_data();
        $data['is_publish'] = 1;
        $data['display_order'] = 1;
        $data['is_deleted'] = 0;
        $this->form_validation->set_rules('category_name', 'Category Name', 'required');
        $this->form_validation->set_rules('parent_id', 'Category Name', 'required');
        $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
        if ($this->form_validation->run() == FALSE) {
            if ($data['id'] != '')
                $this->speciality_category_page();
            else
                $this->speciality_category_page($data['id']);
        } else {
            if ($data['id'] != '') {
                $this->speciality_model->update('speciality', $data, array('id' => $data['id']));
                $flashdata = array("type" => "success", "message" => $this->_language['DTL_0324']);
            } else {
                $this->speciality_model->insert('speciality', $data);
                $flashdata = array("type" => "success", "message" => $this->_language['DTL_0325']);
            }
            $this->session->set_flashdata('flash_message', $flashdata);
            redirect(site_url('admin/speciality'));
        }

    }

    public function save_sub_category()
    {
        $data = $this->_get_posted_data();
        $data['is_publish'] = 1;
        $data['display_order'] = 1;
        $data['is_deleted'] = 0;
        $this->form_validation->set_rules('category_name', 'Category Name', 'required');
        $this->form_validation->set_rules('parent_id', 'Category Name', 'required');
        $this->form_validation->set_message('required', '%s ' . $this->_language['DTL_0301']);
        if ($this->form_validation->run() == FALSE) {
            if ($data['id'] != '')
                $this->sub_category_page();
            else
                $this->sub_category_page($data['id']);
        } else {
            if ($data['id'] != '') {
                $this->speciality_model->update('speciality', $data, array('id' => $data['id']));
                $flashdata = array("type" => "success", "message" => $this->_language['DTL_0324']);
            } else {
                $this->speciality_model->insert('speciality', $data);
                $flashdata = array("type" => "success", "message" => $this->_language['DTL_0325']);
            }
            $this->session->set_flashdata('flash_message', $flashdata);
            redirect(site_url('admin/speciality'));
        }

    }

    public function redirect_back()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: http://' . $_SERVER['SERVER_NAME']);
        }
    }

    private function _get_posted_data()
    {
        $data['id'] = $this->input->post('id');
        $data['category_name'] = $this->input->post('category_name');
        $data['parent_id'] = $this->input->post('parent_id');
        return $data;
    }

    public function delete_main_category($id)
    {
        $sub_categories = $this->speciality_model->get_by_parent_id($id)->result_array();
        $count = 0;
        foreach ($sub_categories as $sub_category) {
            $specialities = null;
            $specialities = $this->speciality_model->get_by_parent_id($sub_category['id'])->result_array();
            foreach ($specialities as $speciality) {
                $this->speciality_model->delete('speciality', array('id' => $speciality['id']));
            }
            $this->speciality_model->delete('speciality', array('id' => $sub_category['id']));
        }
        $this->speciality_model->delete('speciality', array('id' => $id));
        redirect(site_url('admin/speciality'));
    }

    public function delete_sub_category($id)
    {
        $specialities = $this->speciality_model->get_by_parent_id($id)->result_array();
        foreach ($specialities as $speciality) {
            $this->speciality_model->delete('speciality', array('id' => $speciality['id']));
        }
        $this->speciality_model->delete('speciality', array('id' => $id));
        redirect(site_url('amdin/speciality'));
    }

    public function delete_speciality($id)
    {
        $this->speciality_model->delete('speciality', array('id' => $id));
        redirect(site_url('admin/speciality'));
    }


    public function get_main_pagination()
    {
        $main_limit = $this->input->post('main_limit');
        $main_offset = $this->input->post('main_offset');
        $searchByMainCategory = null;
        if ($this->input->post()) {
            if ($this->input->post('main_limit')) {
                $main_limit = $this->input->post('main_limit');
                $this->session->set_userdata('main_limit', $main_limit);
            } else
                $this->session->unset_userdata('main_limit');
            if ($this->input->post('main_offset')) {
                $main_offset = $this->input->post('main_offset');
                $this->session->set_userdata('main_offset', $main_offset);
            } else
                $this->session->unset_userdata('main_offset');
            if ($this->input->post('searchByMainCategory')) {
                $searchByMainCategory = $this->input->post('searchByMainCategory');
                $this->session->set_userdata('searchByMainCategory', $searchByMainCategory);
            } else
                $this->session->unset_userdata('searchByMainCategory');
            if ($this->session->userdata('main_limit') != '')
                $main_limit = $this->session->userdata('main_limit');
            if ($this->session->userdata('main_offset') != '')
                $main_offset = $this->session->userdata('main_offset');
            if ($this->session->userdata('searchByMainCategory') != '')
                $searchByMainCategory = $this->session->userdata('searchByMainCategory');
        } else {
            if ($this->session->userdata('main_limit') != '')
                $main_limit = $this->session->userdata('main_limit');
            if ($this->session->userdata('main_offset') != '')
                $main_offset = $this->session->userdata('main_offset');
            if ($this->session->userdata('searchByMainCategory') != '')
                $searchByMainCategory = $this->session->userdata('searchByMainCategory');
        }

        if ($searchByMainCategory)
            $main_specialities = $this->speciality_model->get_main_specialities($main_limit, $main_offset, $searchByMainCategory)->result_array();
        else
            $main_specialities = $this->speciality_model->get_main_specialities($main_limit, $main_offset)->result_array();

        if ($searchByMainCategory)
            $total_main_category = count($this->speciality_model->get_main_specialities(null, null, $searchByMainCategory)->result_array());
        else
            $total_main_category = count($this->speciality_model->get_main_specialities()->result_array());
        $words = $this->_language;

        $count = ($main_offset * $main_limit) + 1;
        if (count($main_specialities) > 0) {
            foreach ($main_specialities as $main_speciality) {
                echo '<tr>';
                echo '<td>' . $count . '</td>';
                echo '<td>' . $main_speciality['category_name'] . '</td>';
                echo '<td class="double_icons">';
                echo '<a href="' . site_url('admin/speciality/main_category_page') . '/' . $main_speciality['id'] . '"><button class="btn btn-xs btn-secondary"><i class="fa fa-pencil"></i></button></a>';

                echo '<a href="' . site_url('admin/speciality/delete_main_category') . '/' . $main_speciality['id'] . '" onclick="return confirm(' . $words['DTL_0322'] . ');"><button class="btn btn-xs btn-primary"><i class="fa fa-trash-o"></i></button></a>';
                echo '</td>';
                echo '</tr>';
                $count++;
            }

            echo "<tr><td colspan='3'>" . create_ajax_paging("get_main_pagination", $total_main_category, $main_offset, array($main_limit), $main_limit) . "</td></tr>";
        } else
            echo 'No more results.';

    }

    public function get_sub_pagination()
    {
        $sub_limit = $this->input->post('sub_limit');
        $sub_offset = $this->input->post('sub_offset');
        $searchBySubCategory = null;
        if ($this->input->post()) {
            if ($this->input->post('sub_limit')) {
                $sub_limit = $this->input->post('sub_limit');
                $this->session->set_userdata('sub_limit', $sub_limit);
            } else
                $this->session->unset_userdata('sub_limit');
            if ($this->input->post('sub_offset')) {
                $sub_offset = $this->input->post('sub_offset');
                $this->session->set_userdata('sub_offset', $sub_offset);
            } else
                $this->session->unset_userdata('sub_offset');
            if ($this->input->post('searchBySubCategory')) {
                $searchBySubCategory = $this->input->post('searchBySubCategory');
                $this->session->set_userdata('searchBySubCategory', $searchBySubCategory);
            } else
                $this->session->unset_userdata('searchBySubCategory');
            if ($this->session->userdata('sub_limit') != '')
                $sub_limit = $this->session->userdata('sub_limit');
            if ($this->session->userdata('sub_offset') != '')
                $sub_offset = $this->session->userdata('sub_offset');
            if ($this->session->userdata('searchBySubCategory') != '')
                $searchBySubCategory = $this->session->userdata('searchBySubCategory');
        } else {
            if ($this->session->userdata('sub_limit') != '')
                $sub_limit = $this->session->userdata('sub_limit');
            if ($this->session->userdata('sub_offset') != '')
                $sub_offset = $this->session->userdata('sub_offset');
            if ($this->session->userdata('searchBySubCategory') != '')
                $searchBySubCategory = $this->session->userdata('searchBySubCategory');
        }

        if ($searchBySubCategory)
            $sub_categories = $this->speciality_model->get_all_subcategories($sub_limit, $sub_offset, $searchBySubCategory);
        else
            $sub_categories = $this->speciality_model->get_all_subcategories($sub_limit, $sub_offset);


        if ($searchBySubCategory)
            $total_sub_category = count($this->speciality_model->get_all_subcategories(null, null, $searchBySubCategory));
        else
            $total_sub_category = count($this->speciality_model->get_all_subcategories(null, null));

        $data['total_sub_category'] = $total_sub_category;


        $count = ($sub_offset * $sub_limit) + 1;
        if (count($sub_categories) > 0) {
            foreach ($sub_categories as $sub_category) {
                echo '<tr>';
                echo '<td>' . $count . '</td>';
                echo '<td>' . $sub_category['parent_category_name'] . '</td>';
                echo '<td>' . $sub_category['category_name'] . '</td>';
                echo '<td class="double_icons">';
                echo '<a href="' . site_url('admin/speciality/sub_category_page') . '/' . $sub_category['id'] . '"><button class="btn btn-xs btn-secondary"><i class="fa fa-pencil"></i></button></a>';

                echo '<a href="' . site_url('admin/speciality/delete_sub_category') . '/' . $sub_category['id'] . '" onclick="return confirm(' . $this->_language['DTL_0322'] . ');"><button class="btn btn-xs btn-primary"><i class="fa fa-trash-o"></i></button></a>';
                echo '</td>';
                echo '</tr>';
                $count++;
            }
            echo "<tr><td colspan='4'>" . create_ajax_paging("get_sub_pagination", $total_sub_category, $sub_offset, array($sub_limit), $sub_limit) . "</td></tr>";
        } else
            echo 'No more results.';

    }

    public function get_speciality_pagination()
    {
        $speciality_limit = $this->input->post('speciality_limit');
        $speciality_offset = $this->input->post('speciality_offset');
        $searchBySpeciality = null;
        if ($this->input->post()) {
            if ($this->input->post('speciality_limit')) {
                $speciality_limit = $this->input->post('speciality_limit');
                $this->session->set_userdata('speciality_limit', $speciality_limit);
            } else
                $this->session->unset_userdata('speciality_limit');
            if ($this->input->post('speciality_offset')) {
                $speciality_offset = $this->input->post('speciality_offset');
                $this->session->set_userdata('speciality_offset', $speciality_offset);
            } else
                $this->session->unset_userdata('speciality_offset');
            if ($this->input->post('searchBySpeciality')) {
                $searchBySpeciality = $this->input->post('searchBySpeciality');
                $this->session->set_userdata('searchBySpeciality', $searchBySpeciality);
            } else
                $this->session->unset_userdata('searchBySpeciality');
            if ($this->session->userdata('speciality_limit') != '')
                $speciality_limit = $this->session->userdata('speciality_limit');
            if ($this->session->userdata('speciality_offset') != '')
                $speciality_offset = $this->session->userdata('speciality_offset');
            if ($this->session->userdata('searchBySpeciality') != '')
                $searchBySpeciality = $this->session->userdata('searchBySpeciality');
        } else {
            if ($this->session->userdata('speciality_limit') != '')
                $speciality_limit = $this->session->userdata('speciality_limit');
            if ($this->session->userdata('speciality_offset') != '')
                $speciality_offset = $this->session->userdata('speciality_offset');
            if ($this->session->userdata('searchBySpeciality') != '')
                $searchBySpeciality = $this->session->userdata('searchBySpeciality');
        }

        if ($searchBySpeciality)
            $specialities = $this->speciality_model->get_all_specialities($speciality_limit, $speciality_offset, $searchBySpeciality);
        else
            $specialities = $this->speciality_model->get_all_specialities($speciality_limit, $speciality_offset);
        if ($searchBySpeciality)
            $data['total_speciality'] = count($this->speciality_model->get_all_specialities(null, null, $searchBySpeciality));
        else
            $data['total_speciality'] = count($this->speciality_model->get_all_specialities());

        $total_speciality = $data['total_speciality'];

        $count = ($speciality_offset * $speciality_limit) + 1;
        if (count($specialities) > 0) {
            foreach ($specialities as $speciality) {

                echo '<tr>';
                echo '<td>' . $count . '</td>';
                echo '<td>' . $speciality['parent_category_name'] . '</td>';
                echo '<td>' . $speciality['category_name'] . '</td>';
                echo '<td class="double_icons">';
                echo '<a href="' . site_url('admin/speciality/speciality_category_page') . '/' . $speciality['id'] . '"><button class="btn btn-xs btn-secondary"><i class="fa fa-pencil"></i></button></a>';

                echo '<a href="' . site_url('admin/speciality/delete_speciality') . '/' . $speciality['id'] . '" onclick="return confirm(' . $this->_language['DTL_0322'] . ');"><button class="btn btn-xs btn-primary"><i class="fa fa-trash-o"></i></button></a>';
                echo '</td>';
                echo '</tr>';
                $count++;
            }
            echo "<tr><td colspan='4'>" . create_ajax_paging("get_speciality_pagination", $total_speciality, $speciality_offset, array($speciality_limit), $speciality_limit) . "</td></tr>";
        } else
            echo 'No more results.';

    }

}