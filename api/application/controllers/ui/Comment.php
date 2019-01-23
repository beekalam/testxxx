<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/Base.php");

class Comment extends Base
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // $this->load->helper("sms_helper");

        $data = array("active_menu" => "",
                      "title"       => "نظرات");

        $this->view("comments/index", $data);
    }

    public function comments_list()
    {
        require APPPATH . "third_party/datatable-ssp/ssp.class.php";

        $table = 'view_comments';

        // Table's primary key
        $primaryKey = 'id';

        $columns = array(
            array('db' => 'id', 'dt' => 'id'),
            array('db' => 'user_id', 'dt' => 'user_id'),
            array('db' => 'post_id', 'dt' => 'post_id'),
            array('db' => 'comment', 'dt' => 'comment'),
            array('db' => 'created_at', 'dt' => 'created_at'),
            array('db' => 'name', 'dt' => 'name'),
            array('db' => 'family', 'dt' => 'family')
        );

        echo json_encode(SSP::simple($_GET, $this->build_sql_details(), $table, $primaryKey, $columns));
    }

    public function approve_comment()
    {
        $id  = $this->input->post("id");
        $res = $this->db->set('status', '1')
                        ->where('id', $id)
                        ->update('product_comments');
        ejson(true);
    }

    public function delete_comment()
    {
        $id  = $this->input->post("id");
        $res = $this->db->where('id', $id)->delete('product_comments');
        ejson(true);
    }
}
