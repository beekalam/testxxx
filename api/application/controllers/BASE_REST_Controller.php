<?php
require(APPPATH . '/libraries/REST_Controller.php');

class BASE_REST_Controller extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        // if(strpos($_SERVER['REQUEST_URI'], 'upload') === false)

        if(count($_FILES) == 0)
        {
            log_message("debug","ua: " . $_SERVER['HTTP_USER_AGENT']);
            log_message("debug","ip: " . $_SERVER['REMOTE_ADDR']);
            log_message("debug",$_SERVER['REQUEST_URI']);
            log_message("debug","POST parameters are:" . print_r($_POST,true));
            log_message("debug","GET parameters are:" . print_r($_GET,true));
        }
    }

    protected function resp($success, $data)
    {
        $to_send = array("success" => false, "data" => array());
        if ($success == false) {
            $to_send["success"] = false;
            $to_send["error"]   = $data;
        } else {
            $to_send["success"] = true;
            $to_send["data"]    = $data;
        }

        return $this->response($to_send);
    }

    protected function make_response($success, $data)
    {
        return array("success" => $success, "data" => $data);
    }

    protected function app_settings(){
        $settings = $this->db->get("app_settings")->result_array();
        $ret = array();
        foreach($settings as $s){
            $ret[$s["name"]] = $s["value"];
        }
        return $ret;
    }


    protected function read_params()
    {
        $this->load->helper("utils");
        $params = json_decode(file_get_contents('php://input'));
        $params = object_to_array($params);
        return $params;
    }

    // protected function extract_first_last_name(){
    //     if(!isset($_POST['fullname'])){
    //         return;
    //     }
    //
    //     $name = explode(' ', $_POST['fullname']);
    //     unset($_POST['fullname']);
    //     $_POST['firstname'] = "";
    //     $_POST['lastname'] = "";
    //     if (count($name) == 2) {
    //         $_POST['firstname'] = $name[0];
    //         $_POST['lastname']  = $name[1];
    //     } else if (count($name) > 2) {
    //         $_POST['firstname'] = $name[0];
    //         unset($name[0]);
    //         $_POST['lastname'] = join(' ', $name);
    //     }
    //
    // }

}
