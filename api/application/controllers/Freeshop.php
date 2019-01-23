<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// use Ramsey\Uuid\Uuid;
// use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

require __DIR__ . "/BASE_REST_Controller.php";
require APPPATH . "/third_party/UUID.php";

class Freeshop extends BASE_REST_Controller
{
    private $image_field = null;

    public function search()
    {
        // $this->db->get("freeshop")
    }

    public function test()
    {
        return $this->resp(true, array(array("field_id" => 3, "value" => "خانه"), array("field_id" => 7, "value" => "زن")));
    }

    public function create_ad()
    {
        $this->load->helper("image_helper");
        $address = $this->input->post("address", true);
        $address = is_null($address) ? "" : $address;
        $price   = $this->input->post("price", true);
        $price   = is_null($price) ? "" : $price;
        $user_id = $this->input->post("user_id", true);
        $cat_id  = $this->input->post('cat_id', true);
        $fields  = $this->input->post('fields');
        //@todo delete  if it is not used anymore
        $ad_type = "maskan";
        $fields  = json_decode($fields);
        if (json_last_error() != JSON_ERROR_NONE) return $this->resp(false, "Invalid fields value.");

        //@todo check if user is business and then allow insert.
        $this->db->trans_start();
        $this->db->insert("freeshop", compact("address", "ad_type", "price", "cat_id", "user_id"));
        $ad_id           = $this->db->insert_id();
        $category_fields = array_column($this->_catetgory_fields($cat_id), "id");
        $dbg             = "";


        foreach ($fields as $f) {

            if (!isset($f->field_id)) {
                $dbg .= "field_id is not set on field . " . print_r($f, true) . "\n";
                continue;
            }
            if (!isset($f->value)) {
                $dbg .= "value is not set on field " . print_r($f, true) . "\n";
                continue;
            }
            if (in_array($f->field_id, $category_fields)) {
                if ($this->is_image_field($cat_id, $f->field_id)) {
                    $photo_name = base64_imagestring_save(($f->value), FCPATH . FREESHOP_AD_PIC, UUID::v4());
                    $this->db->insert("freeshop_field_values", array("field_id"    => $f->field_id,
                                                                     "ad_id"       => $ad_id,
                                                                     "category_id" => $cat_id,
                                                                     "value"       => $photo_name));
                } else {
                    $this->db->insert("freeshop_field_values", array("field_id"    => $f->field_id,
                                                                     "ad_id"       => $ad_id,
                                                                     "category_id" => $cat_id,
                                                                     "value"       => $f->value));
                }
            } else {
                $dbg .= "field_id: " . @$f["field_id"] . " is not in  category_fields\n";
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return $this->resp(false, "Error completing transaction.");
        }
        return $this->resp(true, array("dbg" => $dbg));
    }

    private function is_image_field($cat_id, $id)
    {
        $res = $this->db->get_where('category_fields', array('category_id'  => $cat_id,
                                                             'field_format' => 'image',
                                                             'id'           => $id))->result_array();
        if (isset($res[0])) {
            return true;
        }

        return false;
    }

    private function _catetgory_fields($cat_id)
    {
        return $this->db->select("category_fields.*")
                        ->from("category_fields")
                        ->where("category_id", $cat_id)
                        ->get()->result_array();
    }

    public function category_fields()
    {
        $cat_id = $this->input->get("cat_id", true);
        if (is_null($cat_id)) return $this->resp(false, "cat_id not provided");


        return $this->resp(true, $this->_catetgory_fields($cat_id));
    }

    public function add()
    {
        $address = $this->input->post("address", true);
        $ad_type = $this->input->post("ad_type", true);
        $price   = $this->input->post("price", true);
        $user_id = $this->input->post("user_id", true);

        $this->db->insert("freeshop", compact("address", "ad_type", "price", "user_id"));

        $this->resp(true, array());
    }

    public function freeshop_ads()
    {

        $cat_id          = $this->input->get('cat_id', true);
        $ads             = $this->db->select("freeshop.id,freeshop.address,freeshop.price,freeshop.cat_id,freeshop.user_id,freeshop.created_at")
                                    ->select("category.cat_name")
                                    ->from("freeshop")
                                    ->join("category", "freeshop.cat_id = category.id", "left")
                                    ->where("cat_id", $cat_id)
                                    ->order_by('created_at', 'desc')
                                    ->get()->result_array();
        $category_fields = $this->db->select("category_fields.*")
                                    ->from("category_fields")
                                    ->where("category_id", $cat_id)
                                    ->get()->result_array();
        $ids             = array_column($ads, "id");


        $ad_fields = $this->db->select("freeshop_field_values.field_id,freeshop_field_values.value,freeshop_field_values.ad_id")
                              ->from("freeshop_field_values")
                              ->where_in("freeshop_field_values.ad_id", array_column($ads, "id"))
                              ->get()->result_array();
        foreach ($ads as &$a) {
            $fields = array();
            foreach ($ad_fields as $f) {
                if ($f['ad_id'] == $a['id']) {
                    $fields[] = $f;
                }
            }
            $a["fields"] = $fields;
        }
        $this->resp(true, compact("ads", "category_fields"));
    }

    public function freeshop_ads2()
    {
        $cat_id = $this->input->get("cat_id", true);

        $res = $this->db->select("freeshop.id,freeshop.address,freeshop.price,freeshop.cat_id,freeshop.user_id,freeshop.created_at")
                        ->select("category.cat_name")
                        ->from("freeshop")
                        ->join("category", "freeshop.cat_id = category.id", "left")
                        ->where("cat_id", $cat_id)
                        ->order_by('created_at', 'desc')
                        ->get()
                        ->result_array();

        foreach ($res as &$r) {
            $d          = $this->db->select("fv.id,fv.field_id,fv.value")
                                   ->select("category_fields.field_name,category_fields.field_suggestions,category_fields.field_format,category_fields.field_label")
                                   ->from("freeshop_field_values as fv")
                                   ->join("category_fields", "fv.field_id = category_fields.id", "left")
                                   ->where("fv.ad_id", $r["id"])
                                   ->get()
                                   ->result_array();
            $r["value"] = $d;
        }
        // $res["category_fields"] = $this->db->get("category_fields")->result_array();

        //@fixme : add paging
        $this->resp(true, $res);
    }


}
