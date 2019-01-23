<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/Base.php");

class Payment extends Base
{
    function __construct()
    {
        $this->checkAuthorization = false;
        parent::__construct();
    }

    public function index()
    {
        $data = array("active_menu" => "",
                      "title"       => "");

        die("no implemented yet.");
    }

}
