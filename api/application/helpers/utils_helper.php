<?php
function _pr()
{
    $num_args = func_num_args();
    if ($num_args == 1 && is_array(func_get_arg(0))) {
        $in = func_get_arg(0);
        echo "<pre style='direction:ltr'>";
        echo print_r($in[0], true);
        echo "</pre>";
        return;
    }

    if ($num_args > 0) {
        for ($i = 0; $i < $num_args; $i++) {
            $in = func_get_arg($i);
            echo "<pre style='direction:ltr'>";
            echo print_r($in, true);
            echo "</pre>";
        }
    }
}

function pr($in = null)
{
    echo "<pre style='direction:ltr !important;margin-right:20px'>";
    echo print_r($in, true);
    echo "</pre>";
}

function pre()
{
    $arr = func_get_args();
    foreach ($arr as $a) {
        pr($a);
    }
    exit;
    //exit;
}

function prpost()
{
    echo "<pre style='direction:ltr'>";
    echo print_r($_POST, true);
    echo "</pre>";
}

function prget()
{
    echo "<pre style='direction:ltr'>";
    echo print_r($_GET, true);
    echo "</pre>";
}

function prrequest()
{
    echo "<pre style='direction:ltr'>";
    echo print_r($_POST, true);
    echo print_r($_GET, true);
    echo "</pre>";
}

function xlog($in)
{
    if (!function_exists("log_message")) {
        return;
    }
    if (is_array($in) || is_object($in)) {
        log_message("debug", print_r($in, true));
    } else {
        log_message("debug", $in);
    }
}
//
// function visset($in, $default = "")
// {
//     return isset($in) ? $in : $default;
// }
//
// function eisset($in, $default = "")
// {
//     echo isset($in) ? $in : $default;
// }

function ejson()
{
    $num_args = func_num_args();
    if ($num_args == 1 && is_array(func_get_arg(0))) {
        echo json_encode(func_get_arg(0));
        return;
    }

    $flag     = false;
    $msg      = "";
    $msg_name = "error";
    if ($num_args == 1 && is_bool(func_get_arg(0))) {
        $flag = func_get_arg(0);
        if ($flag) {
            $msg_name = "msg";
        }
    }

    if ($num_args == 2 && is_string(func_get_arg(1))) {
        $flag = func_get_arg(0);
        $msg  = func_get_arg(1);
    }
    echo json_encode(array("success" => $flag, $msg_name => $msg));
}
//
// function getpost_isset()
// {
//     $num_args = func_num_args();
//     $ok       = $num_args > 0;
//     for ($i = 0; $i < $num_args; $i++) {
//         $ok = $ok and (isset($_POST[func_get_arg($i)]) || isset($_GET[func_get_arg($i)]));
//     }
//     return $ok;
// }

function startsWith($string, $search, $case = true)
{
    if ($case)
        return strpos($string, $search, 0) === 0;
    return stripos($string, $search, 0) === 0;
}
//
// function o2a($o)
// {
//     $a = (array)$o;
//     foreach ($a as &$v) {
//         if (is_object($v)) {
//             $v = o2a($v);
//         }
//     }
//     return $a;
// }


function object_to_array($obj)
{
    if (is_object($obj)) $obj = (array)$obj;
    if (is_array($obj)) {
        $new = array();
        foreach ($obj as $key => $val) {
            $new[$key] = object_to_array($val);
        }
    } else $new = $obj;
    return $new;
}

function mobile_number_is_valid($mob)
{
    if (!is_numeric($mob))
        return false;

    if (strlen($mob) != 11)
        return false;

    if (!startsWith($mob, "09"))
        return false;

    return true;
}

function format_currency($in, $suffix = "ریال")
{
    if ($suffix == "$")
        return '$ ' . $in;

    if (is_string($in))
        $in = floatval($in);

    return number_format($in) . " " . $suffix;
}
//
// function et($tag, $content, $attrs = "")
// {
//     echo "<{$tag} {$attrs}>{$content}</{$tag}>";
// }
//
// function _if($op, $value, $if, $else)
// {
//     if (function_exists($op)) {
//         if ($op($value))
//             return $if;
//         else
//             return $else;
//     }
// }
//
// function eif($op, $value, $if, $else)
// {
//     echo _if($op, $value, $if, $else);
// }
//

function base64_img_encode($data)
{
    // pre(array_keys(get_defined_vars()));
    if (!class_exists("finfo")) {
        throw new Exception("finfo does not exist");
    }

    $finfo       = new finfo(FILEINFO_MIME_TYPE);
    $type        = $finfo->buffer($data);
    $legal_types = array("image/jpeg", "image/png", "image/jpg");
    if (!in_array($type, $legal_types)) {
        throw new Exception("mime type is not image");
    }
    return 'data:' . $type . ';base64,' . base64_encode($data);
}

function base64_img_encode_from_file($path)
{
    // $type = pathinfo($path, PATHINFO_EXTENSION);
    if (!file_exists($path)) {
        throw new Exception("file '$path' does not exist");
    }
    $data = file_get_contents($path);
    return base64_img_encode($data);
}

function val($item = null, $default = null)
{
    return empty($item) ? $default : $item;
}
//
// function make_bootstrap_modal($data = '')
// {
//     /* example usage
//         $data=array("title"     =>"",
//                     "class"     =>"",
//                     "id"        =>"",
//                     "content"   => "");
//         echo make_bootstrap_modal($data);
//     */
//     $body = $title = $class = $id = '';
//
//     if (is_string($data)) {
//         $body = $data;
//     }
//
//     if (is_string($data) && strpos($data, "|") !== false) {
//         $body = '';
//         $data = explode("|", $data);
//         $narr = array();
//         foreach ($data as $item) {
//             list($k, $v) = explode("=", $item);
//             $narr[$k] = $v;
//         }
//         $data = $narr;
//     }
//
//     if (is_array($data)) {
//         $title = isset($data['title']) ? $data['title'] : "";
//         $class = isset($data['class']) ? $data['class'] : "";
//         $id    = isset($data["id"]) ? $data['id'] : "";
//         $body  = isset($data["content"]) ? $data["content"] : "";
//     }
//     return '
// <div class="modal fade ' . $class . '" tabindex="-1" id="' . $id . '" role="dialog"
//          aria-labelledby="modalLabel" aria-hidden="true">
//     <div class="modal-dialog" role="document">
//         <div class="modal-content">
//           <div class="modal-header">
//             <h5 class="modal-title" id="modalLabel">' . $title . '</h5>
//             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
//               <span aria-hidden="true">&times;</span>
//             </button>
//           </div>
//           <div class="modal-body">' .
//         $body .
//         '</div>
//           <div class="modal-footer">
//           </div>
//         </div>
//     </div>
// </div> <!-- end model -->
//         ';
// }

// echo make_bootstrap_modal("class=test|id=test|title=qqq|content=content is here");
// exit;

// function get($item)
// {
//     $ok = startsWith("session.",$item) or startsWith("get.") or startsWith("post.");
//     list($arr,$i) = explode(".",$item);

//     if($arr=="session") return $_SESSION[$i];

//     if($arr == "get") return $_GET[$i];

//     if($arr == "post") return $_POST[$i];

//     throw new Exception("invalid arr name to get;");
// }

// function set($item,$val)
// {
//     $ok = startsWith("session.",$item) or startsWith("get.") or startsWith("post.");
//     list($arr,$i) = explode(".",$item);

//     if($arr=="session")  $_SESSION[$i] = $val;

//     if($arr == "get")  $_GET[$i] = $val;

//     if($arr == "post")  $_POST[$i] = $val;

//     throw new Exception("invalid arr name to set;");
// }



/**
 * Check if a string is json encoded
 *
 * @param  string $string string to check
 * @return bool
 */
function is_json($string)
{
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}

$_dbg = "";
function dbg($in)
{
    global $_dbg;
    if (is_string($in)) {
        $_dbg .= $in . PHP_EOL;
    }
}

function gdbg()
{
    global $_dbg;
    return $_dbg;
}


if (!function_exists("base64_imagestring_save")) {
    function base64_imagestring_save($data, $file_path, $file_name)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }

            $data = base64_decode($data);

            if ($data === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }


        $file_name_with_extension = "{$file_name}.{$type}";
        $res                      = file_put_contents("{$file_path}" . DIRECTORY_SEPARATOR . "{$file_name_with_extension}", $data);
        if ($res === false) {
            return false;
        }

        return $file_name_with_extension;
    }
}


if(!function_exists("valid_base64_imagestring")){
    function valid_base64_imagestring($data){
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                return false;
            }

            $data = base64_decode($data);

            if ($data === false) {
                // throw new \Exception('base64_decode failed');
                return false;
            }
        } else {
            // throw new \Exception('did not match data URI with image data');
            return false;
        }

        return true;
    }
}


if(!function_exists("build_options_from_array")){
    function build_options_from_array($array,$key,$value){
        $res = "";
        foreach($array as $arr){
            $res .= "<option value='{$arr[$key]}'>{$arr[$value]}</option>";
        }
        return $res;
    }
}

function tomorrow_unix()
{
    $d        = date_add(date_create(date('Y-m-d')), date_interval_create_from_date_string("1 days"));
    $tomorrow = $d->format('Y-m-d');
    return strtotime($tomorrow);
    // pr($tomorrow);
}

function en_num($input)
{
    $en_num = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ".", ".", "."); //".",".","." dirty code
    $fa_num = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", ",", "٫", ".");
    return str_replace($fa_num, $en_num, $input);
}

function fa_num($input)
{
    $en_num = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
    $fa_num = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹");
    return str_replace($en_num, $fa_num, $input);
}