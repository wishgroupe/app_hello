<?php
/**
 * app.php
 */

namespace Database;

class App {
    /**
     * Db Instance
     * @var DbLink
     */
    protected $db = null;

    /**
     * AbstractDB constructor.
     */
    public function __construct($db){
        $this->db = $db;
    }
}
