<?php
/**
 * main.php
 */
namespace Hello\APIs;

use Hello\Databases\Subscriber;
use Hello\Lib\AbstractController;
use Template;
use Wish\JWT;
use Wish\Tools;
use Wish\Web;

class Main extends AbstractController {
    public function api_get(){
        $f3 = \Base::instance();
        $data = @Tools::signedRequest_decode($_GET['signedRequest'], $f3->get('BMAPP.APP_KEY'));
        $f3->set('urlCallback', isset($data['urlCallback']) ? $data['urlCallback'] : '');

        if(isset($data['clientToken']) && isset($data['userToken'])) {
            $app = new Subscriber();
            $subscriber = $app->getSubscriberFromClientToken($data['clientToken']);
            if ($subscriber) {
                $payload = [
                    'userToken' => $data['userToken'],
                    'appToken' => $subscriber['appToken'],
                    'time' => time(),
                    'mode' => 'normal'
                ];

                $web = new Web();
                $web->setSpecificHeaders(['X-JWT-App-BoondManager: ' . JWT::encode($payload, $f3->get('BMAPP.APP_KEY'))]);
                if($response = $web->setUrl($f3->get('BMAPI.API_URL') . '/application/current-user')->get()) {
                    $f3->set('lastName', $response['data']['attributes']['lastName']);
                    $f3->set('firstName', $response['data']['attributes']['firstName']);
                }
            }
        }

        $f3->set('content', 'main.htm');
        echo Template::instance()->render('layout.htm');
    }
}
