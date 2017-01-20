<?php
/**
 * install.php
 */
namespace APIs;

use Database\App;
use Lib\DbFactory;

class Install {
    public function api_get(){
        echo 'install';

        $db = DbFactory::instance()->getDbApp();

        $app = new App($db);
    }
}
