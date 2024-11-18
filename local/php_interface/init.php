<?php
if(file_exists($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/include/const.php')) {
    require_once $_SERVER["DOCUMENT_ROOT"].'/local/php_interface/include/const.php';
}

if(file_exists($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/include/agent.php')) {
    require_once $_SERVER["DOCUMENT_ROOT"].'/local/php_interface/include/agent.php';
}

if(file_exists($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/include/event.php')) {
    require_once $_SERVER["DOCUMENT_ROOT"].'/local/php_interface/include/event.php';
}
