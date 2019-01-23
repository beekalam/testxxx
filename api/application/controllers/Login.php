<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// require_once(APPPATH . 'helpers/perms_map.php');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->helper("utils");
        $this->load->database();
    }

    public function index()
    {
        if ($this->session->userdata('login_in') ==  true) {
            redirect('/ui/dashboard');
        }

        if ($_POST) {
            $user_name = $this->input->post('username');
            $password = sha1($this->input->post('password'));
            $row      = $this->db->get_where("users", compact("user_name", "password"))->row(0, "array");

            if (!empty($row)) {
                $this->session->set_userdata('login_in', true);
                if ($row["isadmin"] == "1")
                    $this->session->set_userdata('isadmin', true);
                else
                    $this->session->set_userdata('isadmin', false);

                $this->session->set_userdata('name', $row["name"] . " " . $row["family"]);
                $this->session->set_userdata('userid', $row["id"]);

                // $this->session->set_userdata("perms",$perms);
                // $perms = $this->Users_model->get_user_permissions($row["role"]);

                // $this->session->set_userdata("perms", $perms);

                $this->session->set_userdata("profile_img", val($row['image'], base_url() . "/assets/layouts/layout2/img/avatar.png"));
                redirect('ui/dashboard');
            } else {
                redirect('login');
            }
        }
        $data['footer'] = "By<a href='#'> FANACMP </a>" . "@" . date('Y');
        $this->load->view('login', $data);
    }

    public function logout()
    {
        $this->session->set_userdata('login_in', false);
        $this->session->set_userdata('isadmin', false);
        $this->session->unset_userdata("userid");
        $this->session->sess_destroy();
        redirect('login');
    }
}
