<?php

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

