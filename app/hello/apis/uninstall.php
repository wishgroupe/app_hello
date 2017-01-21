<?php
/**
 * uninstall.php
 */
namespace Hello\APIs;

use Hello\Lib\AbstractController;

class Uninstall extends AbstractController {
    public function api_get(){
        echo 'uninstall';
    }
}
