<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require __DIR__ . "/BASE_REST_Controller.php";

class Category extends BASE_REST_Controller
{

    public function categories()
    {
        $id = $this->input->get("id");
        // if id is set give subcategories

        $data = $this->db->select('id,cat_name,pic')
                         ->from("category");
        // if (isset($_GET['id'])) {
        //     $this->db->where("parent", $id);
        // } else {
        //     $this->db->where("parent", 0);
        // }

        $data = $this->db->get()
                         ->result_array();

        // foreach ($data as &$d) {
        //     $d['pic_absolute_path'] = base_url(CATEGORY_IMAGE_PATH . "/" . $d['pic']);
        // }

        $this->resp(true, $data);
    }
}