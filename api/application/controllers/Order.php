<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require __DIR__ . "/BASE_REST_Controller.php";

class Order extends BASE_REST_Controller
{

    public function new_order()
    {
        list($valid, $error) = $this->validate_new_order($_POST);
        if (!$valid) {
            return $this->resp(false, $error);
        }

        $product_id   = $this->input->post('product_id');
        $customer_id  = $this->input->post('customer_id');
        $user_address = $this->input->post("address");
        $payment_type = $this->input->post('payment_type');
        $date         = $this->input->post("date");
        $time         = $this->input->post("time");
        $title        = $this->input->post("title");
        $description  = $this->input->post("description");
        $lat          = $this->input->post("lat");
        $lng          = $this->input->post("lng");

        $code     = time();
        $product  = $this->db->get_where("products", array("id" => $product_id))->row(0, "array");
        $customer = $this->db->get_where('customers', array("id" => $customer_id))->row(0, "array");
        if (is_null($product)) return $this->resp(false, "Product not found.");
        if (is_null($customer)) return $this->resp(false, "Customer not found.");

        if (isset($product["price"]))
            $to_pay = $product['price'];
        else
            return $this->resp(false, "Invalid product price.");

        if ($payment_type == PAYMENT_METHOD_WALLET) {
            // if wallet payment check if user has enough credit
            if ($customer["wallet_credit"] < intval($to_pay)) {
                return $this->resp(false, "Not enough credit");
            }

            $this->db->trans_start();
            // remove credit from user wallet
            $app_settings     = $this->app_settings();
            $discount_percent = intval($app_settings["wallet_discount"]);
            if ($discount_percent > 0) {
                $discount = intval(($product["price"] * $discount_percent) / 100);
                $to_pay   -= $discount;
            }
            $new_credit = $customer['wallet_credit'] - $to_pay;
            $pay_method = "WALLET_PAYMENT";
            $this->db->where('id', $customer_id)
                     ->set("wallet_credit", $new_credit)
                     ->update("customers");

            // insert order
            $order_status = "ORDER_PAID";
            $this->db->insert("orders",
                compact("product_id", "customer_id", "user_address", "code", "to_pay", "date", "time",
                    "pay_method", "order_status", "title","description","lat","lng"));
            $order_id = $this->db->insert_id();

            // insert wallet transaction
            $this->db->insert("wallet_transactions", array("amount"      => $to_pay,
                                                           "customer_id" => $customer_id,
                                                           "order_id"    => $order_id));
            $this->db->trans_complete();
            if ($this->db->trans_status() === false) {
                return $this->resp(false, "Error completing transaction.");
            }

            return $this->resp(true, array("code"     => $code,
                                           "order_id" => $order_id));
        } else {
            $pay_method = "BANK_PAYMENT";
            $res        = $this->db->insert("orders",
                compact("product_id", "customer_id", "user_address", "code", "to_pay", "date",
                    "time", "pay_method", "title","description","lat","lng"));

            return $this->resp($res, array("code"        => $code,
                                           "order_id"    => $this->db->insert_id(),
                                           'payment_url' => base_url('ui/bank/pay?id=' . $this->db->insert_id() . "&code=" . $code)));
        }
    }

    public function order_status()
    {
        if (!isset($_GET['order_id']) or empty($_GET['order_id'])) {
            return $this->resp(false, "Order_id not provided or empty.");
        }

        // find order
        $order = $this->db->get_where("orders", array("id" => $this->input->get('order_id')))
                          ->row(0, "array");
        if (is_null($order)) {
            return $this->resp(false, "Order not found.");
        }

        return $this->resp(true, $order);
    }

    private function validate_new_order($params)
    {
        foreach (array("product_id", "customer_id", "address", "date", "time", "payment_type") as $k) {
            if (!isset($params[$k])) {
                return array(false, "{$k} is not provided.");
            }

            if (empty($params[$k])) {
                return array(false, "No value provided for {$k}.");
            }
        }
        return array(true, "");
    }

    public function orders()
    {
        $page        = $this->input->get("page");
        $offset      = $this->input->get("offset");
        $customer_id = $this->input->get("customer_id");

        $this->load->model("Orders_model");
        $this->load->helper("persiandate_helper");

        if (!$customer_id) {
            return $this->resp(false, "customer_id not provided or empty.");
        }
        // $province_id = $this->input->get('province_id');

        $this->db->select("*")
                 ->from('orders');
        // ->join('category', "products.main_cat_id = category.id", "left")
        // ->join('product_images',"product_images.product_id=product.id","left");
        // ->join("province", "products.province_id = province.id", "left");

        // if (isset($_GET['sub_cat_id'])) {
        //     $this->db->where("(products.admin_approved = 1 and products.sub_cat_id=$sub_cat_id)");
        // } else {
        //     $this->db->where("products.admin_approved = 1");
        // }

        // $where = "( products.admin_approved = 1";
        // if (isset($_GET['sub_cat_id'])) {
        //     $where .= " and products.sub_cat_id=$sub_cat_id ";
        // }
        // $where .= " )";
        $where = array("customer_id" => $customer_id);
        $this->db->where($where);
        if (isset($_GET['page']) && isset($_GET['offset'])) {
            $this->db->limit($page, $offset * $page);
        }

        // if (isset($_GET['province_id']))
        //     $this->db
        //         ->order_by("field(karjoo.province_id,{$province_id}) DESC, karjoo.province_id");
        // else
        //     $this->db->order_by("created_at", "DESC");

        // var_dump($this->db->get_compiled_select());
        // exit;
        $this->db->order_by("created_at", "desc");
        $res = $this->db->get()->result_array();
        // add image absolute paths
        // foreach ($res as &$r) {
        //     $images = explode(',', $r['images']);
        //     $tmp    = array();
        //     foreach ($images as $i) {
        //         $tmp[] = base_url(PRODUCT_IMAGE_PATH . "/" . $i);
        //     }
        //     $r['images'] = $tmp;
        //
        //     // $r["image_absolute_path"] = base_url(AD_IMAGE_PATH . "/" . $r["image"]);
        // }

        foreach ($res as &$order) {
            // $order                          = array_merge($order, $this->Orders_model->get_order_detail($order));
            // $order["created_at_fa"]         = convert_gregorian_iso_to_jalali_iso($order["created_at"]);
            // $order["prepayment_factor_pdf"] = base_url("ui/userreport/invoice?order_id=" . $order['id'] . "&format=pdf");
            // // $order["prepayment_factor_"] = base_url("ui/userreport/invoice?order_id=" . $order['id'] . "&format=pdf");
            // if ($order['order_status'] == 'ORDER_CONFIRMED') {
            //     $order['payment_url'] = base_url('ui/payment/index?order_id=' . $order['id']);
            // }
        }


        $this->resp(true, $res);
    }

    public function order_detail()
    {
        $order_id = $this->input->get("order_id");

        $this->db->select('*')
                 ->from('view_order_lines_detail');

        $res = $this->db->get()->result_array();

        $this->resp(true, $res);
    }


}