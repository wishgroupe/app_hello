<?php
/**
 * subscriber.php
 */

namespace Hello\Databases;

class Subscriber extends AbstractObject {
    /**
     * Retrieve subscriber data from an App's Token
     * @param  string $customerToken
     * @return array|int|false
     */
    public function getSubscriberFromCustomerToken($customerToken) {
        $result = $this->db->exec('SELECT id, customerName, appToken, customerToken, expirationDate, creationDate FROM subscriber WHERE customerToken=?', $customerToken);
        if($result)
            return $result[0];
        else
            return false;
    }

    /**
     * Create a subscriber
     * @param array $data
     * @return int
     */
    public function createSubscriber($data) {
        if(!isset($data['subscriber']) || !is_array($data['subscriber']) ) $data['subscriber'] = [];
        if(!isset($data['subscriber']['creationDate'])) $data['subscriber']['creationDate'] = date('Y-m-d H:i:s');

        $tabKey = array();
        $tabValue = array();
        $nbValue = 1;
        foreach($data['subscriber'] as $key => $value) {
            $tabKey[] = $key;
            $tabValue[$nbValue++] = $this->prepareValue($value);
        }

        return $this->db->exec('INSERT INTO subscriber ('.implode(',',$tabKey).') VALUES ('. implode(',', array_fill(0, count($tabValue), '?')) .')', $tabValue);
    }

    /**
     * Update subscriber
     * @param int $id
     * @param array $data
     * @return boolean
     */
    public function updateSubscriber($data, $id) {
        if(isset($data['subscriber']) && is_array($data['subscriber']) && sizeof($data['subscriber']) > 0) {
            $tabKey = array();
            $tabValue = array();
            foreach ($data['subscriber'] as $key => $value) {
                $var = ':' . strtolower($key);
                $tabKey[] = $key . '=' . $var;
                $tabValue[$var] = $this->prepareValue($value);
            }

            $tabValue += [':id' => $id];

            if ($tabKey)
                return $this->db->exec('UPDATE subscriber SET ' . implode(',', $tabKey) . ' WHERE id=:id', $tabValue);
        }

        return false;
    }

    /**
     * Delete subscriber
     * @param int $id
     * @return boolean
     */
    public function deleteSubscriber($id) {
        return $this->db->exec('DELETE FROM subscriber WHERE id=?', $id);
    }
}
