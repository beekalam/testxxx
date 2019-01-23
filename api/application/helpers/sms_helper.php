<?php

class SmsIR_UltraFastSend
{

    /**
     * gets API Ultra Fast Send Url.
     *
     * @return string Indicates the Url
     */
    protected function getAPIUltraFastSendUrl()
    {
        return "http://RestfulSms.com/api/UltraFastSend";
    }

    /**
     * gets Api Token Url.
     *
     * @return string Indicates the Url
     */
    protected function getApiTokenUrl()
    {
        return "http://RestfulSms.com/api/Token";
    }

    /**
     * gets config parameters for sending request.
     *
     * @param string $APIKey API Key
     * @param string $SecretKey Secret Key
     * @return void
     */
    public function __construct($APIKey, $SecretKey)
    {
        $this->APIKey    = $APIKey;
        $this->SecretKey = $SecretKey;
    }


    /**
     * Ultra Fast Send Message.
     *
     * @param data[] $data array structure of message data
     * @return string Indicates the sent sms result
     */
    public function UltraFastSend($data)
    {

        $token = $this->GetToken($this->APIKey, $this->SecretKey);
        if ($token != false) {
            $postData = $data;

            $url           = $this->getAPIUltraFastSendUrl();
            $UltraFastSend = $this->execute($postData, $url, $token);
            $object        = json_decode($UltraFastSend);

            if (is_object($object)) {
                $array = get_object_vars($object);
                if (is_array($array)) {
                    $result = $array['Message'];
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }

        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * gets token key for all web service requests.
     *
     * @return string Indicates the token key
     */
    private function GetToken()
    {
        $postData   = array(

            'UserApiKey' => $this->APIKey,
            'SecretKey'  => $this->SecretKey,
            'System'     => 'php_rest_v_1_2'
        );
        $postString = json_encode($postData);

        $ch = curl_init($this->getApiTokenUrl());
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'

        ));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, count($postString));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);


        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result);
        $resp     = false;
        if (is_object($response)) {
            $resultVars = get_object_vars($response);

            if (is_array($resultVars)) {
                @$IsSuccessful = $resultVars['IsSuccessful'];
                if ($IsSuccessful == true) {
                    @$TokenKey = $resultVars['TokenKey'];
                    $resp = $TokenKey;
                } else {
                    $resp = false;
                }
            }
        }


        return $resp;
    }

    /**
     * executes the main method.
     *
     * @param postData[] $postData array of json data
     * @param string $url url
     * @param string $token token string
     * @return string Indicates the curl execute result
     */
    private function execute($postData, $url, $token)
    {

        $postString = json_encode($postData);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'x-sms-ir-secure-token: ' . $token
        ));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, count($postString));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}

// define("SMS_TEMPLATE_VERIFY_CODE", 3537);
define("SMS_TEMPLATE_VERIFY_CODE", 3737);
define("SMS_SUCCESS_BUY_MSG", 4060);

function send_sms($number, $text, $template_id = SMS_TEMPLATE_VERIFY_CODE)
{
    try {
        date_default_timezone_set("Asia/Tehran");
        // your sms.ir panel configuration
        $APIKey    = "8de34de2a306edde7cdf4161";
        $SecretKey = "!@#fana!@#";
        $data = array();
        if($template_id == SMS_TEMPLATE_VERIFY_CODE) {
            // message data
            $data = array(
                "ParameterArray" => array(
                    array(

                        "Parameter"      => "CompanyName",
                        "ParameterValue" => "شرکت مهندسي فانا"
                        // iconv("Windows-1256", "UTF-8", "شرکت مهندسي فانا")
                    ),
                    array(
                        "Parameter"      => "VerificationCode",
                        "ParameterValue" => $text
                    )
                ),
                "Mobile"         => $number,
                "TemplateId"     => $template_id
            );
        } else if($template_id == SMS_SUCCESS_BUY_MSG){
            $data = array(
                "ParameterArray" => array(
                    array(
                        "Parameter"      => "CompanyName",
                        "ParameterValue" => "شرکت مهندسي فانا"
                        // iconv("Windows-1256", "UTF-8", "شرکت مهندسي فانا")
                    ),
                    array(
                        "Parameter"      => "str",
                        "ParameterValue" => $text
                    )
                ),
                "Mobile"         => $number,
                "TemplateId"     => $template_id
            );
        }

        // $data                = array(
        //     "ParameterArray" => array(
        //         array(
        //             "Parameter"      => "VerificationCode",
        //             "ParameterValue" => iconv("Windows-1256", "UTF-8", $text)
        //         )
        //     ),
        //     "Mobile"         => $number,
        //     "TemplateId"     => $template_id
        // );
        //

        $SmsIR_UltraFastSend = new SmsIR_UltraFastSend($APIKey, $SecretKey);
        $UltraFastSend       = $SmsIR_UltraFastSend->UltraFastSend($data);
        //var_dump($UltraFastSend);

    } catch (Exeption $e) {
        //echo 'Error UltraFastSend : ' . $e->getMessage();
        return false;
    }

    return true;
}

function send_sms_verify_code($number, $text)
{
    return send_sms($number, $text, SMS_TEMPLATE_VERIFY_CODE);
}


function send_sms_success_buy($number, $text)
{
    return send_sms($number, $text, SMS_SUCCESS_BUY_MSG);
}
