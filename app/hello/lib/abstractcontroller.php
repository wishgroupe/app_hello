<?php
/**
 * abstractcontroller.php
 */

namespace Hello\Lib;

use Hello\Lib\MySQL\DbFactory;

abstract class AbstractController {
    /**
     * Controller cleaning.
     */
    public function afterRoute() {
        DbFactory::instance()->closeAll();
    }
}
