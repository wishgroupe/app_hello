<?php
/**
 * install.php
 */
namespace Hello\APIs;

use Hello\Databases\Subscriber;
use Hello\Lib\AbstractController;
use Hello\Lib\Tools;

class Install extends AbstractController {
    public function api_get(){
        $f3 = \Base::instance();

        $data = @Tools::signedRequest_decode($_GET['signedRequest'], $f3->get('BMAPP.APP_KEY'));
        if($data) {
            if(isset($data['installationCode']) && $data['installationCode'] == $f3->get('BMAPP.APP_INSTALLATION_CODE')) {
                //Installation's code confirmation
                $jsonData = array('result' => true, 'expirationDate' => 0);
            } else if(isset($data['appToken']) &&
                isset($data['customerToken']) &&
                isset($data['customerName']) &&
                isset($data['expirationDate'])) {//App's Token acknowledgment of receipt

                /*-----------------
                YOUR APP SPECIFIC CODE
                Do not forget to store into a database the Token receive during the installation process.
                -----------------*/
                $app = new Subscriber();
                $subscriber = $app->getSubscriberFromCustomerToken($data['customerToken']);
                if($subscriber)
                    $app->updateSubscriber(['subscriber' => [
                        'customerName' => $data['customerName'],
                        'appToken' => $data['appToken'],
                        'expirationDate' => $data['expirationDate']
                    ]], $subscriber['id']);
                else
                    $app->createSubscriber(['subscriber' => [
                        'customerToken' => $data['customerToken'],
                        'customerName' => $data['customerName'],
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
