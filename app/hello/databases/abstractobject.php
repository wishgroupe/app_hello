<?php
/**
 * abstractobject.php
 */

namespace Hello\Databases;

use Hello\Lib\MySQL\DbFactory;
use Hello\Lib\MySQL\DbLink;

class AbstractObject {
    /**
     * Db Instance
     * @var DbLink
     */
    protected $db = null;

    /**
     * AbstractObject constructor.
     */
    public function __construct(){
        $this->db = DbFactory::instance()->getDbApp();
    }
}
