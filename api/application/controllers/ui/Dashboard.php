<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . "controllers/Base.php");

class Dashboard extends Base
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('Jalali_date');
    }

    public function index()
    {
        $data["active_menu"]                = 'm-dashboard';
        $data["num_customers"]              = '';
        $data["num_orders_created"]         = '';
        $data["num_orders_created_today"]   = '';
        $data["num_orders_paied_today"]     = '';
        // $data["num_customers"]              = $this->run_query("SELECT COUNT(*) as res FROM customers");
        // $data["num_orders_created"]         = $this->run_query("SELECT COUNT(*) as res FROM orders where  order_status='ORDER_CREATED'");
        // $data["num_orders_created_today"]   = $this->run_query("SELECT COUNT(*) as res FROM orders where  (order_status='ORDER_CREATED' and created_at >= '" . date("Y-m-d 00:00:01") ."')");
        // $data["num_orders_paied_today"]     = $this->run_query("SELECT COUNT(*) as res FROM orders where  (order_status='ORDER_PAID' and created_at >= '" . date("Y-m-d 00:00:01") . "')");
        // $data["num_orders_prepayment_done"] = $this->run_query("SELECT COUNT(*) as res FROM orders where  order_status='ORDER_PREPAYMENT_DONE'");
        // $data["num_orders_shipped"]         = $this->run_query("SELECT COUNT(*) as res FROM orders where  order_status='ORDER_SHIPPED'");
        // $data["num_orders_paid"]            = $this->run_query("SELECT COUNT(*) as res FROM orders where  order_status='ORDER_PAID'");


        // var_dump($data);
        // exit;
        // $data["num_karjoo"]              = 2;
        // $data["num_products"]            = 2;
        // $data["num_job_ads"]             = 2;
        // $data["num_unapproved_products"] = 2;
        // $data["num_unapproved_jobs"]     = 2;
        // $data["num_bets"]                = 2;
        // $data["num_contests"]            = 2;

        // $select = "SELECT count(*) as num_unapproved_karjoo from karjoo where admin_approved=0";
        // $num_unapproved_karjoo = $this->db->query($select)->row(0,"array");
        // $num_unapproved_karjoo = isset($num_unapproved_karjoo["num_karjoo"]) ? $num_unapproved_karjoo["num_karjoo"] : 0;
        // pre($data);
        $data['title'] = 'ویترین';
        // pr($data);exit;
        $this->view('dashboard', $data);
    }

    private function run_query($q)
    {
        $query_res = $this->db->query($q)->row(0, "array");
        $query_res = isset($query_res["res"]) ? $query_res["res"] : 0;
        return $query_res;
    }

    public function test_pdf()
    {
        require_once(APPPATH . "third_party/tcpdf/tcpdf.php");

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('fanacmp.ir');
        $pdf->SetTitle('TCPDF Example 001');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

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


        $this->load->library("Faktor");
        // $html = $this->load->view("partial_layout/header", '', true);
        $html = $this->faktor->set_factor_number(1)
                             ->set_factor_date("1393/11/11")
                             ->set_customer_name('moh mansouri')
                             ->set_customer_id(12)
                             ->set_description('')
                             ->set_payable(4400)
                             ->add_order_item(array("desc" => "item description", "num" => 1, "price" => 1200, "total_price" => 1200))
                             ->add_order_item(array("desc" => "item description", "num" => 1, "price" => 1200, "total_price" => 1200))
                             ->generate_ui();
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
