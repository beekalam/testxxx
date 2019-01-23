<?php

class Users_model extends CI_Model
{

    public function follows_count($user_id)
    {
        $res = $this->db->query("SELECT count(*) as num from followers where follower_id = " . $user_id)->row(0, "array");
        return $res['num'];
    }

    public function followed_by_count($user_id)
    {
        $res = $this->db->query("SELECT count(*) as num from followers where followee_id = " . $user_id)->row(0, "array");
        return $res['num'];
    }

    public function get_user($user_id)
    {
        $user = $this->db->get_where("users", array("id" => $user_id))->row(0, "array");
        if ($user) {
            $user["full_name"] = $user["name"] . " " . $user["family"];
        }
        return $user;
    }

    public function media_count($user_id)
    {
        $res = $this->db->query("SELECT count(*) as num from posts where user_id =  " . $user_id)->row(0, "array");
        return $res['num'];
    }

    public function search_users($query, $current_user_id = null)
    {
        //
        // $sql = "select users.name as first_name,users.family as family_name,users.user_name,users.gender,users.profile_pic,users.user_type";
        // $sql .= " from users ";
        // $sql .= " where user_name like '%" . str_replace("'", "", $query) . "%'";
        // $users = $this->db->query($sql)->result_array();

        $users = $this->db->select("users.id user_id,users.name as first_name,users.family as family_name,users.user_name,users.gender")
                          ->select("users.profile_pic,users.user_type")
                          ->from("users")
                          ->like('user_name', str_replace("'", "", $query))
                          ->get()
                          ->result_array();

        $follows = [];
        if (!is_null($current_user_id)) {
            $user_ids = array_column($users, "user_id");
            $follows  = $this->db->select("follower_id,followee_id")
                                 ->from("followers")
                                 ->where_in("followee_id", $user_ids)
                                 ->or_where_in("follower_id", $user_ids)
                                 ->get()
                                 ->result_array();
            $follows  = array_map(function ($item) {
                return $item['follower_id'] . "_" . $item['followee_id'];
            }, $follows);
        }
        // $users['follows'] = $follows;
        foreach ($users as &$user) {
            $user['profile_pic_path'] = '';
            if (!empty($user['profile_pic'])) {
                $user['profile_pic_path'] = base_url(USER_PROFILE_IMAGE_PATH . "/" . $user['profile_pic']);
            }
            $user['is_followed_by_you'] = false;
            $user['is_following_me']    = false;
            if (!is_null($current_user_id)) {
                if (in_array($current_user_id . "_" . $user["user_id"], $follows)) {
                    $user['is_followed_by_you'] = true;
                }
                if (in_array($user["user_id"] . "_" . $current_user_id, $follows)) {
                    $user["is_following_me"] = true;
                }
            }
        }

        return $users;
    }
}