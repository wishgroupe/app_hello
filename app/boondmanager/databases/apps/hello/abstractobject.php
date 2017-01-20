<?php
/**
 * abstractobject.php
 */

namespace BoondManager\Databases\Apps\Hello;

use BoondManager\Lib\MySQL\DbFactory;
use Wish\MySQL\DbLink;

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
