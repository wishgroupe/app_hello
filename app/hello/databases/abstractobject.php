<?php
/**
 * abstractobject.php
 */

namespace Hello\Databases;

use Hello\Lib\MySQL\DbFactory;
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

    /**
     * prepare a value for update / insert
     * @param mixed $value
     * @return string|int|boolean
     */
    protected function prepareValue($value){
        if(in_array(strtolower($value), array('null', 'now()'))) $value = strtoupper($value);
        return $value;
    }
}
