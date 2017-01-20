<?php
/**
 * abstractcontroller.php
 */
namespace Lib;

abstract class AbstractController {
    /**
     * Controller cleaning.
     */
    public function afterRoute() {
        DbFactory::instance()->closeAll();
    }
}
