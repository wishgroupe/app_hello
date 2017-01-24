<?php
/**
 * dbfactory.php
 */

namespace Hello\Lib\MySQL;

use Wish\MySQL\DbLink;

class DbFactory extends \Prefab
{
    /**
     * @var DbLink local database
     */
    private $dbApp;

    /**
     * return a DB instance for the local database
     * @return DbLink
     */
    public function getDbApp()
    {
        if(!$this->dbApp) $this->dbApp = $this->buildDbApp();
        return $this->dbApp;
    }

    /**
     * Build the Db for App
     * @return DbLink
     */
    private function buildDbApp()
    {
        $server = \Base::instance()->get('BMMYSQL.SERVER');
        $port = \Base::instance()->get('BMMYSQL.PORT');
        $dbName = \Base::instance()->get('BMMYSQL.DATABASE');
        $user = \Base::instance()->get('BMMYSQL.USER');
        $pass = \Base::instance()->get('BMMYSQL.PWD');

        $dsn = "mysql:host=$server;port=$port;dbname=$dbName";

        return new DbLink($dsn, $user, $pass);
    }

    /**
     * close all DB connections
     */
    public function closeDbApp()
    {
        if ($this->dbApp) {
            $this->dbApp->closeConnection();
            $this->dbApp = null;
        }
    }

    /**
     * close all DB connections
     */
    public function closeAll()
    {
        $this->closeDbApp();
    }

    /**
     * destructor
     * Close all DB connections if their are still opened
     */
    public function __destruct()
    {
        $this->closeAll();
    }
}
