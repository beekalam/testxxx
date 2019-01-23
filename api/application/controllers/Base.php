<?php

//@todo add admin verify business users to admin panel
class Base extends CI_Controller
{
    protected $checkAuthorization = true;
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('utils');

        if($this->checkAuthorization) {
            $this->checkAuth();
        }
    }

    private function checkAuth()
    {
        if ($this->session->userdata('login_in') == false) {
            redirect('login');
        }
    }

    public function view($view, $data = array())
    {
        if ($this->session->has_userdata('msg')) {
            $data['msg'] = $this->session->userdata('msg');
            $this->session->unset_userdata("msg");
        } else if ($this->session->has_userdata("error")) {
            $data['error'] = $this->session->userdata('error');
            $this->session->unset_userdata("error");
        }

        if(isset($data["msg"]) || isset($data["error"])){
            // pr($data);
            // pre("some error or some msg is set.");
        }

        $data['footer'] = "By<a href='#'> FANACMP </a>" . "@" . date('Y');
        $data['isadmin'] = $this->is_admin();

        $this->load->view('header', $data);
        $this->load->view($view, $data);
        $this->load->view('footer', $data);
    }

    public function partial_view($view, $data = array())
    {
        $data['isadmin'] = $this->is_admin();

        $this->load->view('partial_layout/header', $data);
        $this->load->view($view, $data);
        $this->load->view('partial_layout/footer', $data);
    }

    public function is_ajax()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest");
    }

    public function is_ajax_post()
    {
        return $this->is_ajax() && $this->is_post_request();
    }

    public function is_post_request()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function is_get_request()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }


    public function allow_post_only()
    {
        if (!$this->is_post_request())
            exit;
    }

    public function allow_ajax_post_only()
    {
        if (!$this->is_ajax_post())
            exit;
    }

    public function check_perm($permname)
    {
        if (check_perm($permname)) return true;

        if ($this->is_ajax()) {
            ejson(array("success" => false, "error" => "شما به ای نعملیت دسترسی ندارید"));
        } else {
            $this->view("no-access");
        }

        return false;
    }

    public function is_admin()
    {
        return isset($_SESSION['isadmin']) && $_SESSION['isadmin'] ?
            true : false;
    }


    public function msg($msg)
    {
        $this->session->set_userdata("msg", $msg);
    }

    protected function build_sql_details()
    {
        $this->load->database();
        $sql_details = array(
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db'   => $this->db->database,
            'host' => $this->db->hostname
        );
        return $sql_details;
    }
}
