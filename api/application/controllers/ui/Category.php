<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/Base.php");
require APPPATH . "/third_party/UUID.php";

class Category extends Base
{
    private $category_field_formats  = array("number", "text", "select", "checkbox", "image", "option");
    private $category_field_formats2 = array("number"   => 'عدد',
                                             "text"     => 'متن',
                                             "select"   => "dopdown",
                                             "checkbox" => "چک باکس",
                                             "image"    => 'تصویر',
                                             'option'   => 'گزینه');

    function __construct()
    {
        parent::__construct();
        $this->load->library('Jalali_date');
    }

    public function index()
    {
        $data["categories"]  = $this->db->get_where("category", array("parent" => 0))->result_array();
        $data["active_menu"] = "m-manage-categories";
        $data["title"]       = "دسته ها";
        foreach ($data["categories"] as &$cat) {
            $cat['pic_absolute_path'] = base_url(CATEGORY_IMAGE_PATH . "/" . $cat['pic']);
        }

        // pre($data);
        $this->view("categories/index", $data);
    }

    public function add_category()
    {
        if (empty($this->input->post("cat_name"))) {
            return ejson(false, "Empty category name.");
        }
        $cats = $this->db->get_where("category", array("cat_name" => $this->input->post("cat_name")))->result_array();
        if (count($cats) > 0) {
            return ejson(false, "Duplicate category name.");
        }


        $res = $this->db->insert('category', array("cat_name" => $this->input->post("cat_name")));
        if (!$res) {
            return ejson(false, "خطا در انجام عملیات.");
        }

        return ejson(true, "");
    }

    public function sub_cats()
    {
        $cat_name    = $this->input->get("cat_name");
        $parent      = $this->input->get("cat_id");
        $sub_cats    = $this->db->get_where("category", array("parent" => $parent))->result_array();
        $active_menu = "m-manage-categories";

        $this->view("categories/sub_categories", compact("sub_cats", "parent", "cat_name", "active_menu"));
    }

    public function subcategories()
    {
        $cat_id        = $this->input->get('cat_id');
        $build_options = $this->input->get('build_options');
        $sub_cats      = $this->db->get_where("category", array("parent" => $cat_id))->result_array();
        if ($build_options) {
            $res = array();
            foreach ($sub_cats as $sc) {
                $res[] = "<option value='{$sc['id']}'>{$sc['cat_name']}</option>";
            }
            return ejson(array("success" => true, "data" => $res));
        }

        return ejson(array("success" => true, "data" => $sub_cats));
    }

    public function add_sub_category()
    {
        if (empty($this->input->post("cat_name"))) {
            return ejson(false, "Empty category name.");
        }
        $cats = $this->db->get_where("category", array("cat_name" => $this->input->post("cat_name")))
                         ->result_array();
        if (count($cats) > 0) {
            return ejson(false, "Duplicate category name.");
        }

        $res = $this->db->insert('category', array("cat_name" => $this->input->post("cat_name"),
                                                   "parent"   => $this->input->post("parent")));
        if (!$res) {
            return ejson(false, "خطا در انجام عملیات.");
        }

        return ejson(true, "");
    }

    public function change_picture()
    {
        $image     = $this->input->post("img");
        $cat_id    = $this->input->post('cat_id');
        $old_image = $this->db->get_where("category", array("id" => $cat_id))->row(0, "array");
        $name      = "";

        //save image
        try {
            $name = base64_imagestring_save($image, FCPATH . CATEGORY_IMAGE_PATH, time());
        } catch (Exception $e) {
            @unlink(FCPATH . CATEGORY_IMAGE_PATH . DIRECTORY_SEPARATOR . $name);
            return ejson(false, $e->getMessage());
        }

        //update new category image
        $res = $this->db->set("pic", $name)
                        ->where("id", $cat_id)
                        ->update("category");

        // delete old image
        @unlink(FCPATH . CATEGORY_IMAGE_PATH . DIRECTORY_SEPARATOR . $old_image['pic']);

        return ejson(true);
    }

    public function delete_category()
    {
        isset($_POST['id']) || die("cat id not provided");

        $id       = $this->input->post("id");
        $main_cat = $this->db->get_where("category", array("id" => $id))
                             ->row(0, "array");
        $images   = array();
        $this->db->trans_start();
        if (!empty($main_cat)) {
            $images[] = FCPATH . CATEGORY_IMAGE_PATH . DIRECTORY_SEPARATOR . $main_cat['pic'];
            // delete main category
            $this->db->where("id", $id)->delete("category");
            // delete child categories
            foreach ($this->db->get_where("category", array("parent" => $id))->result_array() as $row) {
                $images[] = FCPATH . CATEGORY_IMAGE_PATH . DIRECTORY_SEPARATOR . $row['pic'];
                $this->db->where("id", $row['id'])->delete("category");
            }
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() == false) {
            ejson(false);
        }

        // delete images
        foreach ($images as $image) {
            @unlink($image);
        }
        ejson(true);
    }

    public function category_fields()
    {
        $data["cat_id"] = $this->input->get("cat_id", true);
        if (is_null($data["cat_id"])) die("invalid cat_id value");

        $data["category_fields"]        = $this->db
            ->get_where("category_fields", array("category_id" => $data['cat_id']))
            ->result_array();
        $data["category_field_formats"] = $this->category_field_formats;
        $this->view('categories/category_fields', $data);
    }

    public function add_category_fieldx()
    {
        $data = array(
            "category_id"  => $this->input->post("cat_id", true),
            "field_name"   => $this->input->post('field_name', true),
            "field_format" => $this->input->post('format', true)
        );
        $this->db->insert("category_fields", $data);
        return ejson(true);
    }

    public function add_category_field()
    {
        $data["cat_id"]   = $this->input->get('id');
        $category         = $this->db->get_where('category', array('id' => $data['cat_id']))->result_array();
        $data['category'] = array();
        if (count($category) > 0) {
            $data['category'] = $category[0];
        }
        $data['category_fields']        = $this->db->get_where('category_fields', array('category_id' => $data['cat_id']))->result_array();
        $data['category_field_formats'] = $this->build_dropdown();
        // pr($data);
        $this->view('categories/add_category_field', $data);
    }

    public function store_category_fields()
    {
        $cat_id       = $this->input->post('cat_id');
        $english_name = $this->input->post('english_name');
        $f            = $this->db->get_where("category_fields", array("field_name" => $english_name, "category_id" => $cat_id))->result_array();
        if (count($f) != 0) {
            $this->session->set_flashdata("msg", "نام لاتین تکراری هست");
            return redirect('ui/category/add_category_field?id=' . $cat_id);
        }
        $data                      = array("field_name" => $english_name, "category_id" => $cat_id);
        $data["field_label"]       = $this->input->post("label", true);
        $data["field_format"]      = $this->input->post("field_format", true);
        $data["default_value"]     = $this->input->post("default", true);
        $data["field_suggestions"] = $this->input->post("multi_field_values", true);
        $this->db->insert('category_fields', $data);
        return redirect('ui/category/category_fields?cat_id=' . $cat_id);
    }

    private function build_dropdown()
    {
        $ret = '';
        foreach ($this->category_field_formats2 as $k => $v) {
            $ret .= "<option value='" . $k . "'>" . $v . "</option>";
        }
        return $ret;
    }

}
