<?php
/**
 * main.php
 */
namespace Hello\APIs;

use Hello\Lib\AbstractController;
use Hello\Lib\Tools;
use Template;

class Main extends AbstractController {
    public function api_get(){
        $f3 = \Base::instance();
        $data = @Tools::signedRequest_decode($_GET['signedRequest'], $f3->get('BMAPP.APP_KEY'));
        $f3->set('urlCallback', isset($data['urlCallback']) ? $data['urlCallback'] : '');

        $f3->set('content', 'main.htm');
        echo Template::instance()->render('layout.htm');
    }
}
