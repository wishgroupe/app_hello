<?php
/**
 * uninstall.php
 */
namespace Hello\APIs;

use Hello\Databases\Subscriber;
use Hello\Lib\AbstractController;
use Wish\Tools;

class Uninstall extends AbstractController {
    public function api_get(){
        $f3 = \Base::instance();

        $data = @Tools::signedRequest_decode($_GET['signedRequest'], $f3->get('BMAPP.APP_KEY'));
        if($data && isset($data['clientToken'])) {//Authorization's confirmation

            /*-----------------
            YOUR APP SPECIFIC CODE
            -----------------*/
            $app = new Subscriber();
            $subscriber = $app->getSubscriberFromClientToken($data['clientToken']);
            if($subscriber)
                $app->deleteSubscriber($subscriber['id']);

            $jsonData = array('result' => true);
        } else $jsonData = array('result' => false);

        header('Content-Type: application/json');
        echo json_encode($jsonData);
    }
}
