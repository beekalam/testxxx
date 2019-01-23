<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require __DIR__ . "/BASE_REST_Controller.php";

/**
 * Class Main
 *
 * @package App\Http\Controllers\Api
 */
class Main extends BASE_REST_Controller
{

    public function api_info()
    {
        $data["sliders"] = $this->db->select("slider_image,description")->get('sliders')->result_array();
        foreach($data["sliders"] as &$slide){
            $slide["slider_image"] = base_url(SLIDER_IMAGE_PATH."/".$slide["slider_image"]);
        }
        $this->resp(true,$data);
    }

    //<editor-fold desc="login and profile user">
//     function checkuser()
//     {
//         //$data = json_decode(file_get_contents('php://input'));
//         $data = new stdClass();
//
//         if ($_REQUEST) {
//             if (isset($_REQUEST['mobile']) && $_REQUEST['mobile'])// $data->mobile = $_REQUEST['mobile'];
//                 $data->mobile = $_REQUEST['mobile'];
//             if (isset($_REQUEST['code']) && $_REQUEST['code'])// $data->mobile = $_REQUEST['mobile'];
//                 $data->mobile = $_REQUEST['code'];
//
//         }
//
//
//         if (isset($data->code) && isset($data->mobile) && $data->code && $data->mobile) {
//             $datac = $this->base_model->get_data('karjoo', 'status', array('mobile' => $data->mobile, 'code' => $data->code));
//             if (!$datac || $datac[0]->status == 0)
//                 $this->response(array("user_active" => false));
//         } else
//             $this->response(array("user_active" => false));
//     }
//


    public function register()
    {
        $user_id = 0;
        $data    = null;
        $data    = new stdClass();

        if ($_REQUEST) {
            if (isset($_REQUEST['mobile']) && $_REQUEST['mobile'])
                $data->mobile = $_REQUEST['mobile'];
        }

        if ($data) {
            $data2['err'] = null;

            if ((!isset($data->mobile)) || ($data->mobile == NULL))
                $data2['err'] .= 'موبایل الزامی است.';


            if (!$data2['err']) {
                unset($data2['format']);

                if (!$this->base_model->get_data('users', '*', array('mobile' => $data->mobile))) {
                    $user_id            = $this->base_model->insert('users', $data);
                    $data2['registerd'] = true;
                } else {
                    $data2['logined'] = true;
                }

                if (isset($data->mobile))
                    $data2['code'] = $this->send_notify($user_id, $data->mobile);
                $this->base_model->update('users', array('mobile' => $data->mobile), array('code' => $data2['code']));

            }

            if ($data2)
                $this->response($data2);
        } else
            $this->response(array("err" => "null"));
    }

    function send_notify($user_id, $mobile_number)
    {
        $this->load->helper("sms_helper");
        $code    = rand(1000, 9999);
        $message = ' براي تاييد ايميل 
<a href="http://shadleen.com/api/index.php/main/check_code?code=' . $code . '&user_id=' . $user_id . '">
اينجا
</a>
 کليد کنيد.
';

        if ($mobile_number) {
            send_sms_verify_code($mobile_number, $code);
        }

        return $code;

    }
    public function check_code()
    {
        //$data = json_decode(file_get_contents('php://input'));

        $data = new stdClass();
        if ($_REQUEST) {
            if (isset($_REQUEST['mobile']) && isset($_REQUEST['code']))// $data->mobile = $_REQUEST['mobile'];
            {
                $data->mobile = $_REQUEST['mobile'];
                $data->code   = $_REQUEST['code'];
            }
        }

        log_message("debug", "in check code");
        log_message("debug", print_r($_REQUEST, true));
        if ($data && isset($data->code) && isset($data->mobile)) {
            if ($this->base_model->get_data('users', '*', array('mobile' => $data->mobile, 'code' => $data->code))) {


                $data3['status'] = 1;
                $this->base_model->update('users', array('mobile' => $data->mobile), $data3);
                //unset($_SESSION['register_code']);
                $res = array("user_active" => true);

                $user = $this->db->get_where("users", array(
                    "mobile" => $data->mobile,
                    "code"   => $data->code
                ))->result_array();

                if (count($user) > 0) {
                    $res["user_id"] = $user[0]["id"];
                }
                $this->response($res);
            } else {
                //unset($_SESSION['register_code']);
                $data = array("user_active" => false);
                $this->response($data);
            }
        }
    }
//
//     public function profile()
//     {
//         $this->checkuser();
//         $data = new stdClass();
//         if ($_REQUEST) {
//             if (isset($_REQUEST['mobile']))// $data->mobile = $_REQUEST['mobile'];
//                 $data->mobile = $_REQUEST['mobile'];
//         }
//         if ($data) {
//             $data = $this->base_model->get_data('karjoo', 'mobile,firstname,lastname,mobile,address,status', array('mobile' => $data->mobile));
//
//             if ($data) {
//                 $this->response($data);
//             }
//
//         }
//     }
//
//     public function edit_profile()
//     {
//         $this->checkuser();
//         $data = json_decode(file_get_contents('php://input'));
//         if ($data) {
//             $data2 = $data;
//
//
//             //$data2['saved'] = false;
//             if (isset($data2->sx)) {
//                 $data2->sexual = $data2->sx;
//                 unset($data2->sx);
//             }
//
//             $data3['err'] = null;
//
//             if ((!isset($data->mobile)) && ($data->mobile == NULL))
//                 $data3['err'] .= 'موبایل الزامی است.<br>';
//
//             if (!$data3['err']) {
//                 unset($data2->format);
//                 $mobile = $data2->mobile;
//                 unset($data2->mobile);
//                 $this->base_model->update('karjoo', array('mobile' => $mobile), $data2);
//                 //$data2['saved'] = true;
//             }
//
//
//             if ($data3['err'])
//                 $this->response($data3);
//             else {
//                 $data3 = array("save" => true);
//                 $this->response($data3);
//             }
//
//         }
//     }
//
//
//     /**
//      * Updates user profile.
//      *
//      * @return json
//      *
//      * @SWG\Post(
//      *     path="/kalairani/api/index.php/main/user_update_profile",
//      *     description="Returns profile update result.",
//      *     operationId="api.user.user_update_profile",
//      *     produces={"application/json"},
//      *     tags={"user"},
//      *     @SWG\Parameter(
//      *         name="full_name",
//      *         in="body",
//      *          description="full name of user space separated.",
//      *          required=true,
//      *          @SWG\Schema(ref="#/definitions/UserUpdateProfile"),
//      *     ),
//      *     @SWG\Response(
//      *         response=200,
//      *         description="Operation result."
//      *     )
//      * )
//      */
//     public function user_update_profile()
//     {
//         $this->load->helper("utils");
//
//         //validate
//         // $valid = $this->validate_user_complete_profile($_POST);
//         // if (!$valid["res"]) {
//         //     return $this->resp(false, $valid['error']);
//         // }
//
//         // update
//         $params = array(
//             "id"            => $this->input->post("id"),
//             "national_code" => $this->input->post("national_code"),
//             "address"       => $this->input->post("address"),
//             "email"         => $this->input->post("email"),
//             "person_semat"  => $this->input->post("person_semat"),
//             "company_name"  => $this->input->post("company_name"),
//             "province_id"   => $this->input->post("province_id"),
//             "tel"           => $this->input->post("tel"),
//             "mobile"        => $this->input->post("mobile")
//         );
//
//         $id = $_POST["id"];
//         unset($_POST["id"]);
//
//         $name = explode(' ', $_POST['fullname']);
//         unset($_POST['fullname']);
//         $params['firstname'] = $params['lastname'] = "";
//         if (count($name) == 2) {
//             $_POST['firstname'] = $params['firstname'] = $name[0];
//             $_POST['lastname']  = $params['lastname'] = $name[1];
//         } else if (count($name) > 2) {
//             $_POST['firstname'] = $params['firstname'] = $name[0];
//             unset($name[0]);
//             $_POST['lastname'] = $params['lastname'] = join(' ', $name);
//         }
//
//
//         $this->db->trans_start();
//         $res = $this->db->where("id", $id)->update("karjoo", $params);
//
//         // add or update hoghoghi details.
//         if (isset($_POST['person_type']) && $_POST['person_type'] == 'hoghoghi') {
//
//             if (isset($_POST['certification_no'])) {
//                 $certification_no = $_POST['certification_no'];
//                 unset($_POST['certification_no']);
//             }
//
//             if (isset($_POST['certification_image'])) {
//                 $certification_image = $_POST['certification_image'];
//                 unset($_POST['certification_image']);
//             }
//
//             $karjoo = $this->db->get_where("karjoo", array("id" => $id))
//                                ->result_array();
//
//             // if already not a member.
//             if (empty($karjoo['employer_details_id']) && isset($_POST['certification_image']) &&
//                 isset($_POST['certification_no'])) {
//                 $image_name = base64_imagestring_save($certification_image, FCPATH . CERTIFICATION_IMAGE_PATH, time());
//                 $this->db->insert("employer_details", array(
//                     "certification_no"    => $certification_no,
//                     "certification_image" => $image_name
//                 ));
//                 $employer_details_id = $this->db->insert_id();
//                 $this->db->set("employer_details_id", $employer_details_id)
//                          ->where("id", $karjoo[0]["id"])
//                          ->update("karjoo");
//             } else if (isset($_POST['certification_image']) &&
//                 isset($_POST['certification_no'])) {
//                 //just update employer details.
//                 $image_name = base64_imagestring_save($certification_image, FCPATH . CERTIFICATION_IMAGE_PATH, time());
//                 $this->db->set("certification_no", $certification_no)
//                          ->set("certification_image", $image_name)
//                          ->where('id', $karjoo[0]["employer_details_id"])
//                          ->update("employer_details");
//             }
//         }
//
//         $this->db->trans_complete();
//         if ($this->db->trans_status() === false) {
//             $this->resp(false, "خطا در انجام عملیات.");
//         }
//
//         return $this->resp(true, array());
//     }
//
//     private function validate_user_complete_profile($params = array())
//     {
//         $ret = array("res" => false, "error" => "");
//         // validate user exists
//         $user = $this->db->get_where("karjoo", array("id" => $params["id"]))->result_array();
//         if ($user && count($user) == 0) {
//             $ret["error"] = "User with id {$params['id']} does not exist.";
//             return $ret;
//         }
//
//         foreach (array("firstname", "lastname") as $k) {
//             if (!isset($params[$k])) {
//                 $ret['error'] = "{$k} is not provided.";
//                 return $ret;
//             }
//
//             if (empty($params[$k])) {
//                 $ret['error'] = "No value provided for {$k}.";
//                 return $ret;
//             }
//         }
//
//         // check for valid person_type value
//         if (!in_array($params['person_type'], array('haghighi', 'hoghoghi'))) {
//             $ret['error'] = 'Invalid value for person_type.';
//             return $ret;
//         }
//
//
//         if ($params['person_type'] == 'hoghoghi') {
//             foreach (array("person_semat", "company_name", "certification_no", "certification_image") as $k) {
//                 if (!isset($params[$k])) {
//                     $ret['error'] = "{$k} is not provided.";
//                     return $ret;
//                 }
//
//                 if (empty($params[$k])) {
//                     $ret['error'] = "No value provided for {$k}.";
//                     return $ret;
//                 }
//             }
//         }
//
//         $ret["res"] = true;
//         return $ret;
//     }
//
//     /**
//      * Returns general user details.
//      *
//      * @return json
//      *
//      * @SWG\Post(
//      *     path="/kalairani/api/index.php/main/user_info",
//      *     description="Returns general user details.",
//      *     operationId="api.user_info",
//      *     produces={"application/json"},
//      *     tags={"user"},
//      *     @SWG\Parameter(
//      *         name="first_name",
//      *         in="body",
//      *          @SWG\Schema(ref="#/definitions/GetUserInfoRequest"),
//      *     ),
//      *     @SWG\Response( response=200, description="Operation result." )
//      * )
//      */
//     public function user_info()
//     {
//         $this->load->model("Bets_model");
//         // $params = $this->read_params();
//         $valid = $this->validate_user_info($_GET);
//         if (!$valid['res']) {
//             return $this->resp(false, $valid['error']);
//         }
//
//         $user_info = $this->db->select("id,firstname,lastname,national_code,is_employer,mobile,person_type,province_id")
//                               ->where("id", $_GET["user_id"])
//                               ->get("karjoo")
//                               ->result_array();
//
//         if (count($user_info) == 0) {
//             return $this->resp(false, "Invalid user_id.");
//         }
//
//         $user_info[0]['bets'] = $this->Bets_model->bets($_GET["user_id"]);
//         $this->resp(true, $user_info);
//     }
//
//     private function validate_user_info($params = array())
//     {
//         $ret = array("res" => false, "error" => "");
//
//         foreach (array("user_id") as $k) {
//             if (!isset($params[$k])) {
//                 $ret['error'] = "{$k} is not provided.";
//                 return $ret;
//             }
//
//             if (empty($params[$k])) {
//                 $ret['error'] = "No value provided for {$k}.";
//                 return $ret;
//             }
//         }
//
//         $ret["res"] = true;
//         return $ret;
//     }
//
//     //</editor-fold>
//
//     //<editor-fold desc="category">
//     /**
//      * Returns a list of main categories.
//      *
//      * @return json
//      *
//      * @SWG\Get(
//      *     path="/kalairani/api/index.php/main/categury",
//      *     description="Returns a list of main categories.",
//      *     operationId="api.page_ads",
//      *     produces={"application/json"},
//      *     tags={"categories"},
//      *     @SWG\Response( response=200, description="Operation result." )
//      * )
//      */
//     public function categury()
//     {
//         $data = $this->db->select('id,cat_name,pic')
//                          ->from("category")
//                          ->where("parent", 0)
//                          ->get()
//                          ->result_array();
//
//         foreach ($data as &$slid) {
//             if ($slid["pic"])
//                 $slid["pic"] = base_url('uploads/') . $slid["pic"];
//         }
//         if ($data)
//             $this->response($data);
//     }
//
//     public function categuries()
//     {
//         $data = $this->base_model->get_data('category', '*');
//         foreach ($data as $slid) if ($slid->pic) $slid->pic = base_url('../admin/uploads/') . $slid->pic;
//         if ($data)
//             $this->response($data);
//
//     }
//
//     /**
//      * Return a category with subcategories.
//      *
//      * @return json
//      *
//      * @SWG\Post(
//      *     path="/kalairani/api/index.php/main/category_catalog",
//      *     description="Return a category with subcategories.",
//      *     operationId="api.category_catalog",
//      *     produces={"application/json"},
//      *     tags={"categories"},
//      *     @SWG\Parameter(
//      *         name="first_name",
//      *         in="body",
//      *          @SWG\Schema(ref="#/definitions/CategoryCatalogRequest"),
//      *     ),
//      *     @SWG\Response( response=200, description="Operation result." )
//      * )
//      */
//     public function category_catalog()
//     {
//         $this->load->helper("utils");
//         // $params = json_decode(file_get_contents('php://input'));
//         // $params = object_to_array($params);
//
//         //validate inputs
//         $valid = $this->validate_category_catalog($_POST);
//         if (!$valid['res']) {
//             return $this->resp(false, $valid["error"]);
//         }
//
//
//         $res = $this->db->select("id,cat_name,pic")
//                         ->where("id", $_POST["id"])
//                         ->get("category")
//                         ->result_array();
//
//         // add subcategories
//         if (count($res)) {
//             $sub_categories           = $this->db->select("id,cat_name")
//                                                  ->where("parent", $_POST["id"])
//                                                  ->get("category")
//                                                  ->result_array();
//             $res[0]['sub_categories'] = $sub_categories;
//         }
//
//         $this->resp(true, $res);
//     }
//
//     private function validate_category_catalog($params = array())
//     {
//         $ret = array("res" => false, "error" => "");
//         foreach (array("id") as $k) {
//             if (!isset($params[$k])) {
//                 $ret['error'] = "{$k} is not provided.";
//                 return $ret;
//             }
//             if (empty($params[$k])) {
//                 $ret['error'] = "No value provided for {$k}.";
//                 return $ret;
//             }
//         }
//
//         $ret["res"] = true;
//         return $ret;
//     }
//     //</editor-fold>
//
//     //<editor-fold desc="page ads">
//     /**
//      * Return list of ads for main app view.
//      *
//      * @return json
//      *
//      * @SWG\Get(
//      *     path="/kalairani/api/index.php/main/page_ads",
//      *     description="Return list of ads for main app view.",
//      *     operationId="api.page_ads",
//      *     produces={"application/json"},
//      *     tags={"page_ads"},
//      *     @SWG\Response( response=200, description="Operation result." )
//      * )
//      */
//     public function page_ads()
//     {
//         $pictures = $this->db->select("pic")
//                              ->where("tag", "front_page")
//                              ->get("slider")
//                              ->result_array();
//
//         foreach ($pictures as &$pic) {
//             $pic["absolute_path"] = base_url(AD_IMAGE_PATH . "/" . $pic["pic"]);
//         }
//
//         $this->resp(true, $pictures);
//     }
//
//     /**
//      * Return a picture and description for kala meli page.
//      *
//      * @return json
//      *
//      * @SWG\Get(
//      *     path="/kalairani/api/index.php/main/kala_meli_page",
//      *     description="Return a picture and description for kala meli page.",
//      *     operationId="api.kala_meli_page",
//      *     produces={"application/json"},
//      *     tags={"page_ads"},
//      *     @SWG\Response( response=200, description="Operation result." )
//      * )
//      */
//     public function kala_meli_page()
//     {
//         $rows = $this->db->select("pic,description")
//                          ->where("tag", "kala_meli_page")
//                          ->get("slider")
//                          ->result_array();
//         foreach ($rows as &$row) {
//             $row['absolute_path'] = base_url(AD_IMAGE_PATH . "/" . $row['pic']);
//         }
//
//         $this->resp(true, $rows);
//
//     }
//
//     /**
//      * Returns a single gif url.
//      *
//      * @return json
//      *
//      * @SWG\Get(
//      *     path="/kalairani/api/index.php/main/gif_ads",
//      *     description="Returns a single gif url.",
//      *     operationId="api.gif_ads",
//      *     produces={"application/json"},
//      *     tags={"page_ads"},
//      *     @SWG\Response( response=200, description="Operation result." )
//      * )
//      */
//     public function gif_ads()
//     {
//         $rows = $this->db->select("pic")
//                          ->where("tag", "gif_ad")
//                          ->get("slider")
//                          ->result_array();
//         foreach ($rows as &$row) {
//             $row['absolute_path'] = base_url(AD_IMAGE_PATH . "/" . $row['pic']);
//         }
//
//         $this->resp(true, $rows);
//     }
//     //</editor-fold>
//
//
//     public function product()
//     {
//         $this->load->helper("utils");
//         $data = json_decode(file_get_contents('php://input'));
//         if ($data) {
//             //$this->response($data->id);
//             $data = $this->base_model->get_data('products', '*', array('id' => $data->id));
//             foreach ($data as $slid) if ($slid->pic) $slid->pic = base_url('../admin/uploads/') . $slid->pic;
//
//             foreach ($data as $datarow) {
//                 unset($datarow->tel);
//             }
//
//             // fixme:ask should return single product
//             // add cat name
//             $data     = object_to_array($data);
//             $cat_name = $this->db->get_where("category", array("id" => $data[0]["cat_id"]))->row(0, "array");
//             if ($cat_name)
//                 $cat_name = $cat_name["cat_name"];
//             $data[0]["cat_name"] = $cat_name;
//
//             // add product subcategories
//             $product_cat_id            = $data[0]["cat_id"];
//             $sub_categories            = $this->db->select("id,cat_name")
//                                                   ->where("parent", $product_cat_id)
//                                                   ->get("category")
//                                                   ->result_array();
//             $data[0]["sub_categories"] = count($sub_categories) == 0 ? array() : $sub_categories;
//
//             if ($data)
//                 $this->response(array("product" => $data));
//         }
//     }
//
//
//     //<editor-fold desc="bets">
//     /**
//      * Returns general settings plus bets.
//      *
//      * @return json
//      *
//      * @SWG\Get(
//      *     path="/kalairani/api/index.php/main/api_settings",
//      *     description="Returns general settings..",
//      *     operationId="api.job.degrees",
//      *     produces={"application/json"},
//      *     tags={"general"},
//      *     @SWG\Response( response=200, description="Operation result." )
//      * )
//      */
//     public function api_settings()
//     {
//
//         // echo json_encode($res);
//         $this->resp(true, array("bets" => $this->bets()));
//     }
//
//     //</editor-fold>
//
//     //<editor-fold desc="other">
//
//     //------------------- added by Mansouri ---------------------------------
//     public function categories2()
//     {
//         $params = json_decode(file_get_contents('php://input'));
//         if ($params && isset($params->parent_id)) {
//             // get parent info
//             $parent = $this->db->get_where('category', array("id" => $params->parent_id))
//                                ->row(0, "array");
//             if ($parent)
//                 $parent = $parent["cat_name"];
//
//             $categories = $this->db->get_where("category", array("parent" => $params->parent_id))
//                                    ->result_array();
//
//             // add pic and parent name
//             foreach ($categories as &$category) {
//                 $category["pic"]         = "fixme";
//                 $category["parent_name"] = $parent;
//             }
//
//             $this->resp(true, $categories);
//         } else {
//             $res = $this->db->get("category")->result_array();
//             $this->resp(true, $res);
//         }
//
//         $this->resp(true, array());
//     }
//
//
//     /**
//      * Insert new advertisement
//      *
//      * @return json
//      *
//      * @SWG\Post(
//      *     path=" / kalairani / api / index . php / main / create_ad",
//      *     description="Insert new advertisement",
//      *     operationId="api . create_ad",
//      *     produces={"application / json"},
//      *     tags={"product"},
//      *     @SWG\Parameter(
//      *         name="first_name",
//      *         in="body",
//      *          @SWG\Schema(ref="#/definitions/CreateAdRequest"),
//      *     ),
//      *     @SWG\Response(response = 200, description = "Operation result.")
//      * )
//      */
//     public function create_ad()
//     {
//         $this->load->helper("utils");
//         // $params = json_decode(file_get_contents('php://input'));
//         // $params = object_to_array($params);
//         $valid = $this->validate_create_ad($_POST);
//
//         //validate
//         if (!$valid["res"]) {
//             return $this->resp(false, $valid["error"]);
//         }
//
//         // insert into db
//         $image_string = $_POST["image"];
//         unset($_POST['image']);
//         $res       = $this->db->insert("products", $_POST);
//         $insert_id = $this->db->insert_id();
//         if ($res) {
//             $image_name = base64_imagestring_save($image_string, FCPATH . AD_IMAGE_PATH, time());
//             $this->db->set("image", $image_name)
//                      ->where("id", $insert_id)
//                      ->update("products");
//             return $this->resp(true, array());
//         }
//
//         $this->resp(false, "خطا در انجام عملیات.");
//     }
//
//     private function validate_create_ad($params = array())
//     {
//         $ret = array("res" => false, "error" => "");
//         foreach (array("user_id", "image", "main_cat_id", "sub_cat_id", "contact_number", "description") as $k) {
//             if (!isset($params[$k])) {
//                 $ret['error'] = "{$k} is not provided.";
//                 return $ret;
//             }
//             if (empty($params[$k])) {
//                 $ret['error'] = "No value provided for {$k}.";
//                 return $ret;
//             }
//         }
//
//         // check image string has right format
//         if (!valid_base64_imagestring($params["image"])) {
//             $ret['error'] = "Invalid image format.";
//             return $ret;
//         }
//
//         $ret["res"] = true;
//         return $ret;
//     }
//
//     public function irani_product_register()
//     {
//         $params = json_decode(file_get_contents('php://input'));
//         $params = object_to_array($params);
//         //validate inputs
//         $valid = $this->validate_irani_product_register($params);
//         if ($valid["res"]) {
//             return $this->resp(false, $valid["error"]);
//         }
//     }
//
//     private function validate_irani_product_register($params)
//     {
//         $ret = array("res" => false, "error" => "");
//         foreach (array("name", "family", "tel", "email", "national_code", "address", "province_id", "city_id") as $k) {
//             if (!isset($params[$k])) {
//                 $ret['error'] = "{$k} is not provided.";
//                 return $ret;
//             }
//
//             if (empty($params[$k])) {
//                 $ret['error'] = "No value provided for {$k}.";
//                 return $ret;
//             }
//         }
//
//         $ret["res"] = true;
//         return $ret;
//     }
//
//     public function top_products()
//     {
//
//     }
//
//     public function ads()
//     {
//         $this->resp(true, array("main_ads" => array(),
//                                 "sub_ads"  => array()));
//     }
//
//
//     //---------------------------------------------------------------------------------------
//     //
//     // public function cities()
//     // {
//     //     $data = json_decode(file_get_contents('php://input'));
//     //     if ($data) {
//     //         $where = array();
//     //         if (isset($data->province)) $where['province_id'] = $data->province;
//     //         if (isset($data->country)) $where['country_id'] = $data->country;
//     //         $where['status'] = 1;
//     //         $data2           = $this->base_model->get_data('city', 'id,name', $where);
//     //         foreach ($data2 as $slid)
//     //             if (isset($slid->pic) && $slid->pic)
//     //                 $slid->pic = base_url('../uploads/city/') . $slid->pic;
//     //         if ($data2)
//     //             $this->response($data2);
//     //     }
//     // }
//     //
//     // public function aboutus()
//     // {
//     //
//     //     $data = $this->base_model->get_data('about_us', '*', array('id' => 0));
//     //     foreach ($data as $slid) if ($slid->pic) $slid->pic = base_url('../uploads/') . $slid->pic;
//     //     if ($data)
//     //         $this->response($data);
//     //
//     // }
//     //
//     // public function contactus()
//     // {
//     //
//     //     $data = $this->base_model->get_data('contact_us', '*', array('id' => 0));
//     //
//     //     if ($data)
//     //         $this->response($data);
//     //
//     // }
//     //
//     // public function terms()
//     // {
//     //
//     //     $data = $this->base_model->get_data('terms', '*', array('id' => 1));
//     //
//     //     if ($data)
//     //         $this->response($data);
//     //
//     //
//     // }
//     //
//     // public function policy()
//     // {
//     //
//     //     $data = $this->base_model->get_data('policy', '*', array('id' => 1));
//     //
//     //     if ($data)
//     //         $this->response($data);
//     //
//     //
//     // }
//     //
//
//
// ////////////////////////////////////////////////////////////////////////////////////////////////////////
//     public function resend_code()
//     {
//         $data2 = json_decode(file_get_contents('php://input'));
//         if ($data2) {
//
//
//             $data = null;
//             if (isset($data2->mobile))
//                 $data = $this->base_model->get_data('karjoo', '*', array('mobile' => $data2->mobile));
//
//             if ($data) {
//                 $code = rand(1000, 9999);
//
//                 $this->session->set_userdata('register_code', $code);
//                 $this->session->set_userdata('mobile', $data2->mobile);
//
//                 $message = ' براي تاييد ايميل
// <a href="http://shadleen.com/demo96/api/index.php/main/check_code?code=' . $code . '&user=' . $data[0]->id . ">
// اينجا
// </a>
//  کليد کنيد.
// ';
//              //" . $code . ' ' . $data[0]->id;
//
//
//                 if ($data2->sms) {
//
//                 }
//
//                 $this->response(array("send_code" => true, "code" => $code));
//
//             }
//
//         }
//     }
//
//     public function login()
//     {
//         $data = json_decode(file_get_contents('php://input'));
//         if ($data) {
//             $logined = false;
//             $err     = "";
//             if (isset($data->mobile) && isset($data->password)) {
//                 $mobile   = $data->mobile;
//                 $password = sha1($data->password);
//
//                 $result = $this->base_model->get_data('karjoo', '*', array('mobile' => $mobile, 'password' => $password));
//                 if ($result != NULL) {
//
//                     $this->session->set_userdata('login_in', true);
//                     $this->session->set_userdata('user_id', $result[0]->id);
//                     $logined = true;
//                     $data2   = array("logined" => $logined, "user_id" => $result[0]->id, "error" => $err);
//                     $this->response($data2);
//                 } else {
//                     $err = "شماره موبایل یا رمز عبور را وارد فرمایید. ";
//
//                 }
//             } else {
//                 $err              = "شماره موبایل یا رمز عبور را وارد فرمایید. ";
//                 $data2["logined"] = false;
//             }
//
//             $data2 = array("logined" => $logined, "error" => $err);
//             $this->response($data2);
//         }
//     }
//
//     public function country()
//     {
//
//         $data = $this->base_model->get_data('country', 'id,name', array('status' => 1));
//         if ($data)
//             $this->response($data);
//
//     }
//
//     /**
//      * Returns a list of provinces.
//      *
//      * @return json
//      *
//      * @SWG\Get(
//      *     path="/kalairani/api/index.php/main/provinces",
//      *     description="Returns a list of provinces.",
//      *     operationId="api.provinces",
//      *     produces={"application/json"},
//      *     tags={"province_cities"},
//      *     @SWG\Response( response=200, description="Operation result." )
//      * )
//      */
//     public function provinces()
//     {
//         $data = $this->db->select('id,name')
//             // ->where('status', 1)
//                          ->get('province')
//                          ->result_array();
//         $this->resp(true, $data);
//     }
//
//     public function topcities()
//     {
//         $data2 = $this->base_model->get_data('city', 'id,name,pic', NULL, NULL, NULL, NULL, 'visits desc');
//         $i     = 0;
//         foreach ($data2 as $slid) {
//             $i++;
//             if (isset($slid->pic) && $slid->pic)
//                 $slid->pic = base_url('../uploads/city/') . $slid->pic;
//             if ($i > 5) unset($data2[$i]);
//         }
//         if ($data2)
//             $this->response($data2);
//     }
//
//     public function slider()
//     {
//
//         $data = $this->base_model->get_data('slider', '*');
//         foreach ($data as $slid) if ($slid->pic) $slid->pic = base_url('../admin/uploads/') . $slid->pic;
//         if ($data)
//             $this->response($data);
//
//     }
//
//     public function checklogin()
//     {
//
//         if (isset($_SESSION['login_in']) && $_SESSION['login_in'] = true) {
//             $data = array("logined" => true);
//             $this->response($data);
//         } else {
//             $data = array("logined" => false);
//             $this->response($data);
//         }
//
//     }
//
//     public function logout()
//     {
//         unset($_SESSION['login_in']);
//         unset($_SESSION['user_id']);
//         unset($_SESSION['fullname']);
//         $data = array("logout" => true);
//         $this->response($data);
//
//     }
//
//     public function editprofile()
//     {
//         $data = json_decode(file_get_contents('php://input'));
//         if ($data) {
//
//             $data2 = $data;
//
//             //$data2['saved'] = false;
//             if (isset($data2->sx)) {
//                 $data2->sexual = $data2->sx;
//                 unset($data2->sx);
//             }
//
//             $data3['err'] = null;
//
//             if ((!isset($data->email)) && ($data->email == NULL) && (!isset($data->mobile)) && ($data->mobile == NULL))
//                 $data3['err'] .= 'ایمیل یا موبایل الزامی است.<br>';
//
//             if (!$data3['err']) {
//                 unset($data2->format);
//                 $user_id = $data2->user_id;
//                 unset($data2->user_id);
//                 $this->base_model->update('end_users', array('id' => $user_id), $data2);
//                 //$data2['saved'] = true;
//             }
//
//
//             if ($data3['err'])
//                 $this->response($data3);
//             else {
//                 $data3 = array("save" => true);
//                 $this->response($data3);
//             }
//
//
//         }
//     }
//
//     public function request_password()
//     {
//         $data = json_decode(file_get_contents('php://input'));
//         if ($data) {
//             $data2['err'] = null;
//
//             if ((!isset($data->email)) && ($data->email == NULL) && (!isset($data->mobile)) && ($data->mobile == NULL))
//                 $data2['err'] .= 'ایمیل یا موبایل الزامی است.<br>';
//
//             if (!$data2['err']) {
//                 $email  = null;
//                 $mobile = null;
//                 if (isset($data->mobile)) $mobile = $data->mobile;
//                 if (isset($data->email)) $email = $data->email;
//                 $this->password_notify($email, $mobile);
//                 $data2['send'] = true;
//             }
//             $this->response($data2);
//         }
//     }
//
//     function password_notify($mail, $sms)
//     {
//         $code = rand(1000, 9999);
//         $this->session->set_userdata('register_code', $code);
//         $message = "<br><div style='font-size: 17px;font-family: tahoma;' align='center' dir='rtl'>";
//         $message .= "برای تغییر رمز عبور ";
//         $message .= " <a href='" . base_url("index.php/set_password?code=") . $code . "'>اینجا</a> ";
//         $message .= "کلید کنید.<br>";
//         $message .= '<br> کد تایید: ' . $code . "</div>";
//         if ($mail) {
//             $this->session->set_userdata('mail', $mail);
//             $subject = "شادلین";
//             $config  = Array(
//                 'protocol'  => 'smtp',
//                 'smtp_host' => 'mail.shadleen.com',
//                 'smtp_port' => 587,
//                 'smtp_user' => 'support@shadleen.com',
//                 'smtp_pass' => 'D46HI645vV4',
//                 'mailtype'  => 'html',
//                 'charset'   => 'UTF-8'
//             );
//
//
//             $this->load->library('email', $config);
//             $this->email->set_newline("\r\n");
//             $this->email->from('support@shadleen.com', 'شادلین');
//             $this->email->to($mail);
//             $this->email->subject($subject);
//             $this->email->message($message);
//             $this->email->send();
//         }
//         if ($sms) {
//             $this->session->set_userdata('mobile', $sms);
//         }
//
//     }
//
//     public function set_password()
//     {
//         $data = json_decode(file_get_contents('php://input'));
//         if ($data) {
//
//             if (isset($data->code) && isset($data->password)) {
//                 if ($this->session->userdata('register_code') == $data->code) {
//                     $data3['password'] = sha1($data->password);
//                     if (isset($_SESSION['mail'])) {
//                         $this->base_model->update('end_users', array('email' => $_SESSION['mail']), $data3);
//                         $data4['save'] = true;
//                     } else if (isset($_SESSION['mobile'])) {
//                         $this->base_model->update('end_users', array('mobile' => $_SESSION['mobile']), $data3);
//                         $data4['save'] = true;
//                     }
//                     $this->response(array('change_password' => 'true'));
//                 } else {
//                     $data4['err'] = "کد اشتباه است.";
//                 }
//             }
//             unset($_SESSION['mail']);
//             unset($_SESSION['mobile']);
//             unset($_SESSION['register_code']);
//             $this->response($data4);
//
//         }
//     }
//
//     public function set_new_password()
//     {
//         $data = json_decode(file_get_contents('php://input'));
//         if ($data) {
//
//             if (isset($data->userid) && isset($data->oldpassword) && isset($data->newpassword)) {
//
//                 $result = $this->base_model->get_data('end_users', '*', array('id' => $data->userid, 'password' => sha1($data->oldpassword)));
//                 if ($result) {
//                     $data3['password'] = sha1($data->newpassword);
//                     $this->base_model->update('end_users', array('id' => $data->userid), $data3);
//                     $this->response(array('save_password' => 'true'));
//                 }
//
//
//             }
//             $this->response(array("err" => true));
//
//         }
//     }
//
//     public function jsn()
//     {
//         $test = utf8_encode($_POST['content']); // Don't forget the encoding
//         $data = json_decode($test);
//         echo $data->id;
//     }

//     //</editor-fold>

}