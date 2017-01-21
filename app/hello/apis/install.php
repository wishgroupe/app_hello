<?php
/**
 * install.php
 */
namespace Hello\APIs;

use Hello\Databases\Subscriber;
use Hello\Lib\AbstractController;

class Install extends AbstractController {
    public function api_get(){
        echo 'install';

        $app = new Subscriber();
        $subscriber = $app->getSubscriberFromAppToken('12345');

        var_dump($subscriber);
    }
}
