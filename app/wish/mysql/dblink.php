<?php
/**
 * dblink.php
 */

namespace Wish\MySQL;

class DbLink extends \DB\SQL
{
    /**
     * get the database address
     * @return string
     */
    public function getDSN()
    {
        return $this->dsn;
    }

    /**
     * close the connection
     */
    public function closeConnection()
    {
        $this->pdo = null;
    }

    /**
     * Execute SQL statement(s)
     * @param array|string $query
     * @param null $args
     * @param int $ttl
     * @param bool $log
     * @param bool $stamp
     * @return array|FALSE|int
     */
    public function exec($query, $args = NULL, $ttl = 0, $log = TRUE, $stamp = false)
    {
        if (is_array($args)) {
            array_unshift($args, '');
            unset($args[0]);
        }
        $result = parent::exec($query, $args, $ttl, $log, $stamp);
        return $result;
    }
}
