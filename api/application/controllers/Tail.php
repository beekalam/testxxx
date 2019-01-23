<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH . "third_party" . DIRECTORY_SEPARATOR . "phptail" . DIRECTORY_SEPARATOR . "PHPTail.php";

class Tail extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
//        echo "<br/>";
//        echo FCPATH;
//        echo "<br/>";
//        die("iin tail controller");
    }

    public function tail()
    {
        // die("offline");
        $allowed_ip = '151.233.79.97';
        if (!false) {
            // require_once "lib/phptail/PHPTail.php";
            $tail_file_name = 'log-' . date('Y-m-d') . '.php';
            // $tail_file = __DIR__ . "/tmp/" . $tail_file_name;
            $tail_file = APPPATH . "logs/" . $tail_file_name;
            $tail      = new PHPTail($tail_file, 20000);
            $tail->setAjaxCallbackUrl('tail')
                // ->setBaseUrl("http://beniz.fanacmp.ir/kalairani/api/");
                 ->setBaseUrl("http://localhost/insta/api/");

            $currentSecond = date('s') + 0;
//            / sys load /////
            if ($currentSecond % 3 == 0) {
//                 $load = sys_getloadavg();
//                 ilog($load);
            }

            if (isset($_GET['ajax'])) {
                echo $tail->getNewLines($_GET['lastsize'], $_GET['grep'], $_GET['invert']);
                die();
            }

            $tail->generateGUI();
        }
    }

    public function tail2()
    {
        exit;
        function getDirContents($dir, &$results = array())
        {
            $files = scandir($dir);

            foreach ($files as $key => $value) {
                $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
                if (!is_dir($path)) {
                    $results[] = $path;
                } else if ($value != "." && $value != "..") {
                    getDirContents($path, $results);
                    $results[] = $path;
                }
            }

            return $results;
        }

        echo "<pre>" .
            var_dump(getDirContents('/var/log')) .
            "</pre>";
    }

    public function tail3()
    {
        exit;
        function writeln($in)
        {
            echo in . PHP_EOL;
        }

        function pr($in)
        {
            echo "<pre>$in</pre>";
        }

        function pre($in)
        {
            pr($in);
            exit;
        }

        $arr = array("/var/log/nginx/domains/shadleen.ir.error.log", "/var/log/nginx/domains/shadleen.ir.log");
        echo(str_repeat("**", 100));
        echo(file_get_contents("/var/log/nginx/domains/shadleen.ir.error.log"));
        echo(str_repeat("**", 100));
        echo(file_get_contents("/var/log/nginx/domains/shadleen.ir.log"));
    }

    public function tail4()
    {
        exit;
        $log = '/var/log/nginx/domains/samandtravel.com.charter.log';
        $log = '/var/log/nginx/domains/agahikhabar.com.log';
        $log = "/var/log/nginx/domains/shadleen.com.error.log";
        $log = "/var/log/nginx/domains/shadleen.com.log";
        $log = '/var/log/nginx/domains/fanacmp.ir.fpm.error.log';

        $file = array_reverse(explode("\n",file_get_contents($log)));
        $i = 0;
        foreach($file as $f){
            echo $f . "<br/>";
            if($i++ > 10) break;
        }
    }
}