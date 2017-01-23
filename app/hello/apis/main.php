<?php
/**
 * main.php
 */
namespace Hello\APIs;

use Hello\Lib\AbstractController;
use Hello\Lib\Tools;
use View;

class Main extends AbstractController {
    public function api_get(){
        $f3 = \Base::instance();
        $data = @Tools::signedRequest_decode($_GET['signedRequest'], $f3->get('BMAPP.APP_KEY'));
        $f3->set('urlCallback', isset($data['urlCallback']) ? $data['urlCallback'] : '');
        echo View::instance()->render('main.htm');
    }
}
