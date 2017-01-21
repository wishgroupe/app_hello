<?php
/**
 * subscriber.php
 */

namespace Hello\Databases;

class Subscriber extends AbstractObject {
    /**
     * Retrieve subscriber data from an App's Token
     * @param  string  $appToken
     * @return array|int|false
     */
    public function getSubscriberFromAppToken($appToken) {
        $result = $this->db->exec('SELECT id, customerName, appToken, customerToken, expirationDate, creationDate FROM subscriber WHERE appToken=?', $appToken);
        if($result)
            return $result[0];
        else
            return false;
    }
}
