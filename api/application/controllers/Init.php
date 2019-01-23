<?php
set_time_limit(60);
defined('BASEPATH') OR exit('No direct script access allowed');
require __DIR__ . "/BASE_REST_Controller.php";

class Init extends BASE_REST_Controller
{
    public function create()
    {
        $this->deinit();
        $user_id = 13;
        foreach (range(1, 100) as $k) {
            $arr = array(
                "caption"           => "caption " . $k,
                "comments_disabled" => 0,
                "user_id"           => $user_id
            );
            $this->db->insert("posts", $arr);
        }

        $posts = $this->db->get("posts")->result_array();
        $this->create_hash_table();
        $hashtags = $this->db->get("hashtags")->result_array();
        foreach ($posts as $post) {
            $p = array(
                "caption"  => "",
                "latitude" => "",
                "user_id"  => $post['user_id'],
                "post_id"  => $post['id'],
                "name"     => "name " . $post['id']
            );
            $this->db->insert("photos", $p);
        }

        foreach ($posts as $post) {
            $h = array(
                "post_id"    => $post['id'],
                "hashtag_id" => $hashtags[0]['id']
            );
            $this->db->insert("post_hashtags", $h);
        }

        foreach ($posts as $post) {
            $l = array(
                "user_id" => $user_id,
                "post_id" => $post['id']
            );
            $this->db->insert("likes", $l);
        }
        $i = 0;
        foreach ($posts as $post) {
            $s = array(
                "user_id" => $user_id,
                "post_id" => $post['id']
            );
            $this->db->insert("saves", $s);
        }

        foreach ($posts as $post) {
            $s = array(
                "user_id" => $user_id,
                "post_id" => $post['id'],
                "comment" => "comment " . $post['id']
            );
            $this->db->insert("comments", $s);
        }

    }

    private function create_hash_table()
    {
        foreach (range(1, 10) as $k) {
            $this->db->insert("hashtags", array("hashtag" => "hashtag_" . $k));
        }
    }

    public function deinit()
    {
        $this->db->where("0 = 0")->delete("posts");
        $this->db->where("0 = 0")->delete("photos");
        $this->db->where("0 = 0")->delete("hashtags");
        $this->db->where("0 = 0")->delete("post_hashtags");
    }
}