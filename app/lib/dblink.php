<?php
/**
 * dblink.php
 */
namespace Lib;

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
     * @param array|string $cmds
     * @param null $args
     * @param int $ttl
     * @param bool $log
     * @param bool $stamp
     * @return array|FALSE|int
     */
    public function exec($cmds, $args = NULL, $ttl = 0, $log = TRUE, $stamp = FALSE)
    {
        if (is_array($args)) {
            array_unshift($args, '');
            unset($args[0]);
        }
        $id = Tools::getContext()->startQuery($cmds, $this->name(), $args);
        $result = parent::exec($cmds, $args, $ttl, $log);
        Tools::getContext()->endQuery($id);
        return $result;
    }
}
