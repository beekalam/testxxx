<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/Base.php");

class Units extends Base
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Jalali_date');
    }

    public function index()
    {
        $this->view('units/index', array("active_menu" => "m-manage-products","title"=> "واحدها"));
    }

    public function units_list()
    {
        require APPPATH . "third_party/datatable-ssp/ssp.class.php";

        $table = 'units';

        // Table's primary key
        $primaryKey = 'id';

        $columns = array(
            array('db' => 'id', 'dt' => 'id'),
            array('db' => 'unit_name', 'dt' => 'unit_name'),
            // array('db' => 'description', 'dt' => 'description'),
            // array('db' => 'unit_name', 'dt' => 'unit_name'),
            // array('db' => 'price', 'dt' => 'price'),
            // array('db' => 'contact_number', 'dt' => 'contact_number'),
            // array('db' => 'firstname', 'dt' => 'firstname'),
            // array('db' => 'lastname', 'dt' => 'lastname'),
            // array('db' => 'email', 'dt' => 'email'),
            // array('db' => 'province_name', 'dt' => 'province_name'),
            // array('db' => 'is_employer', 'dt' => 'is_employer'),
            // array('db' => 'certification_no', 'dt' => 'certification_no'),
            // array('db' => 'certification_image', 'dt' => 'certification_image')
        );

        echo json_encode(SSP::simple($_GET, $this->build_sql_details(), $table, $primaryKey, $columns));
    }

    public function add_unit()
    {
        if (empty($this->input->post("unit_name"))) {
            return ejson(false, "Empty unit name.");
        }
        $cats = $this->db->get_where("units", array("unit_name" => $this->input->post("unit_name")))->result_array();
        if (count($cats) > 0) {
            return ejson(false, "Duplicate unit name.");
        }


        $res = $this->db->insert('units', array("unit_name" => $this->input->post("unit_name")));
        if (!$res) {
            return ejson(false, "خطا در انجام عملیات.");
        }

        return ejson(true, "");
    }



}
