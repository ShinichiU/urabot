<?php
/**
 * @copyright 2010 Shinichi Urabe
 */

chdir(dirname(__FILE__));
define('ROOT_PATH', realpath('./'));
define('CONFIG_PATH', ROOT_PATH.'/config/');
define('LIB_PATH', ROOT_PATH.'/lib/');
define('WEB_PATH', ROOT_PATH.'/web/');
define('BIN_PATH', ROOT_PATH.'/bin/');
require_once CONFIG_PATH.'config.php';
require_once LIB_PATH.'vendor/twitteroauth/twitteroauth.php';
