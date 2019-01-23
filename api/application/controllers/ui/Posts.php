<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/Base.php");

class Posts extends Base
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Jalali_date');
    }

    public function index()
    {
        $data         = array("active_menu" => "m-manage-posts", "title" => "پست ها");
        $data['type'] = $this->input->get('type') ? $this->input->get('type') : 'ORDER_CREATED';

        $this->view('posts/index', $data);
    }

    public function posts_list()
    {
        require APPPATH . "third_party/datatable-ssp/ssp.class.php";

        $table = 'view_posts';

        // Table's primary key
        $primaryKey = 'id';

        $columns = array(
            array('db' => 'id', 'dt' => 'id'),
            array('db' => 'title', 'dt' => 'title'),
            array('db' => 'caption', 'dt' => 'caption'),
            array('db' => 'comments_disabled', 'dt' => 'comments_disabled'),
            array('db' => 'user_id', 'dt' => 'user_id'),
            array('db' => 'post_type', 'dt' => 'post_type'),
            array('db' => 'created_at', 'dt' => 'created_at'),
            array('db' => 'name', 'dt' => 'name'),
            array('db' => 'family', 'dt' => 'family')
        );

        echo json_encode(SSP::simple($_GET, $this->build_sql_details(), $table, $primaryKey, $columns));
        // if (isset($_GET['type'])) {
        //     $type = $this->input->get("type");
        //     echo json_encode(SSP::complex($_GET, $this->build_sql_details(), $table, $primaryKey, $columns, " order_status='{$type}'"));
        // } else {
        //     echo json_encode(SSP::simple($_GET, $this->build_sql_details(), $table, $primaryKey, $columns));
        // }
    }

    public function confirm_order()
    {
        $id                 = $this->input->post("id");
        $prepayment_percent = intVal($this->input->post("prepayment_percent"));

        $ok_to_continue = $prepayment_percent > 0 && $prepayment_percent <= 100;
        if (!$ok_to_continue) exit;

        $this->db->trans_start();
        $res = $this->db->set("order_status", "ORDER_CONFIRMED")
                        ->set("prepayment_percent", $prepayment_percent)
                        ->where("id", $id)
                        ->update("orders");
        // add current price to order_lines table.
        $order_lines = $this->db->get_where("order_lines", array("order_id" => $id))->result_array();
        foreach ($order_lines as $order_line) {
            $product = $this->db->get_where("products", array("id" => $order_line["product_id"]))->row(0, "array");
            if ($product) {
                $this->db->set("admin_confirmed_price", $product["price_per_unit"])
                         ->where(array("id" => $order_line["id"]))
                         ->update("order_lines");
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            return ejson(true);
        }

        ejson(false, "Error updating order status.");
    }

    public function confirm_order_payment()
    {
        $id = $this->input->post("id");
        if (!$id) exit;

        $this->db->trans_start();
        $res = $this->db->set("order_status", "ORDER_PAID")
                        ->where("id", $id)
                        ->update("orders");
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            return ejson(true);
        }

        ejson(false, "Error setting order paid status.");
    }

}
