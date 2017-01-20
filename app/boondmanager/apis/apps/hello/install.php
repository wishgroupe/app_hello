<?php
/**
 * install.php
 */
namespace BoondManager\APIs\Apps\Hello;

use BoondManager\Databases\Apps\Hello\Subscriber;
use BoondManager\Lib\MySQL\DbFactory;

class Install {
    public function api_get(){
        echo 'install';

        $app = new Subscriber();
        $subscriber = $app->getSubscriberFromAppToken('12345');

        var_dump($subscriber);
    }
}
