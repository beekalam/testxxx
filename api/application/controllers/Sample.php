<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require __DIR__ . "/BASE_REST_Controller.php";


/**
 * Class Main
 *
 * @package App\Http\Controllers\Api
 */
class Sample extends BASE_REST_Controller
{


    public function test()
    {
        $user_id = 31;
        $bets    = $this->db->select("id,bet_title,bet_description,bet_point,bet_event_date,bet_event_time,bet_start_date")
                            ->select('text_to_show_a,text_to_show_b,image_to_show_a,image_to_show_b')
                            ->from('bets')
                            ->where('bet_is_active=1')
                            ->get()
                            ->result_array();
        $res     = array();
        foreach ($bets as $bet) {
            $bet_select_options = $this->db->select("id,option_text")
                                           ->from('bet_select_options')
                                           ->where('bet_id', $bet['id'])
                                           ->get()
                                           ->result_array();

            $res[] = array("bet_info" => $bet, "bet_options" => $bet_select_options);
        }

        if (!is_null($user_id)) {
            //get list of bets has participated in.
            echo $user_id;
            $user_bets = $this->db->select("bet_id")
                                  ->from('user_bets')
                                  ->where("user_id", $user_id)
                                  ->get()
                                  ->result_array();

            $user_bets = array_column($user_bets, "bet_id");

            // add flag to result if user can participate in bet.
            foreach ($res as &$item) {
                if (in_array($item["bet_info"]["id"], $user_bets)) {
                    $item["bet_info"]["can_participate"] = false;
                } else {
                    $item["bet_info"]["can_participate"] = true;
                }
            }
        }

        // echo "<pre>";
        // var_dump($res);
        // echo "</pre>";
        return $res;
    }


    public function list_jobs()
    {
        $this->load->helper("utils");
        $res = $this->db->select('job_ads.*,careers.career,education_majors.major,education_degrees.degree,karjoo.firstname,karjoo.lastname')
                        ->from("job_ads")
                        ->join("careers", "job_ads.career_id=careers.id")
                        ->join("education_majors", "job_ads.major_id=education_majors.id", "LEFT")
                        ->join("education_degrees", "job_ads.degree_id=education_degrees.id", "LEFT")
                        ->join("karjoo","job_ads.user_id=karjoo.id","LEFT")
                        ->where('admin_approved', 1)
                        ->order_by("created_at", "desc")
                        ->get()
                        ->result_array();

        pr($res);
    }

}