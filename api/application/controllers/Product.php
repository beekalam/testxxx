<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require __DIR__ . "/BASE_REST_Controller.php";

class Product extends BASE_REST_Controller
{

    public function products()
    {
        $page        = $this->input->get("page");
        $offset      = $this->input->get("offset");
        $sub_cat_id  = $this->input->get("sub_cat_id");
        $province_id = $this->input->get('province_id');

        $this->db->select("*")
                 ->from('view_products_list');
        // ->join('category', "products.main_cat_id = category.id", "left")
        // ->join('product_images',"product_images.product_id=product.id","left");
        // ->join("province", " products.province_id = province.id", "left");

        if (isset($_GET['sub_cat_id'])) {
            $this->db->where("(sub_cat_id=$sub_cat_id)");
        }

        // $where = "( products.admin_approved = 1";
        // if (isset($_GET['sub_cat_id'])) {
        //     $where .= " and products.sub_cat_id=$sub_cat_id ";
        // }
        // $where .= " )";

        // search by price.
        // $price_search_ok = isset($_GET['price_min']) && !empty($_GET['price_min']) &&
        //     isset($_GET['price_max']) && !empty($_GET['price_max']);
        // if ($price_search_ok) {
        //     $this->db->where("price_per_unit > ", $this->input->get('price_min'));
        //     $this->db->where("price_per_unit < ", $this->input->get('price_max'));
        // }

        // search by title.
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $this->db->like('title', $this->input->get('search'));
        }

        // order by visits, price, or time.
        if (isset($_GET['order']) && !empty($_GET['order'])) {
            switch ($this->input->get('order')) {
                case 'visits':
                    $this->db->order_by('visits', 'desc');
                    break;
                case 'price':
                    $this->db->order_by('price_per_unit', 'asc');
                    break;
                case 'newest':
                    $this->db->order_by('created_at', 'desc');
                    break;
                case 'num_orders':
                    $this->db->order_by('num_buys', 'desc');
                    break;
            }
        }

        // paging.
        if (isset($_GET['page']) && isset($_GET['offset'])) {
            $this->db->limit($page, $offset * $page);
        }

        // if (isset($_GET['province_id']))
        //     $this->db
        //         ->order_by("field(karjoo.province_id,{$province_id}) DESC, karjoo.province_id");
        // else
        //     $this->db->order_by("created_at", "DESC");

        // var_dump($this->db->get_compiled_select());
        $res = $this->db->get()->result_array();
        // add image absolute paths
        foreach ($res as &$r) {
            $this->add_product_image($r);
            $this->add_wallet_discount($r);
        }

        $this->resp(true, $res);
    }

    private function add_wallet_discount(&$row){
        $settings = $this->app_settings();
        if ($row["price"] != 0 && $settings["wallet_discount"] > 0) {
            $discount_percent = intval($settings['wallet_discount']);
            $row["wallet_buy_discount_percent"] = $discount_percent;
            $row["wallet_buy_discount_amount"] = intval(($row["price"] * $discount_percent) / 100);
            $row["price_with_discount"]        = $row["price"] - $row["wallet_buy_discount_amount"];
        }
    }

    private function add_product_image(&$r)
    {
        $images = explode(',', $r['images']);
        $tmp    = array();
        foreach ($images as $i) {
            $tmp[] = base_url(PRODUCT_IMAGE_PATH . "/" . $i);
        }
        $r['images'] = $tmp;
    }

    public function get_product()
    {
        $product_id = $this->input->get("product_id");
        $row        = $this->db->get_where("view_products_list", array("id" => $product_id))->row(0, "array");
        if ($row) {
            $this->add_product_image($row);
            $this->add_wallet_discount($row);
            //update number of visits for this product
            $this->db->set("visits", intval($row['visits']) + 1)
                     ->where("id", $product_id)
                     ->update('products');
            return $this->resp(true, $row);
        }

        $this->resp(false, "product_id is empty or not provided.");
    }

    public function product_comment()
    {
        $valid = $this->validate_product_comment($_POST);

        if (!$valid["res"]) {
            return $this->resp(false, $valid['error']);
        }

        $product_id  = $this->input->post("product_id");
        $customer_id = $this->input->post("customer_id");
        $comment     = $this->input->post("comment");
        $res         = $this->db->insert('product_comments', compact("product_id", "customer_id", "comment"));
        $this->resp(true, array());
    }

    private function validate_product_comment($params)
    {
        $ret  = array("res" => false, "error" => "");
        $keys = array("product_id", "customer_id", "comment");
        foreach ($keys as $k) {
            if (!isset($params[$k])) {
                $ret['error'] = "{$k} is not provided.";
                return $ret;
            }

            if (empty($params[$k])) {
                $ret['error'] = "No value provided for {$k}.";
                return $ret;
            }
        }

        // check if user already commented  on product.
        $where = array(
            "product_id"  => $this->input->post('product_id'),
            "customer_id" => $this->input->post('customer_id'));
        $row   = $this->db->get_where("product_comments", $where)
                          ->result_array();
        if (count($row) > 0) {
            $ret['error'] = "User already commented on this product.";
            return $ret;
        }

        $ret["res"] = true;
        return $ret;
    }

    public function comments()
    {
        //fixme return approved comments only
        if (isset($_GET['product_id'])) {
            $product_id = $this->input->get("product_id");
            $res        = $this->db->select("product_comments.id,product_comments.product_id,product_comments.customer_id,product_comments.comment")
                                   ->select("customers.firstname,customers.lastname")
                                   ->from('product_comments')
                                   ->join("customers", "product_comments.customer_id = customers.id", "left")
                                   ->where("product_id", $product_id)
                                   ->order_by("id", "desc")
                                   ->get()
                                   ->result_array();
            // remove null values
            foreach ($res as &$comment) {
                if (empty($comment['firstname'])) {
                    $comment['firstname'] = '';
                }
                if (empty($comment['lastname'])) {
                    $comment['lastname'] = '';
                }
            }
            return $this->resp(true, $res);
        }
        $this->resp(false, "product_id not provided.");
    }

}