<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/Base.php");

class Bank extends Base
{

    function __construct()
    {
        $this->checkAuthorization = false;
        parent::__construct();
        // $this->load->model("Users_model");
        // $this->load->model("Customers_model");
        // $this->load->model("Settings_model");
        $this->load->library('Jalali_date');
    }

    public function index()
    {
        echo "......";
    }

    public function pay()
    {
        $code           = $this->input->get('code');
        $id             = $this->input->get('id');
        // $payment_method = $this->input->get('payment_method');
        $order          = $this->db->get_where("orders", compact("id", "code"))
                                   ->row(0, "array");
        if(is_null($order)){
            return $this->resp(false,"Order not found");
        }

        if (!is_null($order)) {
            // if this is order is already paid.
            if ($order['order_status'] == 'ORDER_PAID') {
                echo "این سفارش قبلا پرداخت شده است.";
                return;
            }
            // order completed
            $this->db->set("order_status", "ORDER_PAID")
                     ->where("code", $code)
                     ->where("id", $id)
                     ->update("orders");
            echo "پرداخت شما انجام شد.";
            return;
        }
        echo 'Customer not found';
    }

    public function buy_credit()
    {
        $uid      = $this->input->get('uid');
        $amount   = $this->input->get('amount');
        $customer = $this->db->get_where('customers', array("id" => $uid))->row(0, "array");
        //fixme check for  valid customer
        $new_amount = $customer["wallet_credit"] + $amount;
        $this->db->trans_start();
        $this->db->where('id', $uid)
                 ->set("wallet_credit", $new_amount)
                 ->update("customers");
        $this->db->insert("wallet_transactions", array("amount"      => $amount,
                                                       "customer_id" => $uid));
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {

        }
        echo 'Successful buy';
    }

    // public function index()
    // {
    //     $data = array("active_menu" => "m-manage-customers");
    //     $data['title'] = 'مدیریت مشتری ها';
    //     $this->view('customers/index', $data);
    // }

    public function invoice()
    {
        require_once(APPPATH . "third_party/tcpdf/tcpdf.php");
        $this->load->library("Faktor");
        $this->load->model("Orders_model");

        $format   = $this->input->get("format");
        $order_id = $this->input->get("order_id");

        if (!$order_id) die("Order id not provided.");

        $order = $this->db->get_where("orders", array("id" => $order_id))->row(0, "array");
        if (!$order) die("Order not found.");

        // $order = $this->db->select("order.*")
        //     ->from("orders")
        //     ->where("id",$order_id)
        $order = array_merge($order, $this->Orders_model->get_order_detail($order));
        // pr($order);

        $faktor = $this->faktor->set_factor_number($order['id'])
                               ->set_factor_date($order["created_at"])
                               ->set_customer_name($order['customer_id'])
                               ->set_customer_id($order['customer_id'])
                               ->set_description('')
                               ->set_payable($order['total_amount']);

        foreach ($order["lines"] as $line) {
            $faktor->add_order_item(array("desc"        => $line["description"],
                                          "num"         => $line["quantity"] . " " . $line["unit_name"],
                                          "price"       => $line["price"],
                                          "total_price" => $line["quantity"] * $line["price"]));
        }

        $html = $faktor->generate_ui();
        if ($format == 'html') {
            echo $this->load->view("partial_layout/header", '', true);
            echo $html;
            echo $this->load->view("partial_layout/footer", '', true);
            return;
        }

        // send pdf output
        if ($format == 'pdf') {

            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('fanacmp.ir');
            // $pdf->SetTitle('');
            // $pdf->SetSubject('');
            // $pdf->SetKeywords('');

            // set default header data
            //         $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
            //         $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            $l['a_meta_charset']  = 'UTF-8';
            $l['a_meta_dir']      = 'rtl';
            $l['a_meta_language'] = 'fa';
            $l['w_page']          = 'صفحه';
            $pdf->setLanguageArray($l);
            // ---------------------------------------------------------

            // set default font subsetting mode
            $pdf->setFontSubsetting(true);

            // Set font
            // dejavusans is a UTF-8 Unicode font, if you only need to
            // print standard ASCII chars, you can use core fonts like
            // helvetica or times to reduce file size.
            $pdf->SetFont('dejavusans', '', 10, '', true);

            // Add a page
            // This method has several options, check the source code documentation for more information.
            $pdf->AddPage();

            // set text shadow effect
            //         $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));


            // $html = $this->load->view("partial_layout/header", '', true);

            // $html .=$this->load->view("partial_layout/footer", '', true);
            // Print text using writeHTMLCell()
            // $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
            $pdf->writeHTML($html, true, false, true, false, '');
            // ---------------------------------------------------------

            // Close and output PDF document
            // This method has several options, check the source code documentation for more information.
            $pdf->Output('example_001.pdf', 'I');
        }
    }
}
