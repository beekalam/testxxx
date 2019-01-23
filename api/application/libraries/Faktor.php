<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* sample usage
        $this->faktor->set_factor_number(1)
                     ->set_factor_date("1393/11/11")
                     ->set_customer_name('moh mansouri')
                     ->set_customer_id(12)
                     ->set_description('')
                     ->set_payable(4400)
                     ->add_order_item(array("desc" => "item description", "num" => 1, "price" => 1200, "total_price" => 1200))
                     ->add_order_item(array("desc" => "item description", "num" => 1, "price" => 1200, "total_price" => 1200))
                     ->add_order_discount(array("desc" => "takhfiff...","discount_amount" => 1200));
                     ->generate_ui();
 */

class Faktor
{

    private $factor_num      = "";
    private $factor_date     = "";
    private $customer_name   = "";
    private $customer_id     = "";
    private $description     = "";
    private $payable         = 0;
    private $order_items     = array();
    private $order_discounts = array();


    public function set_factor_number($num)
    {
        $this->factor_num = $num;
        return $this;
    }

    public function set_factor_date($factor_date)
    {
        $this->factor_date = $factor_date;
        return $this;
    }

    public function set_customer_name($customer_name)
    {
        $this->customer_name = $customer_name;
        return $this;
    }

    public function set_customer_id($customer_id)
    {
        $this->customer_id = $customer_id;
        return $this;
    }

    public function add_order_item($order_item)
    {
        // order_item = array("desc" => "item description", "num"=>1,"price"=>1200,"total_price"=>1200);
        $this->order_items[] = $order_item;
        return $this;
    }

    public function set_description($description)
    {
        $this->description = $description;
        return $this;
    }

    public function set_payable($payable)
    {
        $this->payable = $payable;
        return $this;
    }

    public function add_order_discount($order_discount)
    {
        // $order_discount = array("desc" => "discount description", "discount_amount" => 1200);
        $this->order_discounts[] = $order_discount;
        return $this;
    }

    public function generate_ui()
    {
        $ret = '   
        <style>
            .factor_rows_tdb {
                text-align: right;
                direction: rtl;
                border: 1px solid #999;
                padding: 5px;
            }

            .factor_rows_td {
                text-align: right;
                direction: rtl;
                padding: 5px;
                border: 1px solid #999;
            }

            .factor_table {
                border-collapse: collapse;
                border: 1px solid #999;
                margin-top: 10px;
                margin-bottom: 10px;
            }

            .factor_text_td {
                text-align: right;
                direction: rtl;
                padding: 5px;
            }

            .factor_value_td {
                text-align: right;
                direction: rtl;
                padding: 5px;
            }

            .factor_header_text_td {
                border-bottom: 1px solid #999;
                padding-bottom: 15px;
            }
        </style>
        <table style="table-responsive" class="factor_table persian-number" width="100%">
            <tbody>
            <tr>
                <td colspan="5" class="factor_header_td">
                    <table width="100%">
                        <tbody>
                        <tr>
                            <td colspan="2" class="factor_line" style="border-top: 1px solid #000;height:5px;"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="factor_header_text_td" style="text-align: center;">
                                فاکتور
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table style="width:100%;">
                                    <tbody>
                                    <tr>

                                        <td style="" class="factor_text_td">
                                            شماره فاکتور :
                                        </td>
                                        <td class="persian_digits factor_value_td">
                                        ' . $this->factor_num . '
                                        </td>

                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <table style="width:100%;">
                                    <tbody>
                                    <tr>
                                        <td style="text-align: left" class="factor_text_td">
                                            تاریخ صدور :
                                        </td>
                                        <td class="persian_digits factor_value_td" style="text-align:left;">
                                        ' . $this->factor_date . '
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="factor_line" style="border-bottom: 1px solid #000;height:5px;"></td>
                        </tr>
                        <tr>
                            <td>
                                <table style="width:100%">
                                    <tbody>
                                    <tr>
                                        <td style="" class="factor_text_td">
                                            نام مشترک :
                                        </td>
                                        <td class="persian_digits factor_value_td">
                                        ' . $this->customer_name . '
                                        </td>
                                    </tr>
                                    <!--                                         <tr>
                                                                                <td style="" class="factor_text_td">
                                                                                    آدرس مشترک :
                                                                                </td>
                                                                                <td class="persian_digits factor_value_td">

                                                                                </td>
                                                                            </tr> -->
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <table style="width:100%">
                                    <tbody>
                                    <tr>
                                        <td style="text-align: left" class="factor_text_td">
                                            کد اشتراک :
                                        </td>
                                        <td class="persian_digits factor_value_td" style="text-align:left;">
                                        ' . $this->customer_id . '
                                        </td>
                                    </tr>
                                    <!--                                         <tr>
                                            <td style="text-align: left" class="factor_text_td">
تلفن مشترک :
                                            </td>
                                            <td class="persian_digits factor_value_td" style="text-align:left;">
                                                <?php //echo $customer->mobile;
                                    ?>
                                            </td>
                                        </tr> -->
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="factor_line" style="border-bottom:1px solid #000;height:5px;"></td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="factor_rows_td" style="">
                    ردیف
                </td>
                <td class="factor_rows_td" id="sharh_td">
                    شـــــرح
                </td>
                <td class="factor_rows_td" style="">
                    مبلغ
                </td>
                <td class="factor_rows_td" style="">
                    تعداد
                </td>
                <td class="factor_rows_td" style="">
                    مبلغ ردیف
                </td>
            </tr>
            ';

        $index = 0;
        foreach ($this->order_items as $order_item) {
            $index++;
            $order_item_price       = number_format($order_item['price']);
            $order_item_total_price = number_format($order_item['total_price']);
            $ret                    .= "<tr>
                    <td class='factor_rows_td persian_digits' style=''>
                         {$index} 
                    </td>
                    <td class='factor_rows_td persian_digits'>
                         {$order_item['desc']} 
                    </td>
                    <td class='factor_rows_td persian_digits' style=''>
                         {$order_item_price} 
                    </td>
                    <td class='factor_rows_td persian_digits' style=''>
                         {$order_item['num']}
                    </td>
                    <td class='factor_rows_td persian_digits' style=''>
                         {$order_item_total_price}  &nbsp;
                        ریال
                    </td>
                </tr>";
        }

        $ret .= '
            <!--<tr>-->
            <!--    <td class="factor_rows_td persian_digits" style="">-->
            <!--        -->
            <!--    </td>-->
            <!--    <td class="factor_rows_td persian_digits">-->
            <!--        ورودی-->
            <!--    </td>-->
            <!--    <td class="factor_rows_td persian_digits" style="">-->
            <!--        -->
            <!--    </td>-->
            <!--    <td class="factor_rows_td persian_digits" style="">-->
            <!--        ------>
            <!--    </td>-->
            <!--    <td class="factor_rows_td persian_digits" style="">-->
            <!--        --><!-- &nbsp;-->
            <!--        ریال-->
            <!--    </td>-->
            <!--</tr>-->
            ';

        foreach ($this->order_discounts as $order_discount) {

            $ret .= "
                <tr>
                    <td class='factor_rows_tdb' colspan='2'>
                        شرح تخفیف :
                    </td>
                    <td class='factor_rows_tdb' colspan='2' style='text-align:left'>
                        تخفیف :
                        {$order_discount["desc"]}
                    </td>
                    <td class='factor_rows_tdb persian_digits' style='width:120px'>
                        {$order_discount["discount_amount"]}
                         ریال
                    </td>
                </tr>
                ";
        }

        if (false) {

            $ret .= '
            <tr>
                <td class="factor_rows_tdb" colspan="2">
                    شرح بستانکاری :
            
                </td>
                <td class="factor_rows_tdb" colspan="2" style="text-align:left">
                    بستانکاری :
                </td>
                <td class="factor_rows_tdb persian_digits" style="">
            
                    ریال
                </td>
            </tr>
            
            <tr>
                <td class="factor_rows_tdb" colspan="2">
                    شرح بدهکاری :
            
                </td>
                <td class="factor_rows_tdb" colspan="2" style="text-align:left">
                    بدهکاری :
                </td>
                <td class="factor_rows_tdb persian_digits" style="">
            
                    ریال
                </td>
            </tr>
            
            <tr>
                <td colspan="5" class="factor_footer_td">
                </td>
            </tr>
            ';
        }

        $ret .= '
            <tr>
                <td class="factor_rows_tdb persian_digits" colspan="2">
                    شرح فاکتور:
                     ' . $this->description . '
                </td>
                <td class="factor_rows_tdb" colspan="2" style="text-align:left">
                    قابل پرداخت :
                </td>
                <td class="factor_rows_tdb persian_digits" style="">
                    ' . number_format($this->payable) . 'ریال
                </td>
            </tr>
            <tr>
                <td colspan="5" class="factor_footer_td">

                </td>
            </tr>
            </tbody>
        </table>
        ';

        return $ret;

    }
}

?>
