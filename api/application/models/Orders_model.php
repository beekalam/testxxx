<?php

class Orders_model extends CI_Model
{

    public function get_order_detail($order)
    {
        // die("test");
        $ret = array("lines" => array(), "total_amount" => 0, "prepayment_amount" => 0);
        if ($order['order_type'] == "CART_ORDER" || $order["order_status"] == "ORDER_CREATED") {
            $ret["lines"] = $this->db->select("order_lines.id,order_lines.quantity")
                                     ->select("products.id as product_id,products.title,products.description,products.price_per_unit as price")
                                     ->select("units.unit_name")
                                     ->from("order_lines")
                                     ->join("products", "order_lines.product_id = products.id")
                                     ->join("units", "products.unit_id = units.id", "left")
                                     ->where("order_id", $order['id'])
                                     ->get()
                                     ->result_array();
        } else {
            $ret["lines"] = $this->db->select("order_lines.id,order_lines.quantity,order_lines.admin_confirmed_price as price")
                                     ->select("products.id as product_id,products.title,products.description")
                                     ->select("units.unit_name")
                                     ->from("order_lines")
                                     ->join("products", "order_lines.product_id = products.id")
                                     ->join("units", "products.unit_id = units.id", "left")
                                     ->where("order_id", $order['id'])
                                     ->get()
                                     ->result_array();
        }

        //add prepayment amount and total amount
        $ret["total_amount"] = array_sum(array_column($ret["lines"], "price"));

        if ($ret["total_amount"] != 0 && $order['prepayment_amount'] && $order["prepayment_amount"] != 0) {
            $ret["prepayment_amount"] = intval(($ret["total_amount"] * 100) / $order["prepayment_amount"]);
        }

        return $ret;
    }
}