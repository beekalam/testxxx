<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/Base.php");

class Resellers extends Base
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Jalali_date');
    }

    public function index()
    {
        $this->view('resellers/index', array("active_menu" => "m-manage-resellers","title"=> "فروشنده ها"));
    }

    public function resellers_list()
    {
        require APPPATH . "third_party/datatable-ssp/ssp.class.php";

        $table = 'resellers';

        // Table's primary key
        $primaryKey = 'id';

        $columns = array(
            array('db' => 'id', 'dt' => 'id'),
            array('db' => 'reseller_firstname', 'dt' => 'reseller_firstname'),
            array('db' => 'reseller_lastname', 'dt' => 'reseller_lastname'),
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



}
