<?php
/**
 * main.php
 */
namespace Hello\APIs;

use Hello\Lib\AbstractController;

class Main extends AbstractController {
    public function api_get(){
        echo 'main';
    }
}
