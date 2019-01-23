<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require __DIR__ . "/BASE_REST_Controller.php";

class Wallet extends BASE_REST_Controller
{
    public function buy()
    {
        list($valid, $error) = $this->validate_buy($_POST);
        if (!$valid) {
            $this->resp($valid, $error);
        }

        $customer_id = $this->input->post('customer_id');
        $amount      = $this->input->post("amount");
        $this->resp(true, array("payment_url" => base_url("ui/bank/buy_credit?uid=" . $customer_id . "&amount=" . $amount)));
    }

    private function validate_buy($params)
    {
        foreach (array("customer_id", "amount") as $p) {
            if (!isset($params[$p])) {
                return array(false, $p . " not provided.");
            }
            if (empty($params[$p])) {
                return array(false, $p . " is empty.");
            }
        }

        return array(true, "");
    }

    public function transaction_history()
    {
        if (isset($_GET['customer_id'])) {
            $res = $this->db->select('id,amount,created_at')
                            ->from('wallet_transactions')
                            ->where('customer_id', $this->input->get('customer_id'))
                            ->order_by('id', 'desc')
                            ->get()
                            ->result_array();
            return $this->resp(true, $res);
        }

        $this->resp(false, "customer_id not provided");
    }

}
