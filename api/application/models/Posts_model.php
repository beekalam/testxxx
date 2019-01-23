<?php

class Posts_model extends CI_Model
{
    public function posts_by_user($user_id, $page, $offset)
    {
        $this->db->select("posts.*")
                 ->select("photos.name,photos.file_type,photos.file_uploaded")
                 ->select("users.id as user_id,users.user_name,users.image,users.name as first_name,users.family as family_name")
                 ->select("users.wall_image,users.biography")
                 ->from("posts")
                 ->where("posts.user_id", $user_id)
                 ->join("photos", "posts.id = photos.post_id", "left", "left")
                 ->join('users', 'posts.user_id = users.id', "left");
        // ->where("photos.file_uploaded",1);

        $this->db->limit($page, $offset * $page);
        $this->db->order_by("id", "desc");
        $posts = $this->db->get()->result_array();

        foreach ($posts as &$r) {
            $r['profile_image_absolute_path'] = base_url(USER_PROFILE_IMAGE_PATH . "/" . $r['image']);
        }
        return $posts;
    }

    public function search_posts($user_id, $page, $offset)
    {
        $this->db->select("posts.*")
                 ->select("photos.name,photos.file_type,photos.file_uploaded")
                 ->select("users.id as user_id,users.user_name,users.profile_pic,users.name as first_name,users.family as family_name")
                 ->select("users.wall_image,users.biography")
                 ->from("posts")
                 ->join("photos", "posts.id = photos.post_id", "left")
                 ->join('users', 'posts.user_id = users.id', "left");
        // ->where("photos.file_uploaded",1);

        $this->db->limit($page, $offset * $page);
        $this->db->order_by("id", "desc");
        $posts = $this->db->get()->result_array();


        foreach ($posts as &$post) {
            $post['profile_image_absolute_path'] = '';
            if (!empty($post['profile_pic'])) {
                $post['profile_image_absolute_path'] = base_url(USER_PROFILE_IMAGE_PATH . "/" . $post['profile_pic']);
            }

            $post['file_absolute_path'] = "";
            if (!empty($post['name'])) {
                if ($post['file_type'] == 'PHOTO') {
                    $post['file_absolute_path'] = base_url(POST_PHOTOS_PATH . "/" . $post['name']);
                } else {
                    $post['file_absolute_path'] = base_url(POST_VIDEO_PATH . "/" . $post['name']);
                }
            }
        }
        // var_dump($posts);
        return $posts;
    }

    public function saved_posts($user_id)
    {
        $saves = $this->db->select("saves.user_id,saves.post_id")
                          ->select("photos.name,photos.file_type")
                          ->select("posts.comments_disabled,posts.caption,posts.title")
                          ->from("saves")
                          ->join("posts", "saves.post_id = posts.id", "left")
                          ->join("photos", "photos.post_id = posts.id", "left")
                          ->where("saves.user_id", $user_id)
                          ->get()
                          ->result_array();

        foreach ($saves as &$save) {
            $save['absolute_image_path'] = '';
            if (!empty($save['name'])) {
                $save['absolute_image_path'] = base_url(POST_PHOTOS_PATH . "/" . $save['name']);
            }
        }
        return $saves;
    }

    public function post_comments($post_id)
    {
        $res = $this->db->select("comments.id, comments.comment, comments.created_at")
                        ->select("users.name, users.family, users.user_name, users.image")
                        ->from("comments")
                        ->where("comments.post_id", $post_id)
                        ->join("users", 'users.id = comments.user_id', 'left')
                        ->order_by("id", "desc")
                        ->get()
                        ->result_array();

        foreach ($res as &$r) {
            $r['image_absolute_path'] = base_url(USER_PROFILE_IMAGE_PATH . "/" . $r['image']);
        }
        return $res;
    }

    public function post_by_id($post_id)
    {
        return $this->db->select("posts.*")
                        ->from("posts")
                        ->where("posts.id", $post_id)
                        ->get()
                        ->row(0, "array");
    }

    public function post_photos($post_id, $ret_file_only = false)
    {
        $res = $this->db->get_where("photos", array("post_id" => $post_id))->result_array();
        $ret = array();
        foreach ($res as &$row) {
            if ($row['file_type'] == 'PHOTO')
                $row['file_path'] = base_url(POST_PHOTOS_PATH . "/" . $row['name']);
            else
                $row['file_path'] = base_url(POST_VIDEO_PATH . "/" . $row['name']);
            if ($ret_file_only) {
                $ret[] = array('file_path'  => $row['file_path'],
                               "file_type"  => $row['file_type'],
                               "created_at" => $row['created_at']);
            }
        }
        if ($ret_file_only)
            return $ret;
        else
            return $ret;
    }

    public function post_tags($post_id)
    {
        return $this->db->select("hashtags.id,hashtags.hashtag")
                        ->from("post_hashtags")
                        ->where("post_hashtags.id", $post_id)
                        ->join("hashtags", "post_hashtags.hashtag_id = hashtags.id", "left")
                        ->get()
                        ->result_array();
    }

    public function has_liked($post_id, $user_id)
    {
        $res = $this->db->get_where("likes", array("user_id" => $user_id, "post_id" => $post_id))->result_array();
        return count($res) > 0;
    }

    public function has_saved($post_id, $user_id)
    {
        $res = $this->db->get_where("saves", array("user_id" => $user_id, "post_id" => $post_id))->result_array();
        return count($res) > 0;
    }

    public function post_likes($post_id)
    {
        return $this->db->select("likes.*")
                        ->from("likes")
                        ->where("post_id", $post_id)
                        ->get()
                        ->result_array();
    }

    public function post_big_likes($post_id)
    {
        //@todo does not seem to be valid.
        return $this->db->select("big_likes.*")
                        ->from("likes")
                        ->where("post_id", $post_id)
                        ->get()
                        ->result_array();
    }

    public function has_big_liked($post_id, $user_id)
    {
        return is_null($this->db->select("big_likes.*")
                                ->from("big_likes")
                                ->where(compact("post_id", "user_id"))
                                ->get()
                                ->row_array()) ? false : true;
    }
}