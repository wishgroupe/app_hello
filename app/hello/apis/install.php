<?php
/**
 * install.php
 */
namespace Hello\APIs;

use Hello\Databases\Subscriber;
use Hello\Lib\AbstractController;
use Wish\Tools;

class Install extends AbstractController {
    public function api_get(){
        $f3 = \Base::instance();

        $data = @Tools::signedRequest_decode($_GET['signedRequest'], $f3->get('BMAPP.APP_KEY'));
        if($data) {
            if(isset($data['installationCode']) && $data['installationCode'] == $f3->get('BMAPP.APP_INSTALLATION_CODE')) {
                //Installation's code confirmation
                $jsonData = array('result' => true);
            } else if(isset($data['appToken']) &&
                isset($data['clientToken']) &&
                isset($data['clientName'])) {//App's Token acknowledgment of receipt

                /*-----------------
                YOUR APP SPECIFIC CODE
                Do not forget to store into a database the Token receive during the installation process.
                -----------------*/
                $data['expirationDate'] = !isset($data['expirationDate']) ? 0 : strtotime($data['expirationDate']);

                $app = new Subscriber();
                $subscriber = $app->getSubscriberFromClientToken($data['clientToken']);
                if($subscriber)
                    $app->updateSubscriber(['subscriber' => [
                        'clientName' => $data['clientName'],
                        'appToken' => $data['appToken'],
                        'expirationDate' => $data['expirationDate']
                    ]], $subscriber['id']);
                else
                    $app->createSubscriber(['subscriber' => [
                        'clientToken' => $data['clientToken'],
                        'clientName' => $data['clientName'],
                        'appToken' => $data['appToken'],
                        'expirationDate' => $data['expirationDate']
                    ]]);

                $jsonData = array('result' => true);
            } else $jsonData = array('result' => false);
        } else $jsonData = array('result' => false);

        header('Content-Type: application/json');
        echo json_encode($jsonData);
    }
}
