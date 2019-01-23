<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/Base.php");

class Customers extends Base
{

    function __construct()
    {
        parent::__construct();
        // $this->load->model("Users_model");
        // $this->load->model("Customers_model");
        // $this->load->model("Settings_model");
        $this->load->library('Jalali_date');
    }

    public function index()
    {
        $data = array("active_menu" => "m-manage-customers");
        $data['title'] = 'مدیریت مشتری ها';
        $this->view('customers/index', $data);
    }

    public function customers_list()
    {
        require APPPATH . "third_party/datatable-ssp/ssp.class.php";

        $table = 'customers';

        // Table's primary key
        $primaryKey = 'id';

        $columns = array(
            array('db' => 'id', 'dt' => 'id'),
            array('db' => 'firstname', 'dt' => 'firstname'),
            array('db' => 'lastname', 'dt' => 'lastname'),
            array('db' => 'national_code', 'dt' => 'national_code'),
            array('db' => 'mobile', 'dt' => 'mobile'),
            array('db' => 'email', 'dt' => 'email'),
            array('db' => 'address', 'dt' => 'address'),
            // array('db' => 'person_type', 'dt' => 'person_type'),
            // array('db' => 'person_semat', 'dt' => 'person_semat'),
            // array('db' => 'company_name', 'dt' => 'company_name'),
            // array('db' => 'province_id', 'dt' => 'province_id'),
            // array('db' => 'province_name', 'dt' => 'province_name'),
            // array('db' => 'is_employer', 'dt' => 'is_employer'),
            // array('db' => 'employer_details_id', 'dt' => 'employer_details_id'),
            // array('db' => 'certification_image', 'dt' => 'certification_image'),
            // array('db' => 'certification_no', 'dt' => 'certification_no'),
            // array('db' => 'created_at', 'dt' => 'created_at'),
            array('db' => 'created_at', 'dt' => 'created_at'),
        );

        echo json_encode(SSP::simple($_GET, $this->build_sql_details(), $table, $primaryKey, $columns));
    }

}
