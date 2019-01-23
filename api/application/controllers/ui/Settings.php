<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/Base.php");

class Settings extends Base
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Jalali_date');
    }

    public function index()
    {
        $data['active_menu'] = 'm-settings';
        $data['title']       = 'تنظیمات';


        $data['sliders'] = $this->db->get("sliders")->result_array();

        foreach ($data["sliders"] as &$slider) {
            $slider['pic_absolute_path'] = base_url(SLIDER_IMAGE_PATH . "/" . $slider['slider_image']);
        }

        $wallet_discount         = $this->db->get_where("app_settings", array("name" => "wallet_discount"))
                                            ->row(0, "array");
        $data["wallet_discount"] = isset($wallet_discount['value']) ? $wallet_discount['value'] : 0;

        $this->view('settings/settings', $data);
    }


    public function change_picture()
    {
        $image     = $this->input->post("img");
        $slider_id = $this->input->post('slider_id');
        $old_image = $this->db->get_where("sliders", array("id" => $slider_id))->row(0, "array");
        $name      = "";

        //save image
        try {
            $name = base64_imagestring_save($image, FCPATH . SLIDER_IMAGE_PATH, time());
        } catch (Exception $e) {
            @unlink(FCPATH . SLIDER_IMAGE_PATH . DIRECTORY_SEPARATOR . $name);
            return ejson(false, $e->getMessage());
        }

        //update new category image
        $res = $this->db->set("slider_image", $name)
                        ->where("id", $slider_id)
                        ->update("sliders");

        // delete old image
        @unlink(FCPATH . SLIDER_IMAGE_PATH . DIRECTORY_SEPARATOR . $old_image['pic']);

        return ejson(true);
    }

    public function new_slider()
    {
        $image = $this->input->post('img');

        $name = '';
        //save image
        try {
            $name = base64_imagestring_save($image, FCPATH . SLIDER_IMAGE_PATH, time());
        } catch (Exception $e) {
            @unlink(FCPATH . SLIDER_IMAGE_PATH . DIRECTORY_SEPARATOR . $name);
            return ejson(false, $e->getMessage());
        }

        $res = $this->db->insert("sliders", array("slider_image" => $name));

        return ejson(true);
    }

    public function delete_slider()
    {
        $slider_id = $this->input->post('slider_id');
        $slider    = $this->db->get_where("sliders", array("id" => $slider_id))->row(0, "array");
        if ($slider) {
            $res = $this->db->where('id', $slider_id)->delete('sliders');
            @unlink(FCPATH . SLIDER_IMAGE_PATH . DIRECTORY_SEPARATOR . $slider['slider_image']);
        }
        return ejson(true);
    }


    public function change_slider_description()
    {
        $slider_id   = $this->input->post("slider_id");
        $description = $this->input->post("description");

        $res = $this->db->set("description", $description)
                        ->where("id", $slider_id)
                        ->update("sliders");

        // if ($res) {
        //     $this->show_msg("عملیات با موفقیت انجام شد.");
        // }
        //
        // $this->show_err("خطا در انجام عملیات.");
        $this->msg("عملیات با موفقیت انجام شد.");
        redirect("ui/settings/index");
    }

    public function update_wallet_discount()
    {
        $wallet_discount = $this->input->post("wallet_discount");
        $this->db->set("value", $wallet_discount)
                 ->where("name", "wallet_discount")
                 ->update("app_settings");
        redirect("ui/settings/index");
    }

}
