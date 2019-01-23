<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require __DIR__ . "/BASE_REST_Controller.php";

class Misc extends BASE_REST_Controller
{
    public function provinces()
    {
        $this->resp(true, $this->db->get("province")->result_array());
    }
}