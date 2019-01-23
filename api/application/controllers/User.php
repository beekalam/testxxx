<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require __DIR__ . "/BASE_REST_Controller.php";
require APPPATH . "/third_party/UUID.php";

class User extends BASE_REST_Controller
{
    // user profile
    public function profile()
    {
        $this->load->model("Users_model");
        $user_id = $this->input->get("user_id");
        // $user    = $this->Users_model->get_user($user_id);
        $fields = array("id", "name", "family", "user_name", "gender", "mobile", "biography",
            "user_type", "wall_image", "profile_pic", "province_id","user_to_business_request");
        $user   = $this->db->select($fields)
                           ->from("users")
                           ->where("id", $user_id)
                           ->get()
                           ->row(0, "array");


        if (is_array($user)) {
            // $user["counts"]                = array();
            // $user['counts']['follows']     = $this->Users_model->follows_count($user['id']);
            // $user['counts']['followed_by'] = $this->Users_model->followed_by_count($user['id']);
            // $user['counts']['media']       = $this->Users_model->media_count($user['id']);
            $user["wall_image_path"]  = empty($user["wall_image"]) ? "" : base_url(USER_WALL_IMAGE_PATH . "/" . $user["wall_image"]);
            $user["profile_pic_path"] = empty($user["profile_pic"]) ? "" : base_url(USER_PROFILE_IMAGE_PATH . "/" . $user["profile_pic"]);
            return $this->resp(true, $user);
        }
        $this->resp(false, "User not found.");
    }

    public function likes()
    {
        $user_id = $this->input->get("user_id");
        $likes   = $this->db->select("likes.user_id,likes.post_id,posts.comments_disabled,posts.caption")
                            ->select("photos.name")
                            ->from("likes")
                            ->join("posts", "likes.post_id = posts.id", "left")
                            ->join("photos", "photos.post_id = posts.id", "left")
                            ->where("likes.user_id", $user_id)
                            ->get()
                            ->result_array();
        if ($likes) {
            // add photo urls
            foreach ($likes as &$like) {
                if (!empty($like['name'])) {
                    $like['absolute_image_path'] = base_url(POST_PHOTOS_PATH . "/" . $like['name']);
                }
            }
            return $this->resp(true, $likes);
        }

        $this->resp(false, "Likes not found");
    }

    public function saves()
    {
        $this->load->model("Posts_model");
        $user_id = $this->input->get("user_id", true);
        $saves   = $this->Posts_model->saved_posts($user_id);
        if ($saves) {
            return $this->resp(true, $saves);
        }

        $this->resp(false, "Likes not found");
    }

    public function following()
    {
        $user_id   = $this->input->get("user_id", true);
        $followers = $this->db->select("users.id,users.name,users.family,users.user_name")
                              ->from("followers")
                              ->where("follower_id", $user_id)
                              ->join("users", "followers.followee_id = users.id")
                              ->get()
                              ->result_array();

        return $this->resp(true, $followers);

        // $this->resp(false, "no followers  found.");
    }

    public function followers()
    {
        $user_id   = $this->input->get("user_id");
        $followers = $this->db->select("users.id,users.name,users.family,users.user_name")
                              ->from("followers")
                              ->where("followee_id", $user_id)
                              ->join("users", "followers.follower_id = users.id")
                              ->get()
                              ->result_array();
        if ($followers) {
            return $this->resp(true, $followers);
        }

        $this->resp(false, "Followers not found.");
    }

    public function follow()
    {
        $follower_id = $this->input->post("user_id");
        $followee_id = $this->input->post("followee_id");
        $res         = $this->db->get_where("followers", compact("follower_id", "followee_id"))->row(0, "array");
        if (is_null($res)) {
            $res = $this->db->insert("followers", compact("follower_id", "followee_id"));
            return $this->resp($res, "");
        }

        return $this->resp(true, array());
    }

    public function unfollow()
    {
        $follower_id = $this->input->post("user_id");
        $followee_id = $this->input->post("followee_id");

        $res = $this->db->where("follower_id", $follower_id)
                        ->where("followee_id", $followee_id)
                        ->get("followers")
                        ->row(0, "array");

        if (!is_null($res)) {
            $res = $this->db->where(compact("follower_id", "followee_id"))->delete("followers");
            return $this->resp($res, "");
        }

        return $this->resp(false, "User with {$follower_id} is not following user with id of {$followee_id}");
    }

    public function update_profile()
    {
        if (!isset($_POST['mobile']) || empty($_POST['mobile'])) return $this->resp(false, "mobile is empty or not set");

        $mobile      = $this->input->post("mobile", true);
        $gender      = $this->input->post("gender", true);
        $province_id = $this->input->post("province_id", true);
        $username    = $this->input->post("username", true);
        $biography   = $this->input->post("biography", true);
        $birth_date  = $this->input->post("birth_date", true);

        $user = $this->db->get_where("users", array("mobile" => $mobile))->row(0, "array");
        if (is_null($user)) return $this->resp(false, "user not found");

        // set wall image if any
        if (isset($_FILES['wall_image'])) {

            $file_name      = $_FILES['wall_image']['name'];
            $file_size      = $_FILES['wall_image']['size'];
            $file_type      = $_FILES['wall_image']['type'];
            $file_tmp       = $_FILES['wall_image']['tmp_name'];
            $extension      = explode('.', $_FILES['wall_image']['name'])[1];
            $save_file_name = UUID::v4() . "." . $extension;
            $move           = move_uploaded_file($file_tmp, FCPATH . USER_WALL_IMAGE_PATH . DIRECTORY_SEPARATOR . $save_file_name);
            if ($move) {
                @unlink(FCPATH . USER_WALL_IMAGE_PATH . DIRECTORY_SEPARATOR . $user['wall_image']);
                $this->db->set("wall_image", $save_file_name)
                         ->where("mobile", $mobile)
                         ->update("users");
            }
        }
        // set profile pic if any
        if (isset($_FILES['profile_pic'])) {
            $file_name      = $_FILES['profile_pic']['name'];
            $file_size      = $_FILES['profile_pic']['size'];
            $file_type      = $_FILES['profile_pic']['type'];
            $file_tmp       = $_FILES['profile_pic']['tmp_name'];
            $extension      = explode('.', $_FILES['profile_pic']['name'])[1];
            $save_file_name = UUID::v4() . "." . $extension;
            $move           = move_uploaded_file($file_tmp, FCPATH . USER_PROFILE_IMAGE_PATH . DIRECTORY_SEPARATOR . $save_file_name);
            if ($move) {
                @unlink(FCPATH . USER_PROFILE_IMAGE_PATH . DIRECTORY_SEPARATOR . $user['profile_pic']);
                $this->db->set("profile_pic", $save_file_name)
                         ->where("mobile", $mobile)
                         ->update("users");
            }
        }

        if (!is_null($gender))
            $this->db->set("gender", $gender);
        if (!is_null($province_id))
            $this->db->set("province_id", $province_id);
        if (!is_null($username))
            $this->db->set("user_name", $username);
        if (!is_null($biography))
            $this->db->set("biography", $biography);
        if (!is_null($birth_date))
            $this->db->set("birth_date", $birth_date);
        $res = $this->db->where("mobile", $mobile)
                        ->update("users");
        // $res = $this->db->set("user_name", $username)
        //                 ->set("gender", $gender)
        //                 ->set("province_id", $province_id)
        //                 ->where("mobile", $mobile)
        //                 ->update("users");

        $this->resp($res, array());
    }

    public function switch_to_business()
    {
        $valid = $this->validate_switch_to_business($_POST);

        if ($valid['res'] == false) {
            return $this->resp(false, $valid['error']);
        }

        $tel                      = $this->input->post("tel", true);
        $email                    = $this->input->post("email", true);
        $business_name            = $this->input->post("business_name", true);
        $user_to_business_request = "REQUESTED";
        $this->db->set("tel", $tel)
                 ->set("email", $email)
                 ->set("business_name", $business_name)
                 ->set("user_to_business_request", $user_to_business_request)
                 ->where("id", $this->input->post("user_id"))
                 ->update("users");

        return $this->resp(true, array());
    }

    public function validate_switch_to_business($params = array())
    {
        $ret  = array("res" => false, "error" => "");
        $keys = array("tel", "email", "business_name");
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

        $ret["res"] = true;
        return $ret;
    }

    public function search()
    {
        $this->load->model("Users_model");
        $query           = $this->input->get("query", true);
        $current_user_id = $this->input->get("user_id", true);
        $res             = $this->Users_model->search_users($query, $current_user_id);
        $this->resp(true, $res);
    }
}



