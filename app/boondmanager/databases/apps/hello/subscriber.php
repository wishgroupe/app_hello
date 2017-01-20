<?php
/**
 * subscriber.php
 */

namespace BoondManager\Databases\Apps\Hello;

class Subscriber extends AbstractObject{
    /**
     * Retrieve all data related to a business unit
     * @param  integer  $id
     * @return Model|false
     */
    public function getSubscriberFromAppToken($appToken) {
        $result = $this->db->exec('SELECT id, customerName, appToken, customerToken, expirationDate, creationDate FROM subscriber WHERE appToken=?', $appToken);
        if($result)
            return $result[0];
        else
            return false;
    }
}
