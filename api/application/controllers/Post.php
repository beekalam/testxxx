<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// use Ramsey\Uuid\Uuid;
// use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

require __DIR__ . "/BASE_REST_Controller.php";
require APPPATH . "/third_party/UUID.php";

class Post extends BASE_REST_Controller
{
    public function home_posts()
    {
        $this->load->model("Posts_model");
        $user_id = $this->input->get("user_id");
        $page    = $this->input->get("page");
        $offset  = $this->input->get("offset");

        $posts = $this->Posts_model->posts_by_user($user_id, $page, $offset);

        foreach ($posts as &$post) {
            // add image and video absolute paths
            $post['file_absolute_path'] = "";
            if (!empty($post['name'])) {
                if ($post['file_type'] == 'PHOTO') {
                    $post['file_absolute_path'] = base_url(POST_PHOTOS_PATH . "/" . $post['name']);
                } else {
                    $post['file_absolute_path'] = base_url(POST_VIDEO_PATH . "/" . $post['name']);
                }
            }

            // $post['comments'] = $this->Posts_model->post_comments($post['id']);
            //@fixme: optimize query
            $post['comments_count']  = count($this->Posts_model->post_comments($post['id']));
            $post['likes_count']     = count($this->Posts_model->post_likes($post['id']));
            $post['liked']           = $this->Posts_model->has_liked($post['id'], $user_id);
            $post['saved']           = $this->Posts_model->has_saved($post['id'], $user_id);
            $post['big_liked']       = $this->Posts_model->has_big_liked($post['id'], $user_id);
            $post['big_likes_count'] = count($this->Posts_model->post_comments($post['id']));
        }

        $categories = $this->db->select("id,cat_name,pic")
                               ->from("category")
                               ->get()
                               ->result_array();
        foreach ($categories as &$cat) {
            $cat['pic_path'] = "";
            if (!empty($cat['pic'])) {
                $cat['pic_path'] = base_url(CATEGORY_IMAGE_PATH . "/" . $cat['pic']);
            }
        }

        for ($i = 0; $i < count($posts); $i++) {
            $posts[$i]['categories'] = array();
            if ($i != 0 && $i % 4 == 0) {
                $posts[$i]['categories'] = $categories;
            }
        }
        $this->resp(true, $posts);
    }

    public function search()
    {
        $user_id = $this->input->post('user_id', true);
        $page    = $this->input->get("page");
        $offset  = $this->input->get("offset");
        $this->load->model("Posts_model");
        // $ret   = array("suggestions"       => array(),
        //                "text_suggestions"  => array("تی شرت مردانه", "کلید سازی", "خیاطی"),
        //                "posts"             => array(),
        //                "admin_suggestions" => array());
        $posts             = $this->Posts_model->search_posts($user_id, $page, $offset);
        $admin_suggestions = [];
        while (count($admin_suggestions) < count($posts)) {
            foreach ($posts as $p) {
                if ($p['file_type'] == "PHOTO")
                    $admin_suggestions[] = $p;
            }
        }

        for ($i = 0; $i < 10; $i++) {
            $suggestions[] = $posts[array_rand($posts)];
        }

        foreach ($posts as &$post) {
            $post['text_suggestions'] = [];
            $post['suggestions']      = [];
            $post['admin_suggestion'] = $admin_suggestions[array_rand($admin_suggestions)];
            $post['likes_count']      = count($this->Posts_model->post_likes($post['id']));
            $post['big_likes_count']  = count($this->Posts_model->post_comments($post['id']));
        }

        if ($offset == 1 && isset($posts[0])) {
            $posts[0]["text_suggestions"] = array("تی شرت مردانه", "کلید سازی", "خیاطی");
            $posts[0]['suggestions']      = $suggestions;
        }
        return $this->resp(true, $posts);
    }

    public function get_post()
    {

        $this->load->model("Posts_model");

        $post_id = $this->input->get("post_id");
        $post    = $this->Posts_model->post_by_id($post_id);

        if (!is_null($post)) {
            $post["media"]   = $this->Posts_model->post_photos($post["id"],true);
            $post["comments"]  = $this->Posts_model->post_comments($post['id']);

            // add user profile images
            if (!empty($psot['comments'])) {
                foreach ($post['comments'] as &$pc) {
                    if (!empty($pc['image']))
                        $pc['absolute_image_path'] = base_url(USER_PROFILE_IMAGE_PATH . '/' . $pc['image']);
                }
            }

            //$post["hashtags"]       = $this->Posts_model->post_tags($post_id);
            $post['comments_count'] = count($this->Posts_model->post_comments($post['id']));
            $post['likes_count']    = count($this->Posts_model->post_likes($post['id']));
            // $post['liked']           = $this->Posts_model->has_liked($post['id'], $user_id);
            // $post['saved']           = $this->Posts_model->has_saved($post['id'], $user_id);
            // $post['big_liked']       = $this->Posts_model->has_big_liked($post['id'], $user_id);
            $post['big_likes_count'] = count($this->Posts_model->post_comments($post['id']));
            return $this->resp(true, $post);
        }

        return $this->resp(false, "Post not found");
    }

    public function add_photo_post()
    {
        $this->load->helper("image_helper");
        $user_id           = $this->input->post("user_id");
        $caption           = $this->input->post("caption");
        $title             = $this->input->post("title");
        $comments_disabled = $this->input->post("comments_disabled");
        $photo             = $this->input->post("photo");
        $hashtags          = $this->input->post("hashtags");
        $hashtags          = $this->hashtag_ids(explode(',', $hashtags));

        if (!valid_base64_imagestring($_POST['photo'])) {
            return $this->resp(false, "file is not a photo.");
        }

        $this->db->trans_start();
        $this->db->insert("posts", compact("title", "caption", "comments_disabled", "photos", "user_id"));
        $post_id    = $this->db->insert_id();
        $photo_name = base64_imagestring_save(urldecode($_POST['photo']), FCPATH . POST_PHOTOS_PATH, UUID::v4());
        $this->db->insert("photos", array("post_id"       => $post_id,
                                          "user_id"       => $user_id,
                                          "name"          => $photo_name,
                                          "file_type"     => "PHOTO",
                                          "file_uploaded" => 1));
        //insert hashtags
        foreach ($hashtags as $v) {
            $this->db->insert("post_hashtags", array("post_id"    => $post_id,
                                                     "hashtag_id" => $v));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return $this->resp(false, "There was a database error.");
        }

        return $this->resp(true, array());
    }

    // inserts hashtags to hashtag table if they are not already there
    private function hashtag_ids($hashtags = array())
    {
        $ret = array();
        foreach ($hashtags as $hashtag) {
            $res = $this->db->get_where("hashtags", compact("hashtag"))->row(0, "array");
            if (is_null($res)) {
                $this->db->insert("hashtags", compact("hashtag"));
                // $ret[] = array($hashtag => $this->db->insert_id());
                $ret [] = $this->db->insert_id();
            } else {
                // $ret[] = array($hashtag => $res['id']);
                $ret[] = $res['id'];
            }
        }
        return $ret;
    }

    public function add_text_post()
    {
        $this->load->helper("image_helper");
        $user_id           = $this->input->post("user_id");
        $caption           = $this->input->post("caption");
        $comments_disabled = $this->input->post("comments_disabled");
        $hashtags          = $this->input->post("hashtags");
        $title             = $this->input->post("title");
        $hashtags          = $this->hashtag_ids(explode(',', $hashtags));
        $post_type         = "POST_TYPE_TEXT";
        $this->db->trans_start();
        $this->db->insert("posts", compact("title", "caption", "comments_disabled", "user_id", "post_type"));
        $post_id = $this->db->insert_id();
        //insert hashtags
        foreach ($hashtags as $v) {
            $this->db->insert("post_hashtags", array("post_id"    => $post_id,
                                                     "hashtag_id" => $v));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() !== false) {
            return $this->resp(true, array());
        }

        return $this->resp(false, "");
    }

    public function add_video_post()
    {
        $this->load->helper("image_helper");
        $user_id           = $this->input->post("user_id");
        $caption           = $this->input->post("caption");
        $comments_disabled = $this->input->post("comments_disabled");
        $hashtags          = $this->input->post("hashtags");
        $title             = $this->input->post("title");
        $hashtags          = $this->hashtag_ids(explode(',', $hashtags));
        $this->db->insert("posts", compact("title", "caption", "comments_disabled", "photos", "user_id"));
        $post_id = $this->db->insert_id();
        $this->db->trans_start();
        $this->db->insert("photos", array("post_id"       => $post_id,
                                          "user_id"       => $user_id,
                                          "file_type"     => "VIDEO",
                                          "file_uploaded" => 0,
                                          "name"          => ""));
        $file_id = $this->db->insert_id();
        //insert hashtags
        foreach ($hashtags as $v) {
            $this->db->insert("post_hashtags", array("post_id"    => $post_id,
                                                     "hashtag_id" => $v));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() !== false) {
            $upload_url = base_url("post/upload_video?id=" . $file_id);
            return $this->resp(true, compact("upload_url"));
        }

        return $this->resp(false, "");
    }

    public function upload_video()
    {
        $file_id = $this->input->get("id");
        $row     = $this->db->get_where("photos", array("id" => $file_id))->row(0, "array");

        if (is_null($row)) return $this->resp(false, "invalid id.");
        if (!isset($_FILES['to_upload'])) return $this->resp(false, "to_upload file not found.");

        $file_name = $_FILES['to_upload']['name'];
        $file_size = $_FILES['to_upload']['size'];
        $file_type = $_FILES['to_upload']['type'];
        $file_tmp  = $_FILES['to_upload']['tmp_name'];
        $extension = explode('.', $_FILES['to_upload']['name'])[1];

        // if (in_array($file_type, array("video/mp4")) === false) return $this->resp(false, "Invalid file type.");
        if ($file_size > 50 * 1024 * 1024) return $this->resp(false, "File sizes up to 50 Mb is accepted.");

        $save_file_name = UUID::v4() . "." . $extension;
        move_uploaded_file($file_tmp, FCPATH . POST_VIDEO_PATH . DIRECTORY_SEPARATOR . $save_file_name);
        $this->db->where("id", $file_id)
                 ->set("file_uploaded", 1)
                 ->set("name", $save_file_name)
                 ->update("photos");

        return $this->resp(true, array());
    }

    public function save_post()
    {
        $post_id = $this->input->post("post_id");
        $user_id = $this->input->post("user_id");
        if (is_null($user_id) || is_null($post_id))
            return $this->resp(false, "invalid inputs: post_id: $post_id, user_id:$user_id");

        $row = $this->db->get_where("saves", compact("user_id", "post_id"))->row(0, "array");
        if (!is_null($row))
            return $this->resp(false, "Post already liked by user.");

        $res = $this->db->insert("saves", compact("user_id", "post_id"));
        return $this->resp($res, "");
    }

    public function unsave_post()
    {
        $post_id = $this->input->post("post_id");
        $user_id = $this->input->post("user_id");
        $res     = $this->db->where(compact("user_id", "post_id"))->delete("saves");
        return $this->resp($res, "");
    }

    public function add_comment()
    {
        $post_id = $this->input->post("post_id");
        $user_id = $this->input->post("user_id");
        $comment = $this->input->post("comment");

        $res = $this->db->insert("comments", compact("user_id", "post_id", "comment"));
        return $this->resp($res, "");
    }

    public function comments()
    {
        $post_id = $this->input->get('post_id', true);
        $res     = $this->db->select("comments.*")
                            ->select("users.user_name,users.profile_pic")
                            ->from("comments")
                            ->join("users", "users.id = comments.user_id", "left")
                            ->where("comments.post_id", $post_id)
                            ->get()
                            ->result_array();

        foreach ($res as &$r) {
            $r['profile_pic_path'] = '';
            if (!empty($r['profile_pic'])) {
                $r['profile_pic_path'] = base_url(USER_PROFILE_IMAGE_PATH . "/" . $r['profile_pic']);
            }
        }

        return $this->resp(true, $res);
    }

    public function likes()
    {
        $post_id = $this->input->get("post_id");
        // $like_count   = $this->db->query("select count(*) as like_count from likes where post_id=" . $post_id)->row(0,"array")['like_count'];
        $res = $this->db->select("likes.*,users.name,users.family,users.profile_pic")
                        ->from("likes")
                        ->where("likes.post_id", $post_id)
                        ->join("users", "users.id = likes.user_id", "left")
                        ->get()
                        ->result_array();

        // add user profile picture
        foreach ($res as $liked_post) {
            if (!empty($liked_post['profile_pic'])) {
                $liked_post['absolute_profile_pic'] = base_url(USER_PROFILE_IMAGE_PATH . "/" . $liked_post['profile_pic']);
            }
        }

        return $this->resp(true, $res);
    }

    public function like_post()
    {
        $post_id   = $this->input->post("post_id");
        $user_id   = $this->input->post("user_id");
        $has_liked = $this->db->get_where("likes", compact("user_id", "post_id"))->result_array();
        if (count($has_liked) > 0) {
            return $this->resp(false, "already liked");
        }

        $res = $this->db->insert("likes", compact("user_id", "post_id"));
        return $this->resp($res, "");
    }

    public function unlike_post()
    {
        $post_id = $this->input->post("post_id");
        $user_id = $this->input->post("user_id");
        $res     = $this->db->where(compact("user_id", "post_id"))->delete("likes");
        return $this->resp($res, "");
    }

    public function big_like_post()
    {
        $post_id   = $this->input->post("post_id");
        $user_id   = $this->input->post("user_id");
        $has_liked = $this->db->get_where("big_likes", compact("user_id", "post_id"))->result_array();
        if (count($has_liked) > 0) {
            return $this->resp(false, "already liked");
        }

        $res = $this->db->insert("big_likes", compact("user_id", "post_id"));
        return $this->resp($res, "");
    }

    public function big_unlike_post()
    {
        $post_id = $this->input->post("post_id");
        $user_id = $this->input->post("user_id");
        $res     = $this->db->where(compact("user_id", "post_id"))->delete("big_likes");
        return $this->resp($res, "");
    }


    public function freeshop()
    {
        $data = array(
            "category"    => "cat 1",
            "created_at"  => "2017-05-06",
            "price"       => "27500",
            "province_id" => "1",
            "province"    => "Shiraz",
            "zone"        => "zand",
            "zone_id"     => "1"
        );

        $this->resp(true, $data);
    }
}
