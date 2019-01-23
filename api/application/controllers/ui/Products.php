<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/Base.php");


class Products extends Base
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Jalali_date');
    }

    public function index()
    {
        $data              = array("active_menu" => "m-manage-products", "title" => "خدمات");
        $data['resellers'] = $this->db->select("CONCAT(reseller_firstname ,' ',reseller_lastname) as name,id")
                                      ->from("resellers")
                                      ->get()
                                      ->result_array();
        $data["units"]     = $this->db->get("units")->result_array();
        $data["main_cats"] = $this->db->get_where("category", array("parent" => 0))->result_array();
        // var_dump($data);
        // exit;s
        $this->view('products/index', $data);
    }

    public function products_list()
    {
        require APPPATH . "third_party/datatable-ssp/ssp.class.php";

        $table = 'view_products_list';

        // Table's primary key
        $primaryKey = 'id';

        $columns = array(
            array('db' => 'id', 'dt' => 'id'),
            array('db' => 'title', 'dt' => 'title'),
            array('db' => 'description', 'dt' => 'description'),
            array('db' => 'main_cat', 'dt' => 'main_cat'),
            array('db' => 'reseller_firstname', 'dt' => 'reseller_firstname'),
            array('db' => 'reseller_lastname', 'dt' => 'reseller_lastname'),
            array('db' => 'reseller_id', 'dt' => 'reseller_id'),
            array('db' => 'main_cat_id', 'dt' => 'main_cat_id'),
            array('db' => 'sub_cat_id', 'dt' => 'sub_cat_id'),
            // array('db' => 'certification_no', 'dt' => 'certification_no'),
            // array('db' => 'certification_image', 'dt' => 'certification_image')
        );

        echo json_encode(SSP::simple($_GET, $this->build_sql_details(), $table, $primaryKey, $columns));
    }

    public function manage_images()
    {
        $id                  = $this->input->get("id");
        $data["product_id"]  = $id;
        $data["title"]       = "";
        $data["active_menu"] = "m-manage-products";
        $data["images"]      = $this->db->get_where("product_images", array("product_id" => $id))->result_array();

        foreach ($data["images"] as &$image) {
            $image['image_absolute_path'] = base_url(PRODUCT_IMAGE_PATH . "/" . $image["image"]);
        }

        $this->view("products/manage_images", $data);
    }

    public function add_product_image()
    {
        $product_id              = $this->input->post("product_id");
        $config['upload_path']   = FCPATH . PRODUCT_IMAGE_PATH;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = 0;
        // $config['max_width']     = 1024;
        // $config['max_height']    = 768;
        $config['file_name'] = time();

        $this->load->library('upload', $config);

        if ($this->upload->do_upload("product_file") == false) {
            $this->session->set_flashdata("error", $this->upload->display_errors());
            redirect("ui/products/manage_images?id=" . $product_id);
        }

        $file_name = $this->upload->data("file_name");
        $this->db->insert("product_images", array("product_id" => $product_id, "image" => $file_name));

        $this->session->set_flashdata("msg", "عملیات با موفقیت انجام شد.");
        redirect("ui/products/manage_images?id=" . $product_id);
    }

    public function add_update_product()
    {
        $this->load->helper(array('form', 'url', 'file'));
        $this->load->library('form_validation');
        // $config['upload_path']   = FCPATH . BET_IMAGE_PATH;
        // $config['allowed_types'] = 'gif|jpg|png';
        // $config['max_size']      = 2000;
        // $config['max_width']     = 1024;
        // $config['max_height']    = 768;
        // $this->load->library('upload', $config);

        $err = array("required" => "مقداری برای %s وارد نشده است");
        $this->form_validation->set_rules('title', 'عنوان', 'required', $err);
        $this->form_validation->set_rules('description', 'توضیحات', 'required', $err);
        $this->form_validation->set_rules('main_cat', 'دسته اصلی', 'required', $err);
        $this->form_validation->set_rules('sub_cat', 'زیر دسته', 'required', $err);
        // $this->form_validation->set_rules('unit', 'واحد', 'required', $err);

        $data["main_cats"] = $this->db->get_where("category", array("parent" => 0))->result_array();
        $data["units"]     = $this->db->get("units")->result_array();
        if ($this->input->get_post('action') == 'new') {
            return $this->partial_view("products/_add_update_product", $data);
        }

        if ($this->form_validation->run() == FALSE) {
            $this->partial_view('products/_add_update_product', $data);
        } else {
            // if ($this->upload->do_upload("image_to_show_a") == false) {
            //     echo $this->upload->display_errors();
            // }
            // $image_to_show_a = $this->upload->data("file_name");
            //
            // if ($this->upload->do_upload("image_to_show_b") == false) {
            //     echo $this->upload->display_errors();
            // }
            // $image_to_show_b = $this->upload->data("file_name");
            //
            // $_POST['image_to_show_a'] = $image_to_show_a;
            // $_POST['image_to_show_b'] = $image_to_show_b;
            $to_save = array(
                "title"       => $_POST['title'],
                "description" => $_POST['description'],
                "main_cat_id" => $_POST['main_cat'],
                "sub_cat_id"  => $_POST['sub_cat'],
                // "unit_id"     => $_POST['unit']
            );
            // var_dump($_POST);

            $this->db->trans_start();
            $res        = $this->db->insert("products", $to_save);
            $product_id = $this->db->insert_id();
            $this->db->trans_complete();

            $this->partial_view('products/_add_update_product', array(
                "success_submit" => true,
                "msg"            => "محصول با موفقیت ثبت شد"
            ));
        }

    }


    public function delete_product()
    {
        $id = $this->input->post('id');
        log_message("debug", $id);

        $this->db->trans_start();
        $product_images = $this->db->get_where("product_images", array("product_id" => $id))->result_array();
        log_message("debug", print_r($product_images, true));
        $this->db->where("id", $id)->delete("products");
        // $this->db->where("product_id", $id)->delete("product_unit");
        $this->db->where("product_id", $id)->delete("product_images");
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return ejson(array("success" => false, "error" => $this->db->display_error()));
        }

        //delete  files
        foreach ($product_images as $pi) {
            $file_path = FCPATH . PRODUCT_IMAGE_PATH . "/" . $pi["image"];
            log_message("debug", $file_path);
            @unlink($file_path);
        }

        return ejson(true, "");
    }


    public function delete_product_image()
    {
        $image_id   = $this->input->post("image_id");
        $product_id = $this->input->post("product_id");
        $image      = $this->db->get_where("product_images", array("id" => $image_id))->row(0, "array");
        if ($image) {
            $this->db->where("id", $image_id)->delete("product_images");
            @unlink(FCPATH . PRODUCT_IMAGE_PATH . "/" . $image['image']);
        }
        redirect("ui/products/manage_images?id=" . $product_id);
    }

    public function change_reseller()
    {
        $product_id  = $this->input->post("product_id");
        $reseller_id = $this->input->post("reseller_id");
        $res         = $this->db->set("reseller_id", $reseller_id)
                                ->where("id", $product_id)
                                ->update("products");
        if ($res) {
            return ejson(true);
        }

        return ejson(false, "Error saving reseller.");
    }

    public function change_unit()
    {
        $product_id = $this->input->post("product_id");
        $unit_id    = $this->input->post("unit_id");
        $res        = $this->db->set("unit_id", $unit_id)
                               ->where("id", $product_id)
                               ->update("products");
        if ($res) {
            return ejson(true);
        }

        return ejson(false, "Error saving reseller.");
    }

    public function change_description()
    {
        $product_id  = $this->input->post("product_id");
        $description = $this->input->post("description");

        $res = $this->db->set('description', $description)
                        ->where('id', $product_id)
                        ->update("products");
        if ($res) {
            return ejson(true);
        }

        return ejson(false, "Error saving description.");
    }

    public function change_category()
    {
        $product_id = $this->input->post("product_id");
        $sub_cat_id = $this->input->post("sub_cat_id");

        // var_dump($_POST);
        $main_cat = $this->db->get_where("category", array("id" => $sub_cat_id))->row(0, "array");
        if ($main_cat && $product_id) {
            // var_dump($main_cat);
            $res = $this->db->set('main_cat_id', $main_cat['parent'])
                            ->set('sub_cat_id', $sub_cat_id)
                            ->where('id', $product_id)
                            ->update('products');
            if ($res) {
                return ejson(true);
            }
        }

        // $res = $this->db->set('description', $description)
        //                 ->where('id', $product_id)
        //                 ->update("products");
        // if () {
        //     return ejson(true);
        // }

        return ejson(false, "Error saving category.");
    }

    public function change_price()
    {
        $price_per_unit = $this->input->post("price_per_unit");
        $product_id     = $this->input->post("product_id");
        if ($price_per_unit && $product_id) {
            $res = $this->db->set("price_per_unit", $price_per_unit)
                            ->where("id", $product_id)
                            ->update("products");
            if ($res) {
                return ejson(true);
            }
        }

        return ejson(false, "Error saving price_per_unit.");
    }


    public function test_faktor()
    {
        $this->load->library("Faktor");

        echo $this->load->view("partial_layout/header", '', true);
        echo $this->faktor->set_factor_number(1)
                     ->set_factor_date("1393/11/11")
                     ->set_customer_name('moh mansouri')
                     ->set_customer_id(12)
                     ->set_description('')
                     ->set_payable(4400)
                     ->add_order_item(array("desc" => "item description", "num" => 1, "price" => 1200, "total_price" => 1200))
                     ->add_order_item(array("desc" => "item description", "num" => 1, "price" => 1200, "total_price" => 1200))
                     ->generate_ui();

        // echo $res->generate_ui();
        echo $this->load->view("partial_layout/footer", '', true);

    }
}
