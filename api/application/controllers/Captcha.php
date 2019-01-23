<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;

class Captcha extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }


    public function get_image_temp()
    {
        header('Content-type: image/jpeg');
        $builder = new CaptchaBuilder;
        $builder->build();
        $builder->output();
    }

    // simple captcha
    public function get_image()
    {

        // Will build phrases of 3 characters
        // $phraseBuilder = new PhraseBuilder(4);

        // Will build phrases of 5 characters, only digits
        $phraseBuilder = new PhraseBuilder(4, '0123456789');

        // Pass it as first argument of CaptchaBuilder, passing it the phrase builder
        $captcha = new CaptchaBuilder(null, $phraseBuilder);

        $this->session->set_userdata("captcha", $captcha->getPhrase());

        $captcha->setMaxAngle(0)
                ->setMaxOffset(0)
                ->setDistortion(false)
                ->setMaxBehindLines(0)
                ->setMaxFrontLines(0)
                ->setIgnoreAllEffects(true);

        if(isset($_GET['format'])){
            echo  $captcha->build()->inline();
        } else {
            header('Content-type: image/jpeg');
            $captcha->build()->output();
        }
    }

}