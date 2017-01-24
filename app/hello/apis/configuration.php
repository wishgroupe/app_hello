<?php
/**
 * configuration.php
 */
namespace Hello\APIs;

use Hello\Lib\AbstractController;
use Template;
use Wish\Tools;

class Configuration extends AbstractController {
    public function api_get(){
        $f3 = \Base::instance();
        $data = @Tools::signedRequest_decode($_GET['signedRequest'], $f3->get('BMAPP.APP_KEY'));
        $f3->set('urlCallback', isset($data['urlCallback']) ? $data['urlCallback'] : '');

        $f3->set('content', 'configuration.htm');
        echo Template::instance()->render('layout.htm');
    }
}
