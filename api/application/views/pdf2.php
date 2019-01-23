<style>

    @font-face {
        font-family: BYekan;

        src: url(<?php echo base_url() ?>assets/fonts/BYekan+%20Bold.ttf);

    }

    /* =======================================================
    *
    * 	Template Style
    *	Edit this section
    *
    * ======================================================= */
    body {
        font-family: BYekan !important;

        font-size: 16px;
        background: #fff;
        color: black;
        font-weight: 300;
        overflow-x: hidden;
        direction: rtl;
    }

</style>
<body>

<div class="row" style="    margin: 0px 10% 0px 10%;">
    <table class="first" style="font-size: 30px;width: 100%;">
        <tr>

            <td  align="right"><img height="130" src="<?php echo base_url() ?>assets/shadleen.png"></td>
            <td  align="left"><br><br>
                <img src="<?php echo base_url() ?>../panel/application/libraries/barcode.php?codetype=Code39&size=30&text= <?php echo $order[0]->barcode ?>&print=true&sizefactor=0.8"/><br>
                <span style="font-size:30px">تاریخ: <?php echo $order_date ?></span><br>
                <span style="font-size:30px">ساعت: <?php echo $order_time ?></span><br><br>


                <!--      --><?php
                //      include 'application\libraries\barcode2\BarcodeGenerator.php';
                //      include 'application\libraries\barcode2\BarcodeGeneratorPNG.php';
                //      $generator = new BarcodeGeneratorPNG();
                //      $imageData = base64_encode($generator->getBarcode('081231723897', $generator::TYPE_CODE_128));
                //      $src = 'data: image/png;base64,'.$imageData;
                //      echo '<img src="'.$src.'">';
                //
                ?>





                <style>
                    /*.barcode*/
                    /*{*/
                    /*height:200px !important;*/
                    /*}*/
                    /*.barcode div*/
                    /*{*/
                    /*height:100px !important;*/
                    /*font-weight: bold;*/
                    /*font-family: cursive;*/
                    /*}*/
                    /*.barcode div div*/
                    /*{*/
                    /*height:50px !important;*/
                    /*width: 5px;*/
                    /*}*/
                </style>


            </td>

        </tr>
        <tr>

            <td width="100%" align="center" colspan="2"><span style="font-size:45px; font-weight:bold">بلیط</span></td>

        </tr>

        <tr>
            


            <td align="right"><span style=" font-weight:10;">شماره خرید :</span></td>
            <td align="left"><span
                    style=""><?php echo $order[0]->peygiri ?></span></td>


        </tr>
        <tr>
            


            <td align="right"><span style=" font-weight:10;">تفریح : </span></td>
            <td align="left"><span
                    style=""><?php echo $order[0]->fun_name ?></span></td>


        </tr>
        <tr>
            


            <td align="right"><span style=" font-weight:10;">شهر : </span></td>
            <td align="left"><span
                    style=""><?php echo $order[0]->fun_city_name ?></span></td>


        </tr>
        <tr>
            


            <td align="right"><span style=" font-weight:10;">تاریخ : </span></td>
            <td align="left"><span
                    style=""><?php echo $order[0]->fun_date ?></span></td>


        </tr>
        <tr>
            


            <td align="right"><span style=" font-weight:10;">سانس : </span></td>
            <td align="left"><span
                    style=""><?php echo $order[0]->sans_name ?></span></td>
        </tr>
        <tr>
            


            <td align="right"><span style=" font-weight:10;">مبلغ پایه : </span></td>
            <td align="left"><span
                    style=""><?php echo number_format($order[0]->price) ?></span>ریال
            </td>
        </tr>
        <tr>
            


            <td align="right"><span style=" font-weight:10;">تعداد : </span></td>
            <td align="left"><span
                    style=""><?php echo $order[0]->num ?></span>عدد
            </td>
        </tr>
        <tr>
            


            <td align="right"><span style=" font-weight:10;">مبلغ کل : </span></td>
            <td align="left"><span
                    style=""><?php echo number_format($order[0]->total_price) ?></span>ریال
            </td>
        </tr>
        <tr>
            


            <td align="right"><span style=" font-weight:10;">شماره خرید :</span></td>
            <td align="left"><span
                    style=""><?php echo $order[0]->peygiri ?></span></td>


        </tr>
        <tr>
            


            <td align="right"><span style=" font-weight:10;">خریدار : </span></td>
            <td align="left"><span
                    style=""><?php echo $order[0]->fullname ?></span></td>


        </tr>
        <tr>
            


            <td align="right"><span style=" font-weight:10;">کد ملی : </span></td>
            <td align="left"><span
                    style=""><?php echo $order[0]->national_code ?></span></td>


        </tr>
        <tr>
            


            <td align="right"><span style=" font-weight:10;">موبایل : </span></td>
            <td align="left"><span
                    style=""><?php echo $order[0]->mobile ?></span></td>


        </tr>




    </table>


</div>

</body> 