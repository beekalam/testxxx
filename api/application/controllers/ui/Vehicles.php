<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/Base.php");

class Vehicles extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('Jalali_date');
    }

    public function index()
    {
        $this->view('vehicles/index', array("active_menu" => "m-vehicles", "title" => "خودروها"));
    }

    public function vehicles_list()
    {
        require APPPATH . "third_party/datatable-ssp/ssp.class.php";

        $table = 'vehicles';

        // Table's primary key
        $primaryKey = 'id';

        $columns = array(
            array('db' => 'id', 'dt' => 'id'),
            array('db' => 'vehicle_brand', 'dt' => 'vehicle_brand'),
            array('db' => 'vehicle_model', 'dt' => 'vehicle_model')
        );

        echo json_encode(SSP::simple($_GET, $this->build_sql_details(), $table, $primaryKey, $columns));
    }

    public function add_vehicle()
    {
        if($this->is_get_request()){
           return $this->view('vehicles/add_vehicle', array("active_menu" => "m-vehicles", "title" => "خودروها"));
        }

        $vehicle_model = $this->input->post("vehicle_model");
        $vehicle_brand = $this->input->post("vehicle_brand");

        $pic = null;
        if (isset($_FILES['car_image'])) {
            // upload file
            $config['upload_path']   = FCPATH . CAR_IMAGE_PATH;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size']      = 0;
            $config['file_name']     = time();
            $this->load->library('upload', $config);
            $this->upload->do_upload("car_image");
            $pic = $this->upload->data("file_name");
        }

        if ($pic) {
            $res = $this->db->insert("vehicles", compact("vehicle_brand", "vehicle_model","pic"));
        }else{
            $res = $this->db->insert("vehicles", compact("vehicle_brand", "vehicle_model"));
        }
        if($res)
            $this->msg("success");

        redirect("ui/vehicles/index");
    }

    // public function add_unit()
    // {
    //     if (empty($this->input->post("unit_name"))) {
    //         return ejson(false, "Empty unit name.");
    //     }
    //     $cats = $this->db->get_where("units", array("unit_name" => $this->input->post("unit_name")))->result_array();
    //     if (count($cats) > 0) {
    //         return ejson(false, "Duplicate unit name.");
    //     }
    //
    //
    //     $res = $this->db->insert('units', array("unit_name" => $this->input->post("unit_name")));
    //     if (!$res) {
    //         return ejson(false, "خطا در انجام عملیات.");
    //     }
    //
    //     return ejson(true, "");
    // }

}
