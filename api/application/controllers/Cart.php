<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require __DIR__ . "/BASE_REST_Controller.php";


class Cart extends BASE_REST_Controller
{

    public function lines()
    {
        $data["customer_id"] = $this->input->get("customer_id");
        $data["order_type"]  = 'CART_ORDER';
        $res["order"]        = $this->db->get_where("orders", $data)->row(0, "array");

        if ($res["order"]) {
            $res["lines"] = $this->db->select('order_lines.id as line_id,order_lines.product_id,products.price')
                                     ->select('products.title,products.description')
                                     ->select("CONCAT(resellers.reseller_firstname,' ',resellers.reseller_lastname) as reseller_name")
                                     ->from("order_lines")
                                     ->join("products", "order_lines.product_id = products.id", "left")
                                     ->join("resellers", "products.reseller_id = resellers.id", "left")
                                     ->where("order_id", $res["order"]["id"])
                                     ->get()
                                     ->result_array();
            // add products images
            foreach ($res["lines"] as &$line) {
                $images         = $this->db->get_where("product_images", array("product_id" => $line["product_id"]))->result_array();
                $line["images"] = array();
                foreach ($images as $image) {
                    $line["images"][] = base_url(PRODUCT_IMAGE_PATH . "/" . $image["image"]);
                }
            }

            $res["order"]["total_price"] = array_sum(array_column($res["lines"], "price"));
        } else {
            $res["order"] = $res['lines'] = array();
        }

        $this->resp(true, $res);
    }

    public function remove_line()
    {
        $res = $this->db->where("id", $this->input->post('line_id'))
                        ->delete("order_lines");
        $this->resp(true, array());
    }

    public function add_line()
    {
        $customer_id = $this->input->post("customer_id");
        $product_id  = $this->input->post("product_id");

        // check for valid customer id
        $customer = $this->db->get_where("customers", array("id" => $customer_id))->row(0, "array");
        if (!$customer) {
            return $this->resp(false, "Invalid customer id.");
        }

        $where = array("order_type" => 'CART_ORDER', "customer_id" => $customer_id);
        //create order row if not present already
        $order = $this->db->where($where)
                          ->get("orders")
                          ->row(0, "array");
        if (!$order) {
            $this->db->insert("orders", array("order_type" => 'CART_ORDER', "customer_id" => $customer_id, "code" => time()));
        }

        $order = $this->db->where($where)
                          ->get("orders")
                          ->row(0, "array");

        if ($order) {
            // ignore order line if not already in basket
            $where      = array(
                "order_id"   => $order["id"],
                "product_id" => $product_id
            );
            $order_line = $this->db->get_where("order_lines", $where)->row(0, "array");
            if ($order_line) {
                // $this->db->set("quantity", $quantity)->where($where)->update("order_lines");
                return $this->resp(true, array());
            }

            // insert new order line
            $this->db->insert("order_lines", array(
                "order_id"   => $order["id"],
                "product_id" => $product_id
            ));

            return $this->resp(true, array());
        }

        $this->resp(false, "Unknown error.");
    }

    public function check_out()
    {
        $this->load->helper("sms_helper");
        // $text = "http://beniz.fanacmp.ir/masaleh/api/ui/userreport/invoice?order_id=23";
        // send_sms_success_buy("09359012419",$text);
        // exit;

        $customer_id       = $this->input->post("customer_id");
        $user_request_date = $this->input->post("user_request_date");
        $user_address      = $this->input->post("user_address");

        $where = array("order_type" => 'CART_ORDER', "customer_id" => $customer_id);
        $order = $this->db->where($where)
                          ->get("orders")
                          ->row(0, "array");

        $res = $this->db->set("order_type", "FINAL_ORDER")
                        ->set("user_address", $user_address)
                        ->set("user_request_date", $user_request_date)
                        ->where($where)
                        ->update("orders");

        if ($res) {
            // send order sms
            $url      = base_url("ui/userreport/invoice?format=html&order_id=" . $order['id']);
            $text     = "شماره سفارش " . $order['code'];
            $text     .= $url;
            $customer = $this->db->get_where("customers", array("id" => $order['customer_id']))->row(0, "array");
            // send_sms_success_buy($customer['mobile'], $text);

            return $this->resp(true, array());
        }

        $this->resp(false, "Error checking out.");
    }


}